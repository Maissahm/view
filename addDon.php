<?php
require_once '../Controller/DonController.php';
require_once '../model/Don.php';

$ctrl = new DonController();

$don = new Don(
    $_POST['id_projet'],
    $_POST['id_stream'],
    $_POST['id_user'],
    $_POST['montant']
);

$ctrl->addDon($don);

header("Location: donation.php?success=1");
?>
