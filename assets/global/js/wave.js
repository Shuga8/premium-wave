/** HTML ELEMENTS */
const url = "premium-wave";

function isCurrentTimeInRange() {
  const startTime = new Date();
  startTime.setHours(8, 0, 0, 0);

  const endTime = new Date();
  endTime.setHours(16, 30, 0, 0);
  const currentTime = new Date();
  return currentTime >= startTime && currentTime <= endTime;
}
const weekEndDisplay = document.querySelector(".weekend-closed-trade-info");
const currentDay = new Date().getDay();

let market_type = "open";

if (currentDay === 0 || currentDay === 6) {
  market_type = "closed";
  weekEndDisplay.style.display = "block";
}

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
const coinmarketcap_api_key = "2f8bc8a6-f18a-4d12-8144-f7a114acef76";
const iexcloud_api_key = "sk_4326a4d3e83449238d614b2d5d224b7d";
const fastforex_api_key = "1524f42cf8-1872de1e22-sfu1gi";
const alpha_api_key = "UJ1DWALYT16ZVVUS";
const fmp_api_key = "cARpiP1yH7faNhSWqnQLyGNV0mc7oTxl";

let coin_rate = null;
let stop_loss = null;
let take_profit = null;
let lotsize = 0.1;
let currency_type = "currency";
let coin_symbol = "AUD";
let open_at_rate_is_checked = false;
let open_rate = null;
let trade_type = null;

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
    const randomTradeType = ["sell", "buy"];

    const confirmBot = confirm(
      "Are you sure you want to set an automatic trade?"
    );

    if (confirmBot == true) {
      currency_type = randomFiat[Math.floor(Math.random() * randomFiat.length)];

      if (market_type === "closed" && currency_type !== "crypto") {
        notify("error", "Market is Closed");
        return false;
      } else if (
        !isCurrentTimeInRange() &&
        (currency_type === "stock" || currency_type === "commodity")
      ) {
        notify("error", "Market is closed from 8:00am to 4:30pm ");
        return false;
      }

      if (curr)
        trade_type =
          randomTradeType[Math.floor(Math.random() * randomTradeType.length)];
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
    if (this.getAttribute("data-nav-control-title") == "Blog") {
      location.href = "https://premiumwave.ca/blog/";
      return false;
    }
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

    if (this.classList.contains("set-sell")) {
      trade_type = "sell";
    } else if (this.classList.contains("set-buy")) {
      trade_type = "buy";
    }

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
    let rate = cryptoRates[crypto.symbol];

    assetContent.innerHTML += `
          <div class="asset-pair-item" data-asset-symbol=${
            crypto.symbol
          } onclick="assetClickTrigger(this)" data-asset-type="crypto">

              <div class="asset-pair-info">
                  <div class="img-pair"><img src="/${url}/assets/global/icons/${
      crypto.symbol
    }.png" alt="" /></div>
                  <div class="img-pair"><img src="/${url}/assets/global/icons/USD.png" alt="" /></div>
                  <div class="pair-name">${crypto.symbol}USD</div>
              </div>

              <div class="asset-pair-rate">
                  <div class="item-status">open</div>
                  <div class="item-rate">${rate ? rate : "loading..."}</div>
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
                <div class="item-status">${market_type}</div>
                <div class="item-rate">${rate ? rate : "loading..."}</div>
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
            <div class="asset-pair-item" data-asset-symbol=${
              stock.symbol
            } onclick="assetClickTrigger(this)" data-asset-type="stock">

                <div class="asset-pair-info">
                    <div class="img-pair"><img src="/${url}/assets/global/icons/${
      stock.symbol ?? "stock"
    }.png" alt="" /></div>
                    <div class="pair-name">${stock.symbol}</div>
                </div>

                <div class="asset-pair-rate">
                    <div class="item-status">${market_type}</div>
                    <div class="item-rate">${rate ? rate : "loading..."}</div>
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
          <div class="asset-pair-item" data-asset-symbol=${
            commodity.symbol
          } onclick="assetClickTrigger(this)" data-asset-type="commodity">

              <div class="asset-pair-info">
                  <div class="img-pair"><img src="/${url}/assets/global/icons/stock.png" alt="" /></div>
                  <div class="pair-name">${commodity.symbol}USD</div>
              </div>

              <div class="asset-pair-rate">
                  <div class="item-status">${market_type}</div>
                  <div class="item-rate">${rate ? rate : "loading..."}</div>
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

  if (
    (currency_type !== "crypto" && market_type === "closed") ||
    (!isCurrentTimeInRange() &&
      (currency_type === "stock" || currency_type === "commodity"))
  ) {
    weekEndDisplay.style.display = "block";
  } else {
    weekEndDisplay.style.display = "none";
  }

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

  changeTechnicalAnalysis(returnSymbol);

  new TradingView.widget({
    width: "100%",
    height: 580,
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

function changeTechnicalAnalysis(symbol) {
  const widgetContainer = document.querySelector(
    ".tradingview-widget-container"
  );

  // Clear the existing widget content
  widgetContainer.innerHTML = `
      <div class="tradingview-widget-container__widget"></div>
      <div class="tradingview-widget-copyright">
          <a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank">
              <span class="blue-text">Track all markets on TradingView</span>
          </a>
      </div>
  `;

  // Create a new script element
  const script = document.createElement("script");
  script.type = "text/javascript";
  script.src =
    "https://s3.tradingview.com/external-embedding/embed-widget-technical-analysis.js";
  script.async = true;

  // Set the script content
  script.innerHTML = JSON.stringify({
    interval: "1m",
    width: "100%",
    isTransparent: true,
    height: "250",
    symbol: symbol,
    showIntervalTabs: true,
    displayMode: "single",
    locale: "en",
    colorTheme: "dark",
  });

  // Append the script to the widget container
  widgetContainer.appendChild(script);

  console.log(widgetContainer);
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
  const requestOptions = {
    method: "GET",
    redirect: "follow",
  };

  const response = await fetch(
    `https://financialmodelingprep.com/api/v3/profile/${symbol}?apikey=${fmp_api_key}`,
    requestOptions
  );
  const result = await response.json();

  return result[0].price;
}

