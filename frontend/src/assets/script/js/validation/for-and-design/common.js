
export const onlyNumber = {
  mounted(el) {
    el.oldValue = el.value;

    el.addEventListener("input", (event) => {
      const input = event.target;

      // Check if the current value is valid
      if (!/^\d*\.?\d*$/.test(input.value)) {
        // Revert to the last valid value if the input is invalid
        input.value = el.oldValue || "";
        input.setCustomValidity("Only digits and '.' are allowed");
        input.dispatchEvent(new Event("input")); // Dispatch event to update v-model binding
      } else {
        // Update the last valid value
        el.oldValue = input.value;
        input.setCustomValidity("");
      }
    });
  }
};
