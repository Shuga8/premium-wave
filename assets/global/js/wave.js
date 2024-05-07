/** HTML ELEMENTS */
const controlButtons = document.querySelectorAll(".control-tab");
const afterDisplay = document.querySelector(".control-after-display");
const tradeChartDisplay = document.querySelector(".trading-chart-display");
const tradeFormDisplay = document.querySelector(".trading-form-display");
const tradeFom = document.querySelector(".trade-form");
const tradeBtnGroup = document.querySelector(".trade-button-group");
const accordionBtn = document.querySelector(".accordion-btn");
const accordionContent = document.querySelector(".accordion-content");
const closeDisplayBtn = document.querySelector(".close-display-btn");

let currencyRates = [];
let stockRates = [];

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

  const btnGroup = document.querySelector(".button-group");

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
    return false;
  } else if (elementTitle.toLowerCase() == "currencies") {
    setForCurrencies();
  } else if (elementTitle.toLowerCase() == "stocks") {
    setForStocks();
    return false;
  } else if (elementTitle.toLowerCase() == "commodities") {
    setForCommodities();
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
          <div class="asset-pair-item" data-asset-symbol=${crypto.symbol} onclick="assetClickTrigger(this)" data-asset-type="">

              <div class="asset-pair-info">
                  <div class="img-pair"></div>
                  <div class="img-pair"><img src="/premium-wave/assets/global/icons/USD.png" alt=""></div>
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
          <div class="asset-pair-item" data-asset-symbol=${currency[4]} onclick="assetClickTrigger(this)" data-asset-type="hhxjx">
            <div class="asset-pair-info">
                <div class="img-pair"></div>
                <div class="img-pair"></div>
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
            <div class="asset-pair-item" data-asset-symbol=${stock.symbol} onclick="assetClickTrigger(this)" data-asset-type="">

                <div class="asset-pair-info">
                    <div class="img-pair"></div>
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
    assetContent.innerHTML += `
          <div class="asset-pair-item" data-asset-symbol=${commodity.symbol} onclick="assetClickTrigger(this)" data-asset-type="">

              <div class="asset-pair-info">
                  <div class="img-pair"></div>
                  <div class="pair-name">${commodity.symbol}USD</div>
              </div>

              <div class="asset-pair-rate">
                  <div class="item-status">open</div>
                  <div class="item-rate">0.001</div>
              </div>

              <div class="asset-fav">
                  <i class="las la-star"></i>
              </div>
          </div>
          `;
  });
}

function assetClickTrigger(element) {
  const symbol = element.getAttribute("data-asset-symbol");

  const returnSymbol =
    element.getAttribute("data-asset-type") === ""
      ? `${symbol}`
      : `${symbol}USD`;

  tradeFormDisplay.querySelector(".pair-name").textContent = `${returnSymbol}`;
  if (element.getAttribute("data-asset-type") === "") {
    tradeFormDisplay
      .querySelector(".usdSymbolImg")
      .setAttribute("hidden", true);
  } else {
    tradeFormDisplay.querySelector(".usdSymbolImg").removeAttribute("hidden");
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
    return result.result.rate;
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
setCurrencyRates();
setStockRates();
