<?php
require '../config/db.php';
require '../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

MercadoPagoConfig::setAccessToken('APP_USR-65443282432-071219-djoodjgoiozodskplkk21323k3212'); // Substitua pelo seu token

header('Content-Type: application/json');

try {
    $paymentId = $_GET['payment_id'] ?? null;
    if (!$paymentId) {
        throw new Exception('ID do pagamento não informado');
    }

    $client = new PaymentClient();
    $payment = $client->get($paymentId);

    echo json_encode([
        'status' => $payment->status,
        'payment_id' => $payment->id
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
