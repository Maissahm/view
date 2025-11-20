<?php
require_once '../Controller/DonController.php';
require_once '../model/Don.php';

$controller = new DonController();

if (!isset($_GET['id'])) {
    die("❌ ID manquant.");
}

$id_don = intval($_GET['id']);
$donData = $controller->getDonById($id_don);

if (!$donData) {
    die("❌ Don introuvable.");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $don = new Don(
        $_POST['id_projet'],
        $_POST['id_stream'],
        $_POST['id_user'],
        $_POST['montant']
    );

    $controller->updateDon($id_don, $don);

    $message = "Don modifié avec succès !";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un don</title>
</head>

<body>

<h2>✏ Modifier le don #<?= $id_don ?></h2>

<?php if ($message): ?>
    <p style="color:green;"><?= $message ?></p>
<?php endif; ?>

<form method="POST">

    <label>ID Projet :</label><br>
    <input type="number" name="id_projet" value="<?= $donData['id_projet'] ?>" required><br><br>

    <label>ID Stream :</label><br>
    <input type="number" name="id_stream" value="<?= $donData['id_stream'] ?>" required><br><br>

    <label>ID User :</label><br>
    <input type="number" name="id_user" value="<?= $donData['id_user'] ?>" required><br><br>

    <label>Montant :</label><br>
    <input type="number" name="montant" value="<?= $donData['montant'] ?>" required><br><br>

    <button type="submit">Enregistrer</button>
</form>

<br>
<a href="donation.php">⬅ Retour</a>

</body>
</html>
