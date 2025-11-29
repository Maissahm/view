<?php
session_start();

require_once '../Controller/DonController.php';
require_once '../Controller/ProjetController.php';
$donCtrl = new DonController();
$projetCtrl = new ProjetController();


$id_stream = $_GET['id_stream'] ?? null;
$session_id = $_GET['session_id'] ?? null;

// Sauvegarde sécurité
if ($id_stream) {
    $_SESSION['last_stream_id'] = $id_stream;
}

// Récupération Stripe
require_once "../config/config.php";

$amount = null;
$id_projet = null;
$nom_projet = null;

if ($session_id) {
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $amount = ($session->amount_total ?? 0) / 100;
        $id_projet = $session->metadata->id_projet ?? null;

        // 🔥 Récupération du nom du projet
        if ($id_projet) {
          $projet = $projetCtrl->getProjetById($id_projet);


            if ($projet) {
                $nom_projet = $projet['titre_projet'] ?? "Projet inconnu";
            }
        }

    } catch (Exception $e) {
        // silencieux
    }
}

// Redirection retour viewer
$viewer_link = $id_stream ? "viewer.php?id=" . intval($id_stream) : "viewer.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement réussi 🎉</title>
    <style>
        body {
            background: #1b1d31;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 80px;
        }
        a {
            background: #4caf50;
            padding: 12px 22px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        a:hover { background: #43a047; }
    </style>
</head>
<body>

<h1>Paiement réussi 🎉</h1>
<p>Merci pour votre don ❤️</p>

<?php if ($amount !== null): ?>
<p>Montant payé : <strong><?= htmlspecialchars($amount) ?> €</strong></p>
<?php endif; ?>

<?php if ($nom_projet): ?>
<p>Projet : <strong><?= htmlspecialchars($nom_projet) ?></strong></p>
<?php endif; ?>

<br><br>
<a href="donation.php">⬅ consulter la progression des projets</a>

</body>
</html>
