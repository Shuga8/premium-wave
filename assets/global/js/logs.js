async function setOpenTrades() {
  const openTrades = document.querySelector(".open-trades-table");
  const tbody = openTrades.querySelector("tbody");
  await $.ajax({
    headers: {
      "X-CSRF-TOKEN": token,
    },
    url: `wave/open-trades`,
    method: "GET",
    success: function (response, status) {
      let data = [...response];

      tbody.innerHTML = ``;

      if (data.length > 0) {
        data.forEach((trade) => {
          let symbol = null;
          if (trade.isCrypto) {
            symbol = trade.crypto;
          } else if (trade.isStock) {
            symbol = trade.stock;
          } else if (trade.isCommodity) {
            symbol = trade.commodity;
          } else if (trade.isForex) {
            symbol = trade.currency;
          }
          let tr = `<tr>
                <td>
                    #${trade.order_id}
                    <br/>
                    ${trade.open_price}
                    
                </td>
                
                <td>
                    ${trade.created_at}
                    <br>
                    ${trade.price_is != null ? trade.price_is : 0}
                    <br>
                    ${trade.open_amount}
                </td>

                <td>
                    ${symbol}
                    <br>

                    ${trade.stop_loss}
                </td>

                <td>
                    ${trade.wallet}
                    <br/>
                    ${trade.take_profit}
                </td>

                <td>
                <a class="bg-success px-4 py-2 text-white" href="wave/end-running-trade/${
                  trade.id
                }">End</a>
                </td>
        </tr>
             `;

          tbody.innerHTML += tr;
        });
      } else {
        let tr = `
            <tr>
                <td colspan="5" class="text-danger">No Open Trade Is Running!</td>
            </tr>
        `;

        tbody.innerHTML = tr;
      }
    },
  });
}

async function setPendingTrades() {
  const pendingTrades = document.querySelector(".pending-trades-table");
  const tbody = pendingTrades.querySelector("tbody");

  await $.ajax({
    headers: {
      "X-CSRF-TOKEN": token,
    },
    url: `wave/pending-trades`,
    method: "GET",
    success: function (response, status) {
      let data = [...response];

      tbody.innerHTML = ``;

      if (data.length > 0) {
        data.forEach((trade) => {
          let symbol = null;
          if (trade.isCrypto) {
            symbol = trade.crypto;
          } else if (trade.isStock) {
            symbol = trade.stock;
          } else if (trade.isCommodity) {
            symbol = trade.commodity;
          } else if (trade.isForex) {
            symbol = trade.currency;
          }
          let tr = `<tr>
                <td>
                    #${trade.order_id}
                    <br/>
                    ${trade.open_price}
                    <br>
                    ${trade.open_at}
                </td>
                
                <td>
                    ${trade.created_at}
                    <br>
                    ${trade.price_is != null ? trade.price_is : 0}
                    <br>
                    ${trade.open_amount}
                </td>

                <td>
                    ${symbol}
                    <br>
                    ${trade.stop_loss}
                </td>

                <td>
                    ${trade.wallet}
                    <br/>
                    ${trade.take_profit}
                </td>

                <td>
                    <a class="bg-danger px-4 py-2 text-white" href="wave/delete-pending-trade/${
                      trade.id
                    }"><i class="las la-trash"></i></a> 
                </td>
        </tr>
             `;

          tbody.innerHTML += tr;
        });
      } else {
        let tr = `
            <tr>
                <td colspan="5" class="text-danger">No Trade Is Pending!</td>
            </tr>
        `;

        tbody.innerHTML = tr;
      }
    },
  });
}

async function setTradeHistory() {
  const pendingTrades = document.querySelector(".trade-history-table");
  const tbody = pendingTrades.querySelector("tbody");

  await $.ajax({
    headers: {
      "X-CSRF-TOKEN": token,
    },
    url: `wave/trades-history`,
    method: "GET",
    success: function (response, status) {
      let data = [...response];

      tbody.innerHTML = ``;

      if (data.length > 0) {
        data.forEach((trade) => {
          let symbol = null;
          if (trade.isCrypto) {
            symbol = trade.crypto;
          } else if (trade.isStock) {
            symbol = trade.stock;
          } else if (trade.isCommodity) {
            symbol = trade.commodity;
          } else if (trade.isForex) {
            symbol = trade.currency;
          }

          let profitLoss =
            trade.price_is >= trade.take_profit && trade.price_is != null
              ? `+${Math.abs(trade.open_amount - trade.amount)}`
              : `-${Math.abs(trade.open_amount - trade.amount)}`;
          let tr = `<tr>
                  <td>
                      #${trade.order_id}
                      <br/>
                      ${trade.open_price}
                  </td>
                  
                  <td>
                      ${trade.created_at}
                      <br>
                      ${trade.take_profit}
                      <br>
                      ${profitLoss}
                  </td>
  
                  <td>
                     ${trade.updated_at}
                      <br>
                      ${trade.stop_loss}
                  </td>
  
                  <td>
                    ${symbol}
                      <br/>
                      ${trade.price_is != null ? trade.price_is : 0}
                  </td>
  

          </tr>
               `;

          tbody.innerHTML += tr;
        });
      } else {
        let tr = `
              <tr>
                  <td colspan="5" class="text-danger">No Trade Has Been Completed !</td>
              </tr>
          `;

        tbody.innerHTML = tr;
      }
    },
  });
}

async function executeTrades() {
  await setOpenTrades();
  await setPendingTrades();
  await setTradeHistory();
  setTimeout(executeTrades, 1000);
}

executeTrades();
