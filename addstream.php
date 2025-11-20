<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    die("Erreur : Aucun utilisateur connecté.");
}


require_once '../Controller/StreamController.php';
require_once '../Model/Stream.php';

$streamController = new StreamController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre  = $_POST['titre'];
    $id_user = $_SESSION['id_user'];

    // Start & End choisis par l'utilisateur
    $start = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
    $end   = !empty($_POST['end_time']) ? $_POST['end_time'] : null;

    // Valeurs automatiques
    $nb_viewers = 0;
    $total_dons = 0;
    $statut = "scheduled";

    // Création du stream
    $stream = new Stream(
        $titre,
        $nb_viewers,
        $total_dons,
        $id_user,
        $statut,
        $start,
        $end
    );

    $streamController->addStream($stream);

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>🎮 Ajouter un Stream</title>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">



<link rel="stylesheet" href="assets/css/addstream.css">

</head>

<body>

<div class="card">

<h2>🎮 Ajouter un Stream</h2>

<form method="POST">

    <label>Titre du stream</label>
    <input type="text" name="titre" required>

    <label>Date et heure de début</label>
    <input type="datetime-local" name="start_time">

    <label>Date et heure de fin</label>
    <input type="datetime-local" name="end_time">

    <button type="submit">Ajouter</button>
</form>

<a href="index.php" class="back">← Retour</a>
 

</div>

 <script src="assets/js/validationStream.js"></script>

</body>
</html>
