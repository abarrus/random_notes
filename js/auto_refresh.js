/**
 * Refreshes every 10 seconds and gives an alert box counting down
 *
 * To use, add <script src="js/auto_refresh.js"></script> to the head
 * and put <body onload="autoRefreshAlert()"> instead of just <body>
 *
 * Also your document must have a div with "main-content" class
 */

let secondsLeft = 10;
const span = document.createElement("span");

function autoRefreshAlert(specialPlacement) {

  const mainContent = specialPlacement
    ? document.getElementById("auto-refresh-alert")
    : document.getElementsByClassName("main-content").item(0);

  const alertBox = document.createElement("div");
  alertBox.classList.add("alert", "alert-info");

  if (specialPlacement) {
    alertBox.classList.add("mb-0");
  }

  const p = document.createElement("p");
  p.classList.add("mb-0");

  span.classList.add("fw-bold");

  p.append("Waiting for updates... Reloading in ", span, " seconds.");
  alertBox.append(p);

  mainContent.insertBefore(alertBox, mainContent.firstChild);

  span.textContent = secondsLeft;
  setInterval(myTimer, 1000); // every second
}

function myTimer() {
  secondsLeft--;
  span.innerText = secondsLeft;
  if (secondsLeft == 0) {
    window.location.reload();
  }
}
