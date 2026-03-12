<?php
require 'config/db.php';
require 'vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

header('Content-Type: application/json');


MercadoPagoConfig::setAccessToken('APP_USR-65443282432-071219-djoodjgoiozodskplkk21323k3212');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    
    if (empty($data['valor'])) {
        throw new Exception('Valor do pedido não informado.');
    }
    
    if (empty($data['pedido_id'])) {
        throw new Exception('ID do pedido não informado.');
    }
    
    if (empty($data['cliente']['email'])) {
        throw new Exception('E-mail do cliente é obrigatório.');
    }
    
    if (empty($data['cliente']['nome'])) {
        throw new Exception('Nome do cliente é obrigatório.');
    }
    
    if (empty($data['cliente']['telefone'])) {
        throw new Exception('Telefone do cliente é obrigatório.');
    }

    
    $request = [
        "transaction_amount" => (float)$data['valor'],
        "payment_method_id" => "pix",
        "description" => "Pedido #" . $data['pedido_id'],
        "notification_url" => "https://seusite.com/notificacoes.php", 
        "payer" => [
            "email" => $data['cliente']['email'],
            "first_name" => explode(' ', $data['cliente']['nome'])[0],
            "last_name" => explode(' ', $data['cliente']['nome'])[1] ?? '',
            "identification" => [
                "type" => "CPF",
                "number" => $data['cliente']['cpf'] ?? '00000000000' // CPF ou um valor padrão
            ],
            "address" => [
                "zip_code" => "00000000", // Opcional
                "street_name" => "Não informado", // Opcional
                "street_number" => "0", // Opcional
                "neighborhood" => "Não informado", // Opcional
                "city" => "Não informado", // Opcional
                "federal_unit" => "RS" // Opcional
            ]
        ]
    ];

    // Cria o pagamento
    $client = new PaymentClient();
    $payment = $client->create($request);

    // Formata a data de expiração
    $expira_em = null;
    if (isset($payment->date_of_expiration)) {
        $date = new DateTime($payment->date_of_expiration);
        $expira_em = $date->format('d/m/Y H:i:s');
    }

    // Retorna os dados do PIX
    echo json_encode([
        'sucesso' => true,
        'id_pagamento' => $payment->id,
        'qrcode' => $payment->point_of_interaction->transaction_data->qr_code,
        'qrcode_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64,
        'valor' => $payment->transaction_amount,
        'pedido_id' => $data['pedido_id'],
        'expira_em' => $expira_em,
        'status' => $payment->status
    ]);

} catch (MPApiException $e) {
    // Log do erro completo
    error_log('Erro na API Mercado Pago: ' . $e->getMessage());
    
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Erro no processamento do pagamento.',
        'detalhes' => $e->getMessage()
    ]);
} catch (Exception $e) {
    // Log do erro
    error_log('Erro geral: ' . $e->getMessage());
    
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}
?>