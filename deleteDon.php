<?php
require_once '../Controller/DonController.php';

if (!isset($_GET['id'])) {
    die("❌ Aucun ID fourni.");
}

$controller = new DonController();
$controller->deleteDon($_GET['id']);

header("Location: donation.php");
exit;
