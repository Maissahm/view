<?php
require_once "../../connection.php"; // contient $pdo

header("Content-Type: application/json");
$pdo = config::getConnexion();


$stmt = $pdo->query("SELECT * FROM gifts");
$gifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($gifts);
