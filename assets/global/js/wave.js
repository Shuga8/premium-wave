/** HTML ELEMENTS */
const controlButtons = document.querySelectorAll(".control-tab");
const afterDisplay = document.querySelector(".control-after-display");
const tradeChartDisplay = document.querySelector(".trading-chart-display");
const tradeFom = document.querySelector(".trade-form");
const tradeBtnGroup = document.querySelector(".trade-button-group");
const accordionBtn = document.querySelector(".accordion-btn");
const accordionContent = document.querySelector(".accordion-content");
const closeDisplayBtn = document.querySelector(".close-display-btn");

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
    console.log(crypto);

    let rate = parseFloat(crypto.rate);
    rate = rate.toFixed(2);

    assetContent.innerHTML += `
          <div class="asset-pair-item">

              <div class="asset-pair-info">
                  <div class="img-pair"></div>
                  <div class="img-pair"></div>
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
  currencies.forEach((currency) => {
    if (currency[4] === "USD") {
      return 0;
    }
    assetContent.innerHTML += `
    <div class="asset-pair-item">

    <div class="asset-pair-info">
        <div class="img-pair"></div>
        <div class="img-pair"></div>
        <div class="pair-name">${currency[4]}USD</div>
    </div>

    <div class="asset-pair-rate">
        <div class="item-status">open</div>
        <div class="item-rate">${currency[6]}</div>
    </div>

    <div class="asset-fav">
        <i class="las la-star"></i>
    </div>
</div>
    `;
  });
}

async function setForStocks() {
  const assetContent = afterDisplay.querySelector(".asset-content");

  stocks.forEach((stock) => {
    assetContent.innerHTML += `
          <div class="asset-pair-item">

              <div class="asset-pair-info">
                  <div class="img-pair"></div>
                  <div class="img-pair"></div>
                  <div class="pair-name">${stock.symbol}USD</div>
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

async function setForCommodities() {
  const assetContent = afterDisplay.querySelector(".asset-content");

  commodities.forEach((commodity) => {
    assetContent.innerHTML += `
          <div class="asset-pair-item">

              <div class="asset-pair-info">
                  <div class="img-pair"></div>
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
