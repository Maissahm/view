<?php
session_start();

require_once '../controller/DonController.php';
require_once '../controller/¨ProjetController.php';
require_once '../model/don.php';
require_once '../model/projet.php';

$donCtrl = new DonController();

// 1️⃣ PREMIÈRE ÉTAPE : Affichage de la page paiement
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
    <link rel="stylesheet" href="dons.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="payment-container">

    <!-- 🟣 Carte affichée -->
    <div class="card-box">
        <div id="display-number">XXXX XXXX XXXX XXXX</div>
        <div id="display-name">FULL NAME</div>
        <div class="card-expiry">
            <span id="display-month">MM</span>/<span id="display-year">YY</span>
        </div>
    </div>

    <!-- 🟣 FORMULAIRE -->
    <form method="POST" action="addDon.php">

        <input type="hidden" name="id_projet" value="<?= $id_projet ?>">
        <input type="hidden" name="id_stream" value="<?= $id_stream ?>">
        <input type="hidden" name="id_user" value="<?= $id_user ?>">
        <input type="hidden" name="montant" value="<?= $montant ?>">

        <label>Numéro de carte</label>
        <input type="text" id="card-number" name="card_number" maxlength="19" required>

        <label>Nom sur la carte</label>
        <input type="text" id="card-name" name="card_name" required>

        <label>Mois</label>
        <br>
        <select id="card-month" name="month" required>
            <option value="">MM</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
            <?php endfor; ?>
        </select>

        <label>Année</label>
        <select id="card-year" name="year" required>
            <option value="">YY</option>
            <?php for ($i = 2025; $i <= 2035; $i++): ?>
                <option><?= substr($i, 2, 2) ?></option>
            <?php endfor; ?>
        </select>

        <label>CVV</label>
        <input type="text" id="card-cvv" name="cvv" maxlength="4" required>

        <button type="submit" name="payer">Payer</button>

    </form>

</div>

<script src="dons.js"></script>

</body>
</html>

<?php
exit;
}

// 2️⃣ DEUXIÈME ÉTAPE : Validation → enregistrement BD
if (isset($_POST['payer'])) {

    $id_projet  = $_POST['id_projet'];
    $id_stream  = $_POST['id_stream'];
    $id_user    = $_POST['id_user'];
    $montant    = $_POST['montant'];

    $don = new Don($id_projet, $id_stream, $id_user, $montant);

    try {
        $donCtrl->addDon($don);
        header("Location: donation.php?success=1");
        exit;
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
