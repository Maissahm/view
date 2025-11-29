<?php
header("Content-Type: application/json");
require_once "../../config/db.php"; // ⚠️ adapter chemin

$id_projet = $_GET['id_projet'] ?? 0;
$id_stream = $_GET['id_stream'] ?? 0;

if (!$id_projet || !$id_stream) {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

/* 🔹 Récupération infos projet */
$stmt = $pdo->prepare("SELECT montant_collecte, montant_estime FROM projet WHERE id_projet = ?");
$stmt->execute([$id_projet]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projet) {
    echo json_encode(["error" => "Projet introuvable"]);
    exit;
}

$collecte = $projet['montant_collecte'];
$objectif = $projet['montant_estime'];
$reste = $objectif - $collecte;

/* 🔹 Récupération viewers */
$stmt = $pdo->prepare("SELECT viewers FROM stream WHERE id_stream = ?");
$stmt->execute([$id_stream]);
$stream = $stmt->fetch(PDO::FETCH_ASSOC);

$viewers = $stream ? intval($stream['viewers']) : 0;


/* ------------------------------------------------------------------
   🤖 LOGIQUE D’INTELLIGENCE ARTIFICIELLE (IA INTÉGRÉE)
------------------------------------------------------------------- */

$message = null;

// 🎯 Objectif presque atteint
if ($reste <= 20 && $reste > 0) {
    $message = "🎯 Le projet est sur le point d’être financé ! Il manque seulement $reste€. Qui participe ? ❤️";
}

// 📉 Projet en difficulté
else if ($collecte < $objectif * 0.25) {
    $message = "⚠️ Le projet avance lentement… même un petit don peut faire la différence 🙏";
}

// 🔥 Beaucoup de spectateurs
else if ($viewers >= 10) {
    $message = "🔥 Vous êtes $viewers spectateurs ! Si chacun donnait 1€, le projet serait financé en quelques secondes ❤️";
}

// 💙 Encouragement général
else {
    $message = "💙 Un simple geste peut tout changer. Merci pour votre soutien 🙌";
}

echo json_encode([
    "message" => $message,
    "stats" => [
        "collecte" => $collecte,
        "objectif" => $objectif,
        "reste" => $reste,
        "viewers" => $viewers
    ]
]);
