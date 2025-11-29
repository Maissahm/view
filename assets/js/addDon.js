document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("#don-form");

    // Champs
    const montant = document.querySelector("input[name='montant']");
    const num = document.querySelector("#card-number");
    const date = document.querySelector("#card-date");
    const cvc = document.querySelector("#card-cvc");
    const stream = document.querySelector("select[name='id_stream']");
    const projet = document.querySelector("select[name='id_projet']");

    const inputs = [montant, num, date, cvc, stream, projet];

    // ============================
    // CREATE MESSAGE BOX UNDER INPUT
    // ============================
    function attachMessageDiv(input) {
        let msg = document.createElement("div");
        msg.classList.add("msg-box");
        msg.style.fontSize = "13px";
        msg.style.marginTop = "4px";
        msg.style.fontWeight = "bold";
        msg.style.minHeight = "16px";
        input.insertAdjacentElement("afterend", msg);
    }

    inputs.forEach(input => attachMessageDiv(input));

    // ============================
    // ERROR AND SUCCESS FUNCTIONS
    // ============================
    function showError(input, message) {
        let msg = input.nextElementSibling;
        msg.style.color = "red";
        msg.innerHTML = "❌ " + message;
        input.style.border = "2px solid red";

        input.classList.add("shake");
        setTimeout(() => input.classList.remove("shake"), 350);
    }

    function showSuccess(input, message = "OK") {
        let msg = input.nextElementSibling;
        msg.style.color = "lime";
        msg.innerHTML = "✔ " + message;
        input.style.border = "2px solid lime";
    }

    // ============================
    // MAIN VALIDATION FUNCTION
    // ============================
    function validate() {
        let valid = true;

        // --- Montant ---
        if (montant.value.trim() === "" || Number(montant.value) <= 0) {
            showError(montant, "Montant invalide");
            valid = false;
        } else {
            showSuccess(montant);
        }

        // --- Numéro carte ---
        const rawNum = num.value.replace(/\s/g, "");
        if (rawNum.length !== 16 || isNaN(rawNum)) {
            showError(num, "Numéro carte invalide (16 chiffres)");
            valid = false;
        } else {
            showSuccess(num);
        }

       // --- Date expiration ---
if (!/^\d{2}\/\d{2}$/.test(date.value)) {
    showError(date, "Format invalide (MM/YY)");
    valid = false;
} else {
    const parts = date.value.split("/");
    const mm = Number(parts[0]);
    const yy = Number("20" + parts[1]); // convertit YY → 20YY

    const today = new Date();
    const currentMonth = today.getMonth() + 1; // 1–12
    const currentYear = today.getFullYear();

    if (mm < 1 || mm > 12) {
        showError(date, "Mois invalide (01–12)");
        valid = false;
    }
    else if (yy < currentYear) {
        showError(date, "Année expirée");
        valid = false;
    }
    else if (yy === currentYear && mm < currentMonth) {
        showError(date, "Carte expirée");
        valid = false;
    }
    else {
        showSuccess(date);
    }
}


        // --- CVC ---
        if (cvc.value.length !== 3 || isNaN(cvc.value)) {
            showError(cvc, "CVC = 3 chiffres");
            valid = false;
        } else {
            showSuccess(cvc);
        }

        // --- Projet ---
        if (!projet.value) {
            showError(projet, "Sélection obligatoire");
            valid = false;
        } else {
            showSuccess(projet);
        }

        // --- Stream ---
        if (!stream.value) {
            showError(stream, "Sélection obligatoire");
            valid = false;
        } else {
            showSuccess(stream);
        }

        return valid;
    }

    // ============================
    // LIVE VALIDATION
    // ============================
    inputs.forEach(input => {
        input.addEventListener("input", validate);
        input.addEventListener("change", validate);
    });

    // ============================
    // VALIDATION ON SUBMIT
    // ============================
    form.addEventListener("submit", function (e) {
        if (!validate()) {
            e.preventDefault();
        }
    });

});
