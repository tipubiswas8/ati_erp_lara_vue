export const onlyNumber = {
  mounted(el) {
    el.addEventListener("input", () => {
      if (!/^\d*\.?\d*$/.test(el.value)) {
        el.value = el.oldValue || "";
        el.setCustomValidity("Only digits and '.' are allowed");
      } else {
        el.oldValue = el.value;
        el.setCustomValidity("");
      }
    });
  }
  };