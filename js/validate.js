// validate.js
// Task 3: Validates the appointment booking form using JavaScript and
// DOM manipulation before it is allowed to submit to the PHP back end.

document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("appointmentForm");
  if (!form) return; // this script only runs on the appointments page

  var resetBtn = document.getElementById("resetBtn");

  // Simple regex patterns used for validation
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var phonePattern = /^[0-9]{10}$/; // exactly 10 digits, e.g. 0712345678
  var idPattern = /^[0-9]{6,10}$/; // 6 to 10 digit National ID

  // Helper: show or hide a single field's error message
  function setError(inputId, show) {
    var errorEl = document.getElementById("err-" + inputId);
    var inputEl = document.getElementById(inputId);
    if (!errorEl || !inputEl) return;

    errorEl.style.display = show ? "block" : "none";
    inputEl.style.borderColor = show ? "#c0392b" : "#ccc";
  }

  // Validates every field and returns true only if the whole form is valid
  function validateForm() {
    var isValid = true;

    // Patient Name - required
    var patientName = document.getElementById("patientName").value.trim();
    if (patientName === "") {
      setError("patientName", true);
      isValid = false;
    } else {
      setError("patientName", false);
    }

    // National ID - required, 6-10 digits
    var nationalId = document.getElementById("nationalId").value.trim();
    if (!idPattern.test(nationalId)) {
      setError("nationalId", true);
      isValid = false;
    } else {
      setError("nationalId", false);
    }

    // Gender - required
    var gender = document.getElementById("gender").value;
    if (gender === "") {
      setError("gender", true);
      isValid = false;
    } else {
      setError("gender", false);
    }

    // Phone Number - required, must match pattern
    var phoneNumber = document.getElementById("phoneNumber").value.trim();
    if (!phonePattern.test(phoneNumber)) {
      setError("phoneNumber", true);
      isValid = false;
    } else {
      setError("phoneNumber", false);
    }

    // Email - required, must look like an email address
    var email = document.getElementById("email").value.trim();
    if (!emailPattern.test(email)) {
      setError("email", true);
      isValid = false;
    } else {
      setError("email", false);
    }

    // Department - required
    var department = document.getElementById("department").value;
    if (department === "") {
      setError("department", true);
      isValid = false;
    } else {
      setError("department", false);
    }

    // Appointment Date - required and cannot be in the past
    var dateValue = document.getElementById("appointmentDate").value;
    if (dateValue === "") {
      setError("appointmentDate", true);
      isValid = false;
    } else {
      var chosenDate = new Date(dateValue + "T00:00:00");
      var today = new Date();
      today.setHours(0, 0, 0, 0);

      if (chosenDate < today) {
        setError("appointmentDate", true);
        isValid = false;
      } else {
        setError("appointmentDate", false);
      }
    }

    return isValid;
  }

  // Stop the browser from picking a past date in the first place
  var dateInput = document.getElementById("appointmentDate");
  if (dateInput) {
    var todayStr = new Date().toISOString().split("T")[0];
    dateInput.setAttribute("min", todayStr);
  }

  // Run validation when the form is submitted
  form.addEventListener("submit", function (event) {
    if (!validateForm()) {
      event.preventDefault(); // stop the form from being sent to PHP
    }
  });

  // Clear all error messages when the user clicks Reset
  if (resetBtn) {
    resetBtn.addEventListener("click", function () {
      var errors = form.querySelectorAll(".error-message");
      errors.forEach(function (el) {
        el.style.display = "none";
      });
      var inputs = form.querySelectorAll("input, select");
      inputs.forEach(function (el) {
        el.style.borderColor = "#ccc";
      });
    });
  }
});
