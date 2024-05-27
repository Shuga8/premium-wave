/** HTML ELEMENTS */
const url = "premium-wave";
const controlButtons = document.querySelectorAll(".control-tab");
const afterDisplay = document.querySelector(".control-after-display");
const tradeChartDisplay = document.querySelector(".trading-chart-display");
const tradeFormDisplay = document.querySelector(".trading-form-display");
const tradeFom = document.querySelector(".trade-form");
const btnGroup = document.querySelector(".button-group");
const tradeBtnGroup = document.querySelector(".trade-button-group");
const accordionBtn = document.querySelector(".accordion-btn");
const accordionContent = document.querySelector(".accordion-content");
const closeDisplayBtn = document.querySelector(".close-display-btn");
const bots = document.querySelectorAll(".bot-trading");
const coinmarketcap_api_key = "552675b6-f913-4a97-adcc-bdbf6ccd37d9";
const iexcloud_api_key = "pk_775afb121446472ebe55fce3abd3cfe9";
const fastforex_api_key = "8ac3c2cc1d-a6dd8e1f96-se4y2q";

let coin_rate = null;
let stop_loss = null;
let take_profit = null;
let lotsize = 0.1;
let currency_type = "currency";
let coin_symbol = "AUD";
let open_at_rate_is_checked = false;
let open_rate = null;

let cryptoRates = {};
let currencyRates = [];
let stockRates = [];
let commodityRates = [];

bots.forEach((bot) => {
  bot.addEventListener("click", async function (e) {
    if (bot.classList.contains("bot-trading-1")) {
      lotsize = 0.3;
    } else {
      lotsize = 0.5;
    }

    const randomFiat = ["crypto", "stock", "currency", "commodity"];
    const randomStock = ["AAPL", "IBM", "TSLA", "XOM", "MSFT", "AIG"];
    const randomCrypto = ["BTC", "ETH", "BNB", "LEO", "SOL", "TON"];
    const randomCurrency = ["EUR", "GBP", "AUD", "CNY", "CAD", "JPY"];
    const randomCommodity = ["GOLD", "CORN", "LEAD", "GF", "NG", "GDP"];

    const confirmBot = confirm(
      "Are you sure you want to set an automatic trade?"
    );

    if (confirmBot == true) {
      currency_type = randomFiat[Math.floor(Math.random() * randomFiat.length)];
      await botPreset(
        randomStock,
        randomCrypto,
        randomCurrency,
        randomCommodity
      );
      // notify(
      //   "success",
      //   `type=${currency_type}, symbol=${coin_symbol}, rate=${coin_rate}, stop_loss=${stop_loss}, take_profit=${take_profit}`
      // );
      $(".trade-btn").click();
    } else {
      notify("error", "Bot auto-trade has cancelled");
    }
  });
});

document.querySelectorAll(".potential-button").forEach((button) => {
  button.setAttribute("disabled", true);
});

async function botPreset(
  randomStock,
  randomCrypto,
  randomCurrency,
  randomCommodity
) {
  if (currency_type == "currency") {
    coin_symbol =
      randomCurrency[Math.floor(Math.random() * randomCurrency.length)];
    coin_rate = await getCurrencyRate(coin_symbol);
  } else if (currency_type == "crypto") {
    coin_symbol = randomCrypto[Math.floor(Math.random() * randomCrypto.length)];
    coin_rate = cryptoRates[coin_symbol];
  } else if (currency_type == "stock") {
    coin_symbol = randomStock[Math.floor(Math.random() * randomStock.length)];
    coin_rate = await getStockRate(coin_symbol);
  } else if (currency_type == "commodity") {
    randomCommodity[Math.floor(Math.random() * randomCommodity.length)];
    coin_rate = await getCommodityRate(coin_symbol);
  }

  if (coin_rate < 10) {
    stop_loss = parseFloat(coin_rate) - 0.0009;
    take_profit = parseFloat(coin_rate) + 0.0009;

    stop_loss = parseFloat(stop_loss.toFixed(4));
    take_profit = parseFloat(take_profit.toFixed(4));
  } else {
    stop_loss = parseFloat(coin_rate) - 9.09;
    take_profit = parseFloat(coin_rate) + 9.09;

    stop_loss = parseFloat(stop_loss.toFixed(2));
    take_profit = parseFloat(take_profit.toFixed(2));
  }
}

