// ===============================
//  VALIDATION + ANIMATION CARTE
// ===============================

// Champs + affichage sur la carte
const numberInput = document.getElementById("card-number");
const displayNumber = document.getElementById("display-number");

const nameInput = document.getElementById("card-name");
const displayName = document.getElementById("display-name");

const monthInput = document.getElementById("card-month");
const displayMonth = document.getElementById("display-month");

const yearInput = document.getElementById("card-year");
const displayYear = document.getElementById("display-year");

const cvvInput = document.getElementById("card-cvv");

// ===============================
//  MESSAGE D’ERREUR GLOBAL
// ===============================
function showError(input, msg) {
    let error = input.nextElementSibling;

    // Créer un <small> si n'existe pas
    if (!error || error.tagName.toLowerCase() !== "small") {
        error = document.createElement("small");
        error.style.color = "#ff4444";
        error.style.fontSize = "12px";
        error.style.display = "block";
        input.after(error);
    }

    error.textContent = msg;
    input.style.border = "2px solid #ff4444";
}

function clearError(input) {
    let error = input.nextElementSibling;
    if (error && error.tagName.toLowerCase() === "small") {
        error.textContent = "";
    }
    input.style.border = "2px solid #00cc88";
}

// ===============================
// 1️⃣ Numéro de carte — dynamique
// ===============================

numberInput.addEventListener("input", function () {

    let digits = this.value.replace(/\D/g, "").substring(0, 16);

    this.value = digits.replace(/(.{4})/g, "$1 ").trim();

    displayNumber.textContent = this.value || "XXXX XXXX XXXX XXXX";

    if (digits.length < 16) {
        showError(this, "Le numéro doit contenir 16 chiffres");
    } else {
        clearError(this);
    }
});


// ===============================
// 2️⃣ Nom sur la carte — dynamique
// ===============================

nameInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
    displayName.textContent = this.value.toUpperCase() || "FULL NAME";

    if (this.value.trim().length < 3) {
        showError(this, "Nom invalide");
    } else {
        clearError(this);
    }
});


// ===============================
// 3️⃣ Date — dynamique
// ===============================

monthInput.addEventListener("change", function () {
    displayMonth.textContent = this.value || "MM";
    clearError(this);
});

yearInput.addEventListener("change", function () {
    displayYear.textContent = this.value || "YY";
    clearError(this);
});


// ===============================
// 4️⃣ CVV — dynamique
// ===============================

cvvInput.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "").substring(0, 4);

    if (this.value.length < 3) {
        showError(this, "CVV invalide");
    } else {
        clearError(this);
    }
});


// ===============================
// 5️⃣ Validation finale
// ===============================

document.querySelector("form").addEventListener("submit", function (e) {

    if (numberInput.value.replace(/\s/g, "").length !== 16) {
        showError(numberInput, "Numéro de carte invalide");
        e.preventDefault();
    }

    if (nameInput.value.trim().length < 3) {
        showError(nameInput, "Nom invalide");
        e.preventDefault();
    }

    if (cvvInput.value.length < 3) {
        showError(cvvInput, "CVV invalide");
        e.preventDefault();
    }

    if (monthInput.value === "") {
        showError(monthInput, "Sélectionner un mois");
        e.preventDefault();
    }

    if (yearInput.value === "") {
        showError(yearInput, "Sélectionner une année");
        e.preventDefault();
    }
});
