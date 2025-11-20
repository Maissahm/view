<?php
session_start();

if (!isset($_POST['id_projet'], $_POST['id_stream'], $_POST['id_user'], $_POST['montant'])) {
    die("Erreur : données manquantes !");
}

$id_projet  = $_POST['id_projet'];
$id_stream  = $_POST['id_stream'];
$id_user    = $_POST['id_user'];
$montant    = $_POST['montant'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Paiement</title>
<link rel="stylesheet" href="card.css">
</head>

<body>

<div class="payment-container">

    <div class="card-box">
        <div class="card-number" id="display-number">XXXX XXXX XXXX XXXX</div>
        <div class="card-holder" id="display-name">FULL NAME</div>
        <div class="card-expiry">
            <span id="display-month">MM</span>/<span id="display-year">YY</span>
        </div>
    </div>

    <form action="addDon.php" method="POST">

        <input type="hidden" name="id_projet" value="<?= $id_projet ?>">
        <input type="hidden" name="id_stream" value="<?= $id_stream ?>">
        <input type="hidden" name="id_user" value="<?= $id_user ?>">
        <input type="hidden" name="montant" value="<?= $montant ?>">

        <label>Numéro de carte</label>
        <input type="text" id="card-number" maxlength="19" required>

        <label>Nom sur la carte</label>
        <input type="text" id="card-name" required>

        <label>Mois</label>
        <select id="card-month" required>
            <option value="">MM</option>
            <?php for ($i=1; $i<=12; $i++): ?>
                <option><?= str_pad($i,2,'0',STR_PAD_LEFT) ?></option>
            <?php endfor; ?>
        </select>

        <label>Année</label>
        <select id="card-year" required>
            <option value="">YY</option>
            <?php for ($i=2025; $i<=2035; $i++): ?>
                <option><?= substr($i,2,2) ?></option>
            <?php endfor; ?>
        </select>

        <label>CVV</label>
        <input type="text" maxlength="4" required>

        <button type="submit">Payer</button>
    </form>

</div>

<script src="card.js"></script>
</body>
</html>
