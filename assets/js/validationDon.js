document.addEventListener("DOMContentLoaded", () => {
    const montantInput = document.querySelector("input[name='montant']");
    const errorMsg = document.getElementById("montant-error");

    montantInput.addEventListener("input", () => {
        const value = parseFloat(montantInput.value);

        if (isNaN(value) || value <= 0) {
            montantInput.classList.add("input-error");
            errorMsg.textContent = "Le montant doit être supérieur à 0";
            errorMsg.style.opacity = "1";
        } else {
            montantInput.classList.remove("input-error");
            errorMsg.textContent = "";
            errorMsg.style.opacity = "0";
        }
    });
});
