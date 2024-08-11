import { formatDistanceToNow } from "https://cdn.jsdelivr.net/npm/date-fns@2.29.3/+esm";

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
          let symbol;
          if (trade.isCrypto == 1) {
            symbol = trade.crypto;
          } else if (trade.isStock == 1) {
            symbol = trade.stock;
          } else if (trade.isCommodity == 1) {
            symbol = trade.commodity;
          } else if (trade.isForex == 1) {
            symbol = trade.currency;
          }
          let tr = `<tr>
                <td>
                    #${trade.order_id}    
                </td>

                <td>
                $${trade.open_price}
                </td>

                <td>
                ${formatDistanceToNow(new Date(trade.created_at), {
                  addSuffix: true,
                })}
                </td>

                <td>
                    $${trade.price_is != null ? trade.price_is : "0.00000000"}
                </td>

                <td>
                    ${
                      trade.trade_type[0].toUpperCase() +
                      trade.trade_type.substring(1)
                    }
                </td>

                <td>
                $${trade.open_amount}
                </td>

                <td>
                    ${symbol}
                </td>

                <td class="loss">
                $${trade.stop_loss}
                </td>

                <td>
                    ${trade.wallet}
                </td>

                <td class="profit">
                $${trade.take_profit}
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
                <td colspan="12" class="text-danger">No Open Trade Is Running!</td>
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
          let symbol;
          if (trade.isCrypto == 1) {
            symbol = trade.crypto;
          } else if (trade.isStock == 1) {
            symbol = trade.stock;
          } else if (trade.isCommodity == 1) {
            symbol = trade.commodity;
          } else if (trade.isForex == 1) {
            symbol = trade.currency;
          }
          let tr = `<tr>
                <td>
                   #${trade.order_id}
                </td>

                <td>
                    $${trade.open_price}
                </td>

                <td>
                    $${trade.open_at}
                </td>

                <td>
                    ${formatDistanceToNow(new Date(trade.created_at), {
                      addSuffix: true,
                    })}
                </td>
                
                <td>
                    $${trade.price_is != null ? trade.price_is : "0.00000000"}
                </td>

                <td>
                    ${
                      trade.trade_type[0].toUpperCase() +
                      trade.trade_type.substring(1)
                    }
                </td>

                <td>
                    $${trade.open_amount}
                </td>

                <td class="loss">
                    $${trade.stop_loss}
                </td>

                <td>
                    ${symbol}
                </td>

                <td>
                    ${trade.wallet}
                </td>

                <td class="profit">
                    $${trade.take_profit}
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
                <td colspan="12" class="text-danger">No Trade Is Pending!</td>
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
          let symbol;
          if (trade.isCrypto == 1) {
            symbol = trade.crypto;
          } else if (trade.isStock == 1) {
            symbol = trade.stock;
          } else if (trade.isCommodity == 1) {
            symbol = trade.commodity;
          } else if (trade.isForex == 1) {
            symbol = trade.currency;
          }

          // Ensure trade.amount and trade.open_amount are numbers
          let tradeAmount = parseFloat(trade.amount);
          let openAmount = parseFloat(trade.open_amount);

          if (isNaN(tradeAmount) || isNaN(openAmount)) {
            console.error(
              `Invalid trade amount or open amount for trade ID ${trade.order_id}`
            );
            return;
          }

          let profitLoss = tradeAmount - openAmount;

          let profitLossType;

          if (tradeAmount > openAmount) {
            profitLossType = "profit";
          } else if (tradeAmount < openAmount) {
            profitLossType = "loss";
          } else {
            profitLossType = "draw";
          }

          // Debugging logs
          // console.log(
          //   `Trade ID: ${trade.order_id}, Amount: ${tradeAmount}, Open Amount: ${openAmount}, Profit/Loss: ${profitLoss}`
          // );

          let tr = `<tr>
                  <td>
                    #${trade.order_id}
                  </td>

                  <td class="loss">
                      $${trade.stop_loss}
                  </td>

                  <td>
                      $${openAmount.toFixed(2)}
                  </td>

                  <td>
                  ${formatDistanceToNow(new Date(trade.created_at), {
                    addSuffix: true,
                  })}
                  </td>

                  <td>
                    ${
                      trade.trade_type[0].toUpperCase() +
                      trade.trade_type.substring(1)
                    }
                </td>

                  <td class="profit">
                    $${trade.take_profit}
                  </td>

                  <td class="${profitLossType}">
                      ${profitLoss.toFixed(2)} USD
                  </td>

                  <td>
                  ${formatDistanceToNow(new Date(trade.updated_at), {
                    addSuffix: true,
                  })}
                  </td>
  
                  <td>
                      $${trade.open_price}
                  </td>

                  <td>
                    ${symbol}
                  </td>
  
                  <td>
                      $${trade.price_is != null ? trade.price_is : "0.00000000"}
                  </td>
  
          </tr>
               `;

          tbody.innerHTML += tr;
        });
      } else {
        let tr = `
              <tr>
                  <td colspan="12" class="text-danger">No Trade Has Been Completed!</td>
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
  setTimeout(executeTrades, 100);
}

executeTrades();
