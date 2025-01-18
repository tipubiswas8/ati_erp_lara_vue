
  // Define the global function directly in index.html
  function setInputFilter(elements, inputFilter, message) {
    elements.forEach((input) => {
      input.addEventListener("input", function () {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.setCustomValidity("");
        } else {
          this.value = this.oldValue || "";
          this.setCustomValidity(message);
        }
      });
    });
  }

  // Apply the function globally on page load
  document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".only-number");
    setInputFilter(elements, (value) => /^\d*\.?\d*$/.test(value), "Only digits and '.' are allowed");
  });

