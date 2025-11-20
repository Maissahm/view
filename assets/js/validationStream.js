document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");
    const titre = document.querySelector("input[name='titre']");
    const startInput = document.querySelector("input[name='start_time']");
    const endInput = document.querySelector("input[name='end_time']");
    const inputs = [titre, startInput, endInput];

    // Création automatique de message sous chaque input
    function attachMessageDiv(input) {
        let msg = document.createElement("div");
        msg.classList.add("msg-box");
        msg.style.fontSize = "13px";
        msg.style.marginTop = "4px";
        msg.style.fontWeight = "bold";
        msg.style.minHeight = "16px"; // empêche déplacement du layout
        input.insertAdjacentElement("afterend", msg);
    }

    inputs.forEach(input => attachMessageDiv(input));
function showError(input, message) {
    let msg = input.nextElementSibling;
    msg.style.color = "red";
    msg.innerHTML = "❌ " + message;
    input.style.border = "2px solid red";

    // Ajoute shake puis l'enlève automatiquement
    input.classList.add("shake");
    setTimeout(() => input.classList.remove("shake"), 350);
}


    // Afficher success
    function showSuccess(input, message = "OK") {
        let msg = input.nextElementSibling;
        msg.style.color = "lime";
        msg.innerHTML = "✔ " + message;
        input.style.border = "2px solid lime";
    }

    function validate() {
        let valid = true;
        const now = new Date();
        now.setSeconds(0, 0);

        const titleValue = titre.value.trim();
        let startDate = startInput.value ? new Date(startInput.value) : null;
        let endDate = endInput.value ? new Date(endInput.value) : null;

        // --- Titre ---
        if (titleValue.length < 3) {
            showError(titre, "Min 3 caractères.");
            valid = false;
        } else if (/^[0-9]/.test(titleValue)) {
            showError(titre, "Ne commence pas par chiffre.");
            valid = false;
        } else {
            showSuccess(titre);
        }

        // --- Date début ---
        if (!startDate) {
            showError(startInput, "Date début obligatoire.");
            valid = false;
        } else if (startDate < now) {
            showError(startInput, "Début ne peut pas être passé.");
            valid = false;
        } else {
            showSuccess(startInput);
        }

        // --- Date fin ---
        if (endDate && startDate && endDate <= startDate) {
            showError(endInput, "Fin > début obligatoire.");
            valid = false;
        } else if (!endInput.value) {
            showError(endInput, "Date fin obligatoire.");
            valid = false;
        } else {
            showSuccess(endInput);
        }

        return valid;
    }

    // Live validation
    inputs.forEach(input => {
        input.addEventListener("input", validate);
        input.addEventListener("change", validate);
    });

    // Validation on submit
    form.addEventListener("submit", function (e) {
        if (!validate()) {
            e.preventDefault();
        }
    });

});