document.querySelector("#lot").addEventListener("change", function (e) {
  let value = parseFloat(this.value) * 10;

  lotsize = this.value;

  tradeFom.querySelector(".pips-value").textContent = `$${value}`;

  let returnval = parseFloat(value * 53.87).toFixed(2);

  tradeFom.querySelector(".required-margin").textContent = `$${returnval}`;
});

document
  .querySelector("#open_rate_check")
  .addEventListener("click", function (e) {
    let checker = this;

    accordionContent
      .querySelectorAll(".potential-open-rate-button")
      .forEach((btn) => {
        if (checker.checked) {
          btn.removeAttribute("disabled");
          open_at_rate_is_checked = true;
          let currenctEther = accordionContent.querySelector(
            ".potential-open-rate-value"
          ).textContent;

          open_rate = currenctEther;

          btn.addEventListener("click", function (e) {
            let ether = accordionContent.querySelector(
              ".potential-open-rate-value"
            ).textContent;

            ether = parseFloat(ether);

            if (this.classList.contains("increment")) {
              setPotentialIncrementVisuals(ether);
            } else if (this.classList.contains("decrement")) {
              setPotentialDecrementVisuals(ether);
            }

            open_rate = ether.toFixed(4);
          });
        } else {
          btn.setAttribute("disabled", true);
          open_at_rate_is_checked = false;
          open_rate = null;
          setVisuals();
        }
      });
  });

document
  .querySelector("#stop_loss_check")
  .addEventListener("click", function (e) {
    let checker = this;

    accordionContent
      .querySelectorAll(".potential-stop-loss-button")
      .forEach((btn) => {
        if (checker.checked) {
          btn.removeAttribute("disabled");

          btn.addEventListener("click", function (e) {
            if (this.classList.contains("increment")) {
              setIncrementTargetVisuals(
                "potential-loss-value",
                "set-sell-value"
              );
            } else if (this.classList.contains("decrement")) {
              setDecrementTargetVisuals(
                "potential-loss-value",
                "set-sell-value"
              );
            }
          });
        } else {
          btn.setAttribute("disabled", true);
          setVisuals();
        }
      });
  });

document
  .querySelector("#take_profit_check")
  .addEventListener("click", function (e) {
    let checker = this;

    accordionContent
      .querySelectorAll(".potential-take-profit-button")
      .forEach((btn) => {
        if (checker.checked) {
          btn.removeAttribute("disabled");

          btn.addEventListener("click", function (e) {
            if (this.classList.contains("increment")) {
              setIncrementTargetVisuals(
                "potential-profit-value",
                "set-buy-value"
              );
            } else if (this.classList.contains("decrement")) {
              setDecrementTargetVisuals(
                "potential-profit-value",
                "set-buy-value"
              );
            }
          });
        } else {
          btn.setAttribute("disabled", true);
          setVisuals();
        }
      });
  });

controlButtons.forEach((controlBtn) => {
  controlBtn.addEventListener("click", async function (e) {
    if (this.classList.contains("active-control-btn")) {
      this.classList.remove("active-control-btn");
      afterDisplay.classList.remove("active-control-display");
      tradeChartDisplay.classList.remove("control-display-is-active");
      clearAfterDisplayContent();
      return false;
    }

    controlButtons.forEach((control) => {
      control.classList.remove("active-control-btn");
      afterDisplay.classList.remove("active-control-display");
      tradeChartDisplay.classList.remove("control-display-is-active");
      clearAfterDisplayContent();
    });

    this.classList.add("active-control-btn");

    if (
      afterDisplay.classList.contains("active-control-display") &&
      tradeChartDisplay.classList.contains("control-display-is-active") &&
      this.classList.contains("active-control-btn")
    ) {
      afterDisplay.classList.remove("active-control-display");
      tradeChartDisplay.classList.remove("control-display-is-active");
      this.classList.remove("active-control-btn");
      clearAfterDisplayContent();
      return false;
    } else {
      if (
        !afterDisplay.classList.contains("active-control-display") &&
        !tradeChartDisplay.classList.contains("control-display-is-active")
      ) {
        await setAfterDisplayContent(this);
        afterDisplay.classList.add("active-control-display");
        tradeChartDisplay.classList.add("control-display-is-active");
      }
    }
  });
});

tradeFom.addEventListener("submit", function (e) {
  e.preventDefault();
});

