document.getElementById("card-number").addEventListener("input", function () {
    let value = this.value.replace(/\D/g, "").substring(0, 16);
    this.value = value.replace(/(.{4})/g, "$1 ").trim();
    document.getElementById("display-number").textContent = this.value || "XXXX XXXX XXXX XXXX";
});

document.getElementById("card-name").addEventListener("input", function () {
    document.getElementById("display-name").textContent = this.value || "FULL NAME";
});

document.getElementById("card-month").addEventListener("change", function () {
    document.getElementById("display-month").textContent = this.value || "MM";
});

document.getElementById("card-year").addEventListener("change", function () {
    document.getElementById("display-year").textContent = this.value || "YY";
});
