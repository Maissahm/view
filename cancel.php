<?php
session_start();

// On récupère le stream depuis l’URL (si transmis)
$id_stream = $_GET['id_stream'] ?? null;

// Si on a un stream → retour automatique vers viewer.php?id=XX
$viewer_link = $id_stream ? "viewer.php?id=" . intval($id_stream) : "viewer.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement annulé ❌</title>
    <style>
        body {
            background: #1b1d31;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 80px;
        }
        a {
            background: #e53935;
            padding: 12px 22px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        a:hover { background: #d32f2f; }
    </style>
</head>
<body>

<h1>❌ Paiement annulé</h1>
<p>La transaction n’a pas été finalisée.</p>

<br><br>
<a href="index.php">🔄 Réessayer</a>

</body>
</html>