btnGroup.querySelectorAll("button").forEach((button) => {
  button.addEventListener("click", function (e) {
    btnGroup.querySelectorAll("button").forEach((button) => {
      button.classList.remove("active");
    });
    if (this.classList.contains("active")) {
      this.classList.remove("active");
    } else {
      this.classList.add("active");
      setTimeout(() => {
        if (!tradeBtnGroup.classList.contains("active")) {
          tradeBtnGroup.classList.add("active");
        }
      }, 100);
    }
  });
});

accordionBtn.addEventListener("click", function () {
  accordionContent.classList.toggle("active");
});

closeDisplayBtn.addEventListener("click", function () {
  controlButtons.forEach((control) => {
    control.classList.remove("active-control-btn");
    afterDisplay.classList.remove("active-control-display");
    tradeChartDisplay.classList.remove("control-display-is-active");
    clearAfterDisplayContent();
  });
});

async function setAfterDisplayContent(element) {
  const elementTitle = element.getAttribute("data-nav-control-title");
  const elementIcon = element.getAttribute("data-nav-control-icon");

  const titleEl = afterDisplay.querySelector(".title");

  titleEl.innerHTML = `${elementIcon} ${elementTitle}`;

  if (elementTitle.toLowerCase() == "cryptos") {
    setForCryptos();
    type = "crypto";
    return false;
  } else if (elementTitle.toLowerCase() == "currencies") {
    setForCurrencies();
    type = "currency";
  } else if (elementTitle.toLowerCase() == "stocks") {
    setForStocks();
    type = "stock";
    return false;
  } else if (elementTitle.toLowerCase() == "commodities") {
    setForCommodities();
    type = "commodity";
    return false;
  }
}

async function clearAfterDisplayContent() {
  const titleEl = afterDisplay.querySelector(".title");

  titleEl.innerHTML = "";

  const assetContent = afterDisplay.querySelector(".asset-content");

  assetContent.innerHTML = "";
}

async function setForCryptos() {
  const assetContent = afterDisplay.querySelector(".asset-content");

  cryptos.forEach((crypto) => {
    let rate = parseFloat(crypto.rate);
    rate = rate.toFixed(2);

    assetContent.innerHTML += `
          <div class="asset-pair-item" data-asset-symbol=${crypto.symbol} onclick="assetClickTrigger(this)" data-asset-type="crypto">

              <div class="asset-pair-info">
                  <div class="img-pair"><img src="/${url}/assets/global/icons/${crypto.symbol}.png" alt="" /></div>
                  <div class="img-pair"><img src="/${url}/assets/global/icons/USD.png" alt="" /></div>
                  <div class="pair-name">${crypto.symbol}USD</div>
              </div>

              <div class="asset-pair-rate">
                  <div class="item-status">open</div>
                  <div class="item-rate">${rate}</div>
              </div>

              <div class="asset-fav">
                  <i class="las la-star"></i>
              </div>
          </div>
          `;
  });
}

async function setForCurrencies() {
  const assetContent = afterDisplay.querySelector(".asset-content");

  let currencies = [];

  for (const index in forexs) {
    currencies.push(Object.values(forexs[index]));
  }
  for (const currency of currencies) {
    if (currency[4] === "USD") {
      continue;
    }

    console.log();

    try {
      const rate = currencyRates[currencies.indexOf(currency)];

      assetContent.innerHTML += `
          <div class="asset-pair-item ${
            currency[4] == "AUD" ? "active" : ""
          }" data-asset-symbol=${
        currency[4]
      } onclick="assetClickTrigger(this)" data-asset-type="currency">
            <div class="asset-pair-info">
                <div class="img-pair"><img src="/${url}/assets/global/icons/${
        currency[4]
      }.png" alt="" /></div>
                <div class="img-pair"><img src="/${url}/assets/global/icons/USD.png" alt="" /></div>
                <div class="pair-name">${currency[4]}USD</div>
            </div>
            <div class="asset-pair-rate">
                <div class="item-status">open</div>
                <div class="item-rate">${rate}</div>
            </div>
            <div class="asset-fav">
                <i class="las la-star"></i>
            </div>
          </div>
        `;
    } catch (error) {
      console.error(error);
    }
  }
}

async function setForStocks() {
  const assetContent = afterDisplay.querySelector(".asset-content");

  for (const stock of stocks) {
    let rate = stockRates[stocks.indexOf(stock)];

    assetContent.innerHTML += `
            <div class="asset-pair-item" data-asset-symbol=${stock.symbol} onclick="assetClickTrigger(this)" data-asset-type="stock">

                <div class="asset-pair-info">
                    <div class="img-pair"><img src="/${url}/assets/global/icons/${stock.symbol}.png" alt="" /></div>
                    <div class="pair-name">${stock.symbol}</div>
                </div>

                <div class="asset-pair-rate">
                    <div class="item-status">open</div>
                    <div class="item-rate">${rate}</div>
                </div>

                <div class="asset-fav">
                    <i class="las la-star"></i>
                </div>
            </div>
            `;
  }
}

