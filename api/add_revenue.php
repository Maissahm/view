<?php
session_start();

require_once "../../connection.php"; // contient class config

header("Content-Type: application/json");

// Récupérer connexion PDO
$pdo = config::getConnexion();  // ✔ IMPORTANT

// Récupération des données JSON
$data = json_decode(file_get_contents("php://input"), true);

$amount = floatval($data['amount']);
$receiverId = intval($data['receiverId']);

if (!$amount || !$receiverId) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

// Mise à jour du portefeuille
$stmt = $pdo->prepare("
    UPDATE portefeuille 
    SET revenue = revenue + ?, 
        total_gain = total_gain + ?
    WHERE id_user = ?
");

$stmt->execute([$amount, $amount, $receiverId]);

echo json_encode(["status" => "success"]);