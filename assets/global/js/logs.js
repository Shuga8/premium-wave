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
                    <button class="bg-primary px-4 py-2 text-white editBtn"  type="button" data-binary='${JSON.stringify(
                      trade
                    )
                      .replace(/'/g, "&apos;")
                      .replace(
                        /"/g,
                        "&quot;"
                      )}'><i class="las la-pen"></i></button> 
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

        // Bind event listener after rows are added to DOM
        document.querySelectorAll(".editBtn").forEach((button) => {
          button.addEventListener("click", setModal);
        });
      } else {
        let tr = `
            <tr>
                <td colspan="13" class="text-danger">No Trade Is Pending!</td>
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

          let profitLoss = trade.amount - trade.open_amount;

          let profitLossType;

          if (trade.amount > trade.open_amount) {
            profitLossType = "profit";
          } else if (trade.amount < trade.open_amount) {
            profitLossType = "loss";
          } else if (trade.amount === trade.open_amount) {
            profitLossType = "draw";
            // profitLoss = 0.00;  // Ensure profitLoss is a number
          }

          let tr = `<tr>
                  <td>
                    #${trade.order_id}
                  </td>

                  <td class="loss">
                      $${trade.stop_loss}
                  </td>

                  <td>
                      $${trade.open_amount}
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
  setTimeout(executeTrades, 1000);
}

$(document).ready(function () {
  executeTrades();
  document
    .querySelector(".submitEditedBtn")
    .addEventListener("click", submitEditedTrade);
});

function setModal(event) {
  let modal = $("#pendingTrade");
  let el = event.currentTarget;

  let action = `/edit-pending-trade/:id`;
  let data = JSON.parse(el.getAttribute("data-binary"));
  if (data.isCrypto == 1) {
    modal.find("input[name=type]").val("Crypto Currency");
    modal.find("input[name=symbol]").val(data.crypto);
  } else if (data.isForex == 1) {
    modal.find("input[name=type]").val("Foreign Currency");
    modal.find("input[name=symbol]").val(data.currency);
  } else if (data.isStock == 1) {
    modal.find("input[name=type]").val("Stock Ticker");
    modal.find("input[name=symbol]").val(data.stock);
  } else if (data.isCommodity == 1) {
    modal.find("input[name=type]").val("Commodity Ticker");
    modal.find("input[name=symbol]").val(data.commodity);
  }

  modal.find("form").prop("action", action.replace(":id", data.id));
  modal.find("input[name=stop_loss]").val(data.stop_loss);
  modal.find("input[name=take_profit]").val(data.take_profit);
  modal.find("input[name=trade_type]").val(data.trade_type);
  if (data.open_at_is_set == 1) {
    document.querySelector(".open_at_field").removeAttribute("hidden");
    modal.find("input[name=open_at]").val(data.open_at);
  } else {
    document.querySelector(".open_at_field").setAttribute("hidden", true);
    modal.find("input[name=open_at]").val(data.open_at);
  }

  $(modal).modal("show");
}

function submitEditedTrade(event) {
  event.preventDefault(); // Prevent the default form submission

  let modal = $("#pendingTrade");

  // Collect data from the modal inputs
  let tradeId = modal.find("form").prop("action").split("/").pop(); // Extract the ID from the form action URL
  let type = modal.find("input[name=type]").val();
  let symbol = modal.find("input[name=symbol]").val();
  let stopLoss = modal.find("input[name=stop_loss]").val();
  let takeProfit = modal.find("input[name=take_profit]").val();
  let tradeType = modal.find("input[name=trade_type]").val();
  let openAt = modal.find("input[name=open_at]").val();
  let openAtIsSet = document
    .querySelector(".open_at_field")
    .hasAttribute("hidden")
    ? 0
    : 1;

  // Prepare the data to be sent
  let updatedData = {
    id: tradeId,
    type: type,
    symbol: symbol,
    stop_loss: stopLoss,
    take_profit: takeProfit,
    trade_type: tradeType,
    open_at: openAt,
    open_at_is_set: openAtIsSet,
  };

  // Send the updated data to the server
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": token,
    },
    url: `wave/edit-pending-trade/${tradeId}`,
    method: "POST",
    data: updatedData,
    success: function (response, status) {
      // console.log(response);
      if (response.success) {
        notify("success", response.success);
      } else {
        notify("error", response.error);
      }
      $(modal).modal("hide");
      setPendingTrades(); // Refresh the pending trades table
    },
    error: function (xhr, status, error) {
      notify("error", `Error updating trade: ${error}`);
    },
  });
}

// Add the event listener for the save changes button
document
  .querySelector(".submitEditedBtn")
  .addEventListener("click", submitEditedTrade);