async function setForCommodities() {
  const assetContent = afterDisplay.querySelector(".asset-content");

  commodities.forEach((commodity) => {
    let rate = commodityRates[commodities.indexOf(commodity)];
    assetContent.innerHTML += `
          <div class="asset-pair-item" data-asset-symbol=${commodity.symbol} onclick="assetClickTrigger(this)" data-asset-type="commodity">

              <div class="asset-pair-info">
                  <div class="img-pair"><img src="/${url}/assets/global/icons/stock.png" alt="" /></div>
                  <div class="pair-name">${commodity.symbol}USD</div>
              </div>

              <div class="asset-pair-rate">
                  <div class="item-status">open</div>
                  <div class="item-rate">${rate}</div>
              </div>

              <div class="asset-fav">
                  <i class="las la-star"></i>
              </div>
          </div>
          `;
  });
}

async function assetClickTrigger(element) {
  currency_type = element.getAttribute("data-asset-type");

  const symbol = element.getAttribute("data-asset-symbol");

  coin_symbol = symbol;

  if (currency_type == "currency") {
    coin_rate = await getCurrencyRate(symbol);
  } else if (currency_type == "crypto") {
    coin_rate = cryptoRates[symbol];
  } else if (currency_type == "stock") {
    coin_rate = await getStockRate(symbol);
  } else if (currency_type == "commodity") {
    coin_rate = await getCommodityRate(symbol);
  }

  setVisuals();

  document.querySelectorAll(".asset-pair-item").forEach((item) => {
    item.classList.remove("active");
  });

  element.classList.add("active");

  let returnSymbol;

  if (currency_type == "currency" || currency_type == "crypto") {
    returnSymbol = `${symbol}USD`;
  } else if (currency_type == "stock") {
    returnSymbol = `${symbol}`;
  } else if (currency_type == "commodity") {
    if (symbol == "GF") {
      returnSymbol = `CME:GF1!`;
    } else if (symbol == "GDP") {
      returnSymbol = `ACTIVTRADES:DIESELK2024`;
    } else if (symbol == "NG") {
      returnSymbol = `CAPITALCOM:NATURALGAS`;
    } else {
      returnSymbol = `CAPITALCOM:${symbol}`;
    }
  } else {
    returnSymbol = `${symbol}`;
  }

  const pairname = currency_type !== "currency" ? `${symbol}` : `${symbol}USD`;

  tradeFormDisplay.querySelector(".pair-name").textContent = `${pairname}`;
  if (currency_type !== "currency") {
    tradeFormDisplay
      .querySelector(".usdSymbolImg")
      .setAttribute("hidden", true);
    tradeFormDisplay.querySelector(".SymbolImg").innerHTML =
      currency_type == "commodity"
        ? `<img src="/${url}/assets/global/icons/stock.png" alt="" />`
        : `
    <img src="/${url}/assets/global/icons/${symbol}.png" alt="" />
    `;
  } else {
    tradeFormDisplay.querySelector(".usdSymbolImg").removeAttribute("hidden");
    tradeFormDisplay.querySelector(".SymbolImg").innerHTML = `
    <img src="/${url}/assets/global/icons/${symbol}.png" alt="" />
    `;
  }

  new TradingView.widget({
    width: "100%",
    height: 525,
    symbol: `${returnSymbol}`,
    interval: "1",
    timezone: "Etc/UTC",
    theme: "dark",
    backgroundColor: "rgba(9, 22, 25, 1)",
    style: "1",
    locale: "en",
    enable_publishing: false,
    hide_side_toolbar: false,
    hide_top_toolbar: false,
    details: false,
    container_id: "tradingview-container",
  });
}

async function getCurrencyRate(symbol) {
  const myHeaders = new Headers();
  myHeaders.append("Accept", "application/json");

  const requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  try {
    const response = await fetch(
      `https://api.fastforex.io/convert?from=${symbol}&to=USD&amount=1&api_key=${fastforex_api_key}`,
      requestOptions
    );
    const result = await response.json();
    return parseFloat(result.result.rate).toFixed(4);
  } catch (error) {
    console.error(error);
  }
}