async function setStockRates() {
  for (const stock of stocks) {
    let rate = await getStockRate(stock.symbol);

    stockRates.push(rate);
  }
}

// async function getCryptoRates(symbol) {
//   const myHeaders = new Headers();
//   myHeaders.append("Accept", "application/json");

//   const requestOptions = {
//     method: "GET",
//     headers: myHeaders,
//     redirect: "follow",
//   };

//   try {
//     const response = await fetch(
//       `https://api.fastforex.io/convert?from=${symbol}&to=USD&amount=1&api_key=${fastforex_api_key}`,
//       requestOptions
//     );
//     const result = await response.json();

//     if (!response.ok) {
//       throw new Error(result.message || "Failed to fetch the rate");
//     }

//     return parseFloat(result.result.rate).toFixed(4);
//   } catch (error) {
//     console.error(error);
//     return null; // Or handle it in a way that suits your application
//   }
// }

// async function extractAndSaveCryptoBalance() {
//   for (const crypto of cryptos) {
//     let sym = crypto.symbol;
//     let rate = await getCryptoRates(sym);

//     if (rate !== null) {
//       cryptoRates[sym] = rate;
//     } else {
//       console.warn(`Skipping ${sym} due to fetching error`);
//     }
//   }
// }

async function getCryptoRates(symbol) {
  const url = `https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=${symbol}&convert=USD`;

  const myHeaders = new Headers();
  myHeaders.append("X-CMC_PRO_API_KEY", coinmarketcap_api_key);
  myHeaders.append("Accept", "application/json");

  const requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  try {
    const response = await fetch(url, requestOptions);
    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || "Failed to fetch the rate");
    }

    const rate = result.data[symbol].quote.USD.price;
    return parseFloat(rate).toFixed(4);
  } catch (error) {
    console.error(`Error fetching rate for ${symbol}:`, error);
    return null; // Or handle it in a way that suits your application
  }
}

function delay(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function extractAndSaveCryptoBalance() {
  for (const crypto of cryptos) {
    let sym = crypto.symbol;
    let rate = await getCryptoRates(sym);

    if (rate !== null) {
      cryptoRates[sym] = rate;
    } else {
      console.warn(`Skipping ${sym} due to fetching error`);
    }

    // Add a delay to prevent API rate limiting
    await delay(1000); // Adjust the delay as needed (e.g., 1000ms = 1 second)
  }
}

async function getCommodityRate(symbol) {
  const requestOptions = {
    method: "GET",
    redirect: "follow",
  };

  const response = await fetch(
    `https://financialmodelingprep.com/api/v3/quote/${symbol}?apikey=${fmp_api_key}`,
    requestOptions
  );
  const result = await response.json();

  return result[0].price;
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
    context -= 0.01;
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

extractAndSaveCryptoBalance();
setCurrencyRates();
setStockRates();
setCommodityRates();

$(".trade-btn").click(function (e) {
  if (market_type === "closed" && currency_type !== "crypto") {
    notify("error", "Market is Closed");
    return false;
  } else if (
    !isCurrentTimeInRange() &&
    (currency_type === "stock" || currency_type === "commodity")
  ) {
    notify("error", "Market is closed from 8:00am to 4:30pm ");
    return false;
  }

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
      trade_type: trade_type,
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
