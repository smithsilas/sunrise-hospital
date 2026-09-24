// main.js - small shared behaviours used on every page

document.addEventListener("DOMContentLoaded", function () {
  // Toggle the mobile navigation menu open/closed
  var menuToggle = document.getElementById("menuToggle");
  var navMenu = document.getElementById("navMenu");

  if (menuToggle && navMenu) {
    menuToggle.addEventListener("click", function () {
      navMenu.classList.toggle("open");
    });
  }

  // If the PHP script redirected back here with ?success=1,
  // show the "your message was sent" box (used on contact page too).
  var params = new URLSearchParams(window.location.search);
  var successBox = document.getElementById("successBox");
  if (successBox && params.get("success") === "1") {
    successBox.style.display = "block";
  }
});