async function setCurrencyRates() {
  let currencies = [];

  for (const index in forexs) {
    currencies.push(Object.values(forexs[index]));
  }
  for (const currency of currencies) {
    const rate = await getCurrencyRate(currency[4]);

    currencyRates.push(rate);
  }

  coin_rate = currencyRates[1];

  setVisuals();
}

async function getStockRate(symbol) {
  const myHeaders = new Headers();
  myHeaders.append("Cookie", "ctoken=ef97832feebc4885851723444a94419e");

  const requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  const response = await fetch(
    `https://api.iex.cloud/v1/data/CORE/QUOTE/${symbol}?token=${iexcloud_api_key}`,
    requestOptions
  );
  const result = await response.json();
  return result[0]["latestPrice"];
}

async function setStockRates() {
  for (const stock of stocks) {
    let rate = await getStockRate(stock.symbol);

    stockRates.push(rate);
  }
}

async function getCommodityRate(symbol) {
  const myHeaders = new Headers();
  myHeaders.append("Cookie", "ctoken=ef97832feebc4885851723444a94419e");

  const requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  const response = await fetch(
    `https://api.iex.cloud/v1/data/CORE/QUOTE/${symbol}?token=${iexcloud_api_key}`,
    requestOptions
  );
  const result = await response.json();
  return result[0]["latestPrice"];
}

async function setCommodityRates() {
  for (const commodity of commodities) {
    let rate = await getCommodityRate(commodity.symbol);

    commodityRates.push(rate);
  }
}

function setVisuals() {
  if (coin_rate < 10) {
    stop_loss = parseFloat(coin_rate) - 0.0009;

    take_profit = parseFloat(coin_rate) + 0.0009;

    document.querySelector(".set-sell-value").textContent =
      parseFloat(stop_loss).toFixed(4);
    accordionContent.querySelector(".potential-loss-value").textContent =
      parseFloat(stop_loss).toFixed(4);
    document.querySelector(".set-buy-value").textContent =
      parseFloat(take_profit).toFixed(4);
    accordionContent.querySelector(".potential-profit-value").textContent =
      parseFloat(take_profit).toFixed(4);

    accordionContent.querySelector(".potential-open-rate-value").textContent =
      parseFloat(coin_rate).toFixed(4);
  } else {
    stop_loss = parseFloat(coin_rate) - 9.09;

    take_profit = parseFloat(coin_rate) + 9.09;

    document.querySelector(".set-sell-value").textContent =
      parseFloat(stop_loss).toFixed(2);
    accordionContent.querySelector(".potential-loss-value").textContent =
      parseFloat(stop_loss).toFixed(2);

    document.querySelector(".set-buy-value").textContent =
      parseFloat(take_profit).toFixed(2);

    accordionContent.querySelector(".potential-profit-value").textContent =
      parseFloat(take_profit).toFixed(2);

    accordionContent.querySelector(".potential-open-rate-value").textContent =
      parseFloat(coin_rate).toFixed(2);
  }
}

function setPotentialIncrementVisuals(rate) {
  if (rate < 10) {
    rate += 0.0001;
    stop_loss = parseFloat(rate) - 0.0009;

    accordionContent.querySelector(".potential-open-rate-value").textContent =
      parseFloat(rate).toFixed(4);

    take_profit = parseFloat(rate) + 0.0009;

    document.querySelector(".set-sell-value").textContent =
      parseFloat(stop_loss).toFixed(4);
    accordionContent.querySelector(".potential-loss-value").textContent =
      parseFloat(stop_loss).toFixed(4);
    document.querySelector(".set-buy-value").textContent =
      parseFloat(take_profit).toFixed(4);
    accordionContent.querySelector(".potential-profit-value").textContent =
      parseFloat(take_profit).toFixed(4);
  } else {
    rate += 0.01;
    stop_loss = parseFloat(rate) - 9.09;

    accordionContent.querySelector(".potential-open-rate-value").textContent =
      parseFloat(rate).toFixed(2);

    take_profit = parseFloat(rate) + 9.09;

    document.querySelector(".set-sell-value").textContent =
      parseFloat(stop_loss).toFixed(2);
    accordionContent.querySelector(".potential-loss-value").textContent =
      parseFloat(stop_loss).toFixed(2);

    document.querySelector(".set-buy-value").textContent =
      parseFloat(take_profit).toFixed(2);

    accordionContent.querySelector(".potential-profit-value").textContent =
      parseFloat(take_profit).toFixed(2);
  }
}

