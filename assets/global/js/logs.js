function setOpenTrades() {
  const openTrades = document.querySelector(".open-trades-table");
  const tbody = openTrades.querySelector("tbody");
  $.ajax({
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
                    ${trade.price_is}
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
                    <a class="bg-success px-4 py-2 text-white" href="">End</a>
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

function setPendingTrades() {
  const pendingTrades = document.querySelector(".pending-trades-table");
  const tbody = pendingTrades.querySelector("tbody");
}

setInterval(() => {
  setOpenTrades();
}, 1000);
