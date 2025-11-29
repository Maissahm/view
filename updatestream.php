<?php
require_once '../Controller/StreamController.php';
require_once '../Model/Stream.php';

if (!isset($_GET['id'])) {
    die("ID manquant !");
}

$ctrl = new StreamController();
$streamData = $ctrl->getStreamById($_GET['id']);

if (!$streamData) {
    die("Stream introuvable !");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stream = new Stream(
        $_POST['titre'],
        $streamData['nb_viewers'],
        $streamData['total_dons'],
        $streamData['id_user'],
        $_POST['statut'],
        $_POST['start_time'],
        $_POST['end_time']
    );

    $ctrl->updateStream($_POST['id_stream'], $stream);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>🎮 Modifier un Stream</title>

<!-- Police gaming -->
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">

 <link rel="stylesheet" href="updatestream.css">

</head>
<body>

<div class="card">

<h2>🎮 Modifier Stream</h2>

<form method="POST">

    <input type="hidden" name="id_stream" value="<?= $streamData['id_stream'] ?>">

    <label>Titre</label>
    <input type="text" name="titre" value="<?= htmlspecialchars($streamData['titre']) ?>" required>

    <label>Statut</label>
    <select name="statut" required>
        <option value="scheduled" <?= $streamData['statut']=="scheduled"?"selected":"" ?>>Scheduled</option>
        <option value="live" <?= $streamData['statut']=="live"?"selected":"" ?>>Live</option>
        <option value="ended" <?= $streamData['statut']=="ended"?"selected":"" ?>>Ended</option>
    </select>

    <label>Heure de début</label>
    <input type="datetime-local" name="start_time"
           value="<?= $streamData['Start'] ? date('Y-m-d\TH:i', strtotime($streamData['Start'])) : '' ?>">

    <label>Heure de fin</label>
    <input type="datetime-local" name="end_time"
           value="<?= $streamData['End'] ? date('Y-m-d\TH:i', strtotime($streamData['End'])) : '' ?>">

    <button type="submit">Mettre à jour</button>

</form>

<a href="index.php" class="back">← Retour</a>

</div>

 <script src="assets/js/updatestream.js"></script>
</body>
</html>
