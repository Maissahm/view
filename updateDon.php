<?php
require_once '../controller/DonController.php';
require_once '../model/don.php';

$controller = new DonController();

if (!isset($_GET['id'])) {
    die("❌ ID manquant.");
}

$id_don = intval($_GET['id']);
$donData = $controller->getDonById($id_don);

if (!$donData) {
    die("❌ Don introuvable.");
}

// ✅ Récupérer les projets AVANT l'affichage
$projets = $controller->getAllProjetsInfos();

$message = "";

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $don = new Don(
        $_POST['id_projet'],
        $donData['id_stream'],   // pas modifiable
        $donData['id_user'],     // pas modifiable
        $_POST['montant']
    );

    $controller->updateDon($id_don, $don);

    $message = "L'equipe G4S vous remercie pour votre Don";
        header("Location: donation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Don</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="updateDon.css">
</head>

<body>

<div class="container">

    <h2>✏ Modifier votre Don </h2>
    <br>
    <p>veuillez inscrire les informations à modifier ci dessous 👇</p>
    <br>

    <?php if ($message): ?>
        <p class="success-message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">

     <label>Projet :</label>
<select name="id_projet" class="select-field" required>
    <?php foreach ($projets as $p): ?>
        <option value="<?= $p['id_projet'] ?>" 
            <?= ($p['id_projet'] == $donData['id_projet']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['titre_projet']) ?>
        </option>
    <?php endforeach; ?>
</select>

       <label>Montant :</label>
<input type="number" 
       name="montant" 
       class="input-field" 
       value="<?= $donData['montant'] ?>" 
       required>

<p id="montant-error" class="error-text-gaming"></p>


<button type="submit" class="submit-btn">💾 Enregistrer</button>

    </form>

    <a href="donation.php">⬅ Retour</a>

</div>
<script src="assets/js/validationDon.js"></script>

</body>
</html>
