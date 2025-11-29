<?php
session_start();

require_once '../controller/DonController.php';
require_once '../model/don.php';
require_once '../controller/ProjetController.php';
$projetCtrl = new ProjetController();
$listeProjets = $projetCtrl->getAllProjets(); // méthode à adapter selon ton controller


$donCtrl = new DonController();

/* ----------------------------------------------------
   1️⃣  AFFICHAGE DU FORMULAIRE (AVEC DESIGN COMPLET)
----------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['payer'])) {

    if (!isset($_POST['id_projet'], $_POST['id_stream'], $_POST['id_user'], $_POST['montant'])) {
        die("Erreur : données manquantes !");
    }

    $id_projet  = $_POST['id_projet'];
    $id_stream  = $_POST['id_stream'];
    $id_user    = $_POST['id_user'];
    $montant    = $_POST['montant'];
    ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>

    <!-- Reset + ton CSS normal -->
    <link rel="stylesheet" href="carte.css">
    <link rel="stylesheet" href="https://public.codepenassets.com/css/reset-2.0.min.css">
    

    <!-- 🔥 CSS DIRECTEMENT INTÉGRÉ (prioritaire) -->
    <style>
        /* Monte ou descend toute la carte */
        .card-list {
            margin-bottom: 30px !important; /* augmente = carte descend / diminue = carte monte */
        }
        .card-form__inner {
            padding-top: 130px !important; /* diminue pour remonter les champs */
        }
        .card-input__input.-select {
            background-image: url("assets/images/dropdown.png") !important;
        }
        .card-input__label {
  font-size: 14px;
  margin-bottom: 5px;
  font-weight: 500;
  color: #f9f9f9ff;   /* <-- couleur actuelle (bleu foncé) */
  width: 100%;
  display: block;
  user-select: none;
}
.card-input__input {
    color: #ffffff !important;   /* Texte blanc dans MM / YY */
}
.card-input__input.-select option {
    color: #000000 !important;
    background: #ffffff !important;
}

    </style>

</head>

<body>
    <div class="card-input">
    <label class="card-input__label">Projet concerné</label>
    <select name="id_projet" class="card-input__input -select" required>
        <option value="">-- Sélectionner un projet --</option>

        <?php foreach ($listeProjets as $projet): ?>
            <option value="<?= $projet['id_projet'] ?>">
                <?= htmlspecialchars($projet['nom_projet']) ?>
            </option>
        <?php endforeach; ?>

    </select>
</div>


<div class="wrapper" id="app">
    <div class="card-form">

        <!-- ——————————————————————————————
             CARTE BANCAIRE AVEC VUE.JS 🔥
        ——————————————————————————————— -->
        <div class="card-list">
            <div class="card-item" v-bind:class="{ '-active' : isCardFlipped }">
                <div class="card-item__side -front">
                    <div class="card-item__focus"
                         v-bind:class="{'-active' : focusElementStyle }"
                         v-bind:style="focusElementStyle"></div>

                    <div class="card-item__cover">
                        <img v-bind:src="'assets/images/' + currentCardBackground + '.jpeg'" class="card-item__bg">
                    </div>

                    <div class="card-item__wrapper">
                        <div class="card-item__top">
                            <img src="assets/images/chip.png" class="card-item__chip">
                            <div class="card-item__type">
                                <transition name="slide-fade-up">
                                    <img v-bind:src="'assets/images/' + getCardType + '.png'" 
                                         v-if="getCardType"
                                         class="card-item__typeImg">
                                </transition>
                            </div>
                        </div>

                        <label class="card-item__number">
                            <span v-for="(n, $index) in otherCardMask" :key="$index">
                                <transition name="slide-fade-up">

                                    <div class="card-item__numberItem"
                                         v-if="$index > 4 && $index < 15 && cardNumber.length > $index && n.trim() !== ''">*</div>

                                    <div class="card-item__numberItem"
                                         v-else-if="cardNumber.length > $index">
                                         {{cardNumber[$index]}}
                                    </div>

                                    <div class="card-item__numberItem" v-else>{{n}}</div>

                                </transition>
                            </span>
                        </label>

                        <div class="card-item__content">
                            <label class="card-item__info">
                                <div class="card-item__holder">Card Holder</div>
                                <div class="card-item__name">{{ cardName || "FULL NAME" }}</div>
                            </label>

                            <div class="card-item__date">
                                <label class="card-item__dateTitle">Expires</label>
                                <span>{{ cardMonth || "MM" }}</span>/
                                <span>{{ cardYear ? String(cardYear).slice(2,4) : "YY" }}</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- BACK SIDE -->
                <div class="card-item__side -back">
                    <div class="card-item__cover">
                        <img v-bind:src="'assets/images/' + currentCardBackground + '.jpeg'" class="card-item__bg">
                    </div>

                    <div class="card-item__band"></div>

                    <div class="card-item__cvv">
                        <div class="card-item__cvvTitle">CVV</div>

                        <div class="card-item__cvvBand">
                            <span v-for="n in cardCvv">*</span>
                        </div>

                        <img v-bind:src="'assets/images/' + getCardType + '.png'" class="card-item__typeImg">
                    </div>

                </div>
            </div>
        </div>

        <!-- FORMULAIRE -->
        <form method="POST" action="carte_banc.php">
    
            <input type="hidden" name="id_stream" value="<?= $id_stream ?>">
            <input type="hidden" name="id_user" value="<?= $id_user ?>">
            <input type="hidden" name="montant" value="<?= $montant ?>">

            <div class="card-input">
                <label class="card-input__label">Card Number</label>
                <input type="text"
                       name="card_number"
                       class="card-input__input"
                       v-mask="generateCardNumberMask"
                       v-model="cardNumber"
                       required>
            </div>

            <div class="card-input">
                <label class="card-input__label">Card Holder</label>
                <input type="text"
                       name="card_name"
                       class="card-input__input"
                       v-model="cardName"
                       required>
            </div>

            <div class="card-form__row">
                <div class="card-form__col">
                    <label class="card-input__label">Expiration</label>

                    <select name="month" class="card-input__input -select" v-model="cardMonth" required>
                        <option value="">MM</option>
                        <?php for ($i=1;$i<=12;$i++): ?>
                            <option><?= str_pad($i,2,'0',STR_PAD_LEFT) ?></option>
                        <?php endfor; ?>
                    </select>

                    <select name="year" class="card-input__input -select" v-model="cardYear" required>
                        <option value="">YY</option>
                        <?php for ($i=2025;$i<=2035;$i++): ?>
                            <option><?= substr($i,2,2) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="card-form__col -cvv">
                    <label class="card-input__label">CVV</label>
                    <input type="text"
                           name="cvv"
                           class="card-input__input"
                           v-mask="'####'"
                           v-model="cardCvv"
                           required>
                </div>
            </div>

            <button type="submit" name="payer" class="card-form__button">Submit</button>
        </form>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
<script src="https://unpkg.com/vue-the-mask@0.11.1/dist/vue-the-mask.js"></script>
<script src="script.js"></script>

</body>
</html>

<?php
exit;
}

/* ----------------------------------------------------
   2️⃣  INSERTION DU DON ET REDIRECTION
----------------------------------------------------- */
if (isset($_POST['payer'])) {

    $don = new Don($_POST['id_projet'], $_POST['id_stream'], $_POST['id_user'], $_POST['montant']);

    try {
        $donCtrl->addDon($don);
        header("Location: donation.php?success=1");
        exit;
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
