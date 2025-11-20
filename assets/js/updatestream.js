document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");
    const titreInput = document.querySelector("input[name='titre']");
    const startInput = document.querySelector("input[name='start_time']");
    const endInput = document.querySelector("input[name='end_time']");

    // 🔥 Fonction utilitaire pour afficher un message dynamique
    function showMsg(input, message, isValid) {
        let msg = input.nextElementSibling;
        if (!msg || !msg.classList.contains("msg")) {
            msg = document.createElement("div");
            msg.classList.add("msg");
            msg.style.fontSize = "12px";
            msg.style.marginTop = "4px";
            msg.style.fontFamily = "Orbitron";
            input.insertAdjacentElement("afterend", msg);
        }

        if (isValid) {
            input.style.borderColor = "#00ff7f";
            input.style.boxShadow = "0 0 10px #00ff7f";
            msg.style.color = "#00ff7f";
            msg.innerHTML = "✔ " + message;
        } else {
            input.style.borderColor = "red";
            input.style.boxShadow = "0 0 10px red";
            msg.style.color = "red";
            msg.innerHTML = "✘ " + message;

            // Animation shake
            input.classList.add("shake");
            setTimeout(() => input.classList.remove("shake"), 400);
        }
    }


    // 🎯 Validation Titre
    function validateTitre() {
        const value = titreInput.value.trim();
        const regex = /^[A-Za-z][A-Za-z0-9\s-_]{3,}$/;

        if (value === "") {
            showMsg(titreInput, "Le titre est obligatoire.", false);
            return false;
        }
        if (!regex.test(value)) {
            showMsg(titreInput, "4+ caractères, ne commence pas par un chiffre.", false);
            return false;
        }

        showMsg(titreInput, "Titre valide.", true);
        return true;
    }

    // 🎯 Validation Dates
    function validateDates() {
        const start = startInput.value ? new Date(startInput.value) : null;
        const end = endInput.value ? new Date(endInput.value) : null;
        let ok = true;

        if (!start) {
            showMsg(startInput, "Date de début obligatoire.", false);
            ok = false;
        } else {
            showMsg(startInput, "Date valide.", true);
        }

        if (!end) {
            showMsg(endInput, "Date de fin obligatoire.", false);
            ok = false;
        } else if (start && end <= start) {
            showMsg(endInput, "Fin doit être > début.", false);
            ok = false;
        } else {
            showMsg(endInput, "Date valide.", true);
        }

        return ok;
    }

    // Event listeners temps réel
    titreInput.addEventListener("keyup", validateTitre);
    startInput.addEventListener("change", validateDates);
    endInput.addEventListener("change", validateDates);

    // Blocage soumission si erreurs
    form.addEventListener("submit", function (e) {
        if (!validateTitre() || !validateDates()) {
            e.preventDefault();
        }
    });

    // 🔥 Ajout animation shake CSS via JS
    const style = document.createElement('style');
    style.innerHTML = `
        .shake { 
            animation: shake 0.3s; 
        }
        @keyframes shake { 
            0% { transform: translateX(0); } 
            25% { transform: translateX(-4px); } 
            50% { transform: translateX(4px); } 
            75% { transform: translateX(-4px); } 
            100% { transform: translateX(0); } 
        }
    `;
    document.head.appendChild(style);
});
