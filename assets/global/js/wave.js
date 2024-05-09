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

let coin_rate = null;
let stop_loss = null;
let take_profit = null;
let lotsize = 0.1;
let currency_type = "currency";

let cryptoRates = {};
let currencyRates = [];
let stockRates = [];
let commodityRates = [];

document.querySelector("#lot").addEventListener("change", function (e) {
  let value = parseFloat(this.value) * 10;

  lotsize = this.value;

  tradeFom.querySelector(".pips-value").textContent = `$${value}`;

  let returnval = parseFloat(value * 53.87).toFixed(2);

  tradeFom.querySelector(".required-margin").textContent = `$${returnval}`;
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

    console.log(coin_rate);
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
    hide_side_toolbar: true,
    hide_top_toolbar: true,
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
      `https://api.fastforex.io/convert?from=${symbol}&to=USD&amount=1&api_key=4686c1d244-257beffcf5-sd2qy0`,
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
    `https://api.iex.cloud/v1/data/CORE/QUOTE/${symbol}?token=pk_2ee9841565e7480c933da27c494aa466`,
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
    `https://api.iex.cloud/v1/data/CORE/QUOTE/${symbol}?token=pk_2ee9841565e7480c933da27c494aa466`,
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
    document.querySelector(".set-buy-value").textContent =
      parseFloat(take_profit).toFixed(4);
  } else {
    stop_loss = parseFloat(coin_rate) - 9.09;

    take_profit = parseFloat(coin_rate) + 9.09;

    document.querySelector(".set-sell-value").textContent =
      parseFloat(stop_loss).toFixed(2);
    document.querySelector(".set-buy-value").textContent =
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