function setIncrementTargetVisuals(rep, to_change) {
  let context = parseFloat(document.querySelector(`.${rep}`).textContent);

  if (context < 10) {
    context += 0.0001;
  } else {
    context += 0.01;
  }

  if (to_change == "set-sell-value") {
    if (context >= coin_rate) {
      notify("error", "stop loss cannot be greater or equal to market rate");
      return false;
    }

    stop_loss = context;
    document.querySelector(`.${to_change}`).textContent =
      context > 10 ? context.toFixed(2) : context.toFixed(4);
  } else if (to_change == "set-buy-value") {
    if (context <= coin_rate) {
      notify("error", "take profit cannot be lesser or equal to market rate");
      return false;
    }
    take_profit = context;
    document.querySelector(`.${to_change}`).textContent =
      context > 10 ? context.toFixed(2) : context.toFixed(4);
  }

  document.querySelector(`.${rep}`).textContent =
    context > 10 ? context.toFixed(2) : context.toFixed(4);
}

function setDecrementTargetVisuals(rep, to_change) {
  let context = parseFloat(document.querySelector(`.${rep}`).textContent);

  if (context < 10) {
    context -= 0.0001;
  } else {
    context += 0.01;
  }

  if (to_change == "set-sell-value") {
    if (context >= coin_rate) {
      notify("error", "stop loss cannot be greater or equal to market rate");
      return false;
    }
    stop_loss = context;
    document.querySelector(`.${to_change}`).textContent =
      context > 10 ? context.toFixed(2) : context.toFixed(4);
  } else if (to_change == "set-buy-value") {
    if (context <= coin_rate) {
      notify("error", "take profit cannot be lesser or equal to market rate");
      return false;
    }
    take_profit = context;
    document.querySelector(`.${to_change}`).textContent =
      context > 10 ? context.toFixed(2) : context.toFixed(4);
  }

  document.querySelector(`.${rep}`).textContent =
    context > 10 ? context.toFixed(2) : context.toFixed(4);
}

function setPotentialDecrementVisuals(rate) {
  if (rate < 10) {
    rate -= 0.0001;
    stop_loss = parseFloat(rate) - 0.0009;

    accordionContent.querySelector(".potential-open-rate-value").textContent =
      parseFloat(rate).toFixed(4);

    take_profit = parseFloat(rate) + 0.0009;

    document.querySelector(".set-sell-value").textContent =
      parseFloat(stop_loss).toFixed(4);
    accordionContent.querySelector(".potential-loss-value").textContent =
      parseFloat(stop_loss).toFixed(4);
    document.querySelector(".set-buy-value").textContent =
      parseFloat(take_profit).toFixed(4);
    accordionContent.querySelector(".potential-profit-value").textContent =
      parseFloat(take_profit).toFixed(4);
  } else {
    rate -= 0.01;
    stop_loss = parseFloat(rate) - 9.09;

    accordionContent.querySelector(".potential-open-rate-value").textContent =
      parseFloat(rate).toFixed(2);

    take_profit = parseFloat(rate) + 9.09;

    document.querySelector(".set-sell-value").textContent =
      parseFloat(stop_loss).toFixed(2);
    accordionContent.querySelector(".potential-loss-value").textContent =
      parseFloat(stop_loss).toFixed(2);

    document.querySelector(".set-buy-value").textContent =
      parseFloat(take_profit).toFixed(2);

    accordionContent.querySelector(".potential-profit-value").textContent =
      parseFloat(take_profit).toFixed(2);
  }
}

function extractAndSaveCryptoBalance() {
  cryptos.forEach((crypto) => {
    let sym = crypto.symbol;
    cryptoRates[sym] = crypto.rate;
  });
}

extractAndSaveCryptoBalance();
setCurrencyRates();
setStockRates();
setCommodityRates();

$(".trade-btn").click(function (e) {
  const setOpenAt = open_at_rate_is_checked == true ? open_rate : null;
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": token,
    },
    url: `wave/store`,
    method: "POST",
    data: {
      stop_loss: stop_loss,
      take_profit: take_profit,
      rate: coin_rate,
      lotsize: lotsize,
      symbol: coin_symbol,
      type: currency_type,
      open_at: setOpenAt,
    },
    success: function (response, status) {
      if (response.success) {
        notify("success", response.success);

        setTimeout(() => {
          window.location.reload();
        }, 1200);
        return 0;
      } else if (response.error) {
        notify("error", response.error);
        return 0;
      }
    },
  });
});
