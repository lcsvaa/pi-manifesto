<?php
require_once 'config.php'; // Deve conter: $acess_token

// Recebe os dados enviados via JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Garante dados essenciais
$transactionAmount = floatval($dados['total'] ?? 0);
$descricao = $dados['descricao'] ?? 'Pedido na loja via PIX';
$email = $dados['email'] ?? 'sem-email@teste.com';

// Monta os dados do pagamento PIX
$data = [
    "transaction_amount" => $transactionAmount,
    "description" => $descricao,
    "payment_method_id" => "pix",
    "payer" => [
        "email" => $email
    ]
];

// Configurações de requisição
$url = "https://api.mercadopago.com/v1/payments";
$headers = [
    "Authorization: Bearer $acess_token",
    "Content-Type: application/json",
    "X-Idempotency-Key: " . bin2hex(random_bytes(16))
];

// Inicializa a requisição cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Executa e trata a resposta
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Retorna resposta JSON ao front-end
header('Content-Type: application/json');
http_response_code($httpCode);
echo $response;
?>