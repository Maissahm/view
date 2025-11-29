<?php
header("Content-Type: application/json");

// Charger Stripe + config
require_once __DIR__ . "/../../config/config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['montant'])) {
    echo json_encode(["error" => "Données manquantes"]);
    exit;
}

$amount    = intval($data['montant']) * 100; // montant en CENTIMES
$id_projet = $data['id_projet'];
$id_stream = $data['id_stream'];
$id_user   = $data['id_user'];

try {
    $session = \Stripe\Checkout\Session::create([
        "payment_method_types" => ["card"],
        "line_items" => [[
            "price_data" => [
                "currency" => "eur",
                "product_data" => [
                    "name" => "Don pour projet #$id_projet"
                ],
                "unit_amount" => $amount
            ],
            "quantity" => 1
        ]],
        "mode" => "payment",

        // ⚠ On garde le session_id pour le récupérer dans success.php
     "success_url" => "http://localhost:8081/projetWEB(front%20office)/view/success.php?session_id={CHECKOUT_SESSION_ID}&id_stream=$id_stream",

        "cancel_url"  => "http://localhost:8081/projetWEB(front%20office)/view/cancel.php?id_stream=" . $id_stream,

        // On envoie les infos dont on aura besoin pour créer le don
        "metadata" => [
            "id_user"   => $id_user,
            "id_projet" => $id_projet,
            "id_stream" => $id_stream,
            "amount"    => $data['montant']  // montant en EUROS
        ]
    ]);

    echo json_encode([ "url" => $session->url ]);

} catch (Exception $e) {
    echo json_encode([ "error" => $e->getMessage() ]);
}
