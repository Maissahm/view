<?php
require_once '../controller/StreamController.php';

if (isset($_GET['id'])) {
    $ctrl = new StreamController();
    $ctrl->deleteStream($_GET['id']);
    header('Location:index.php');
    exit;
}
?>
