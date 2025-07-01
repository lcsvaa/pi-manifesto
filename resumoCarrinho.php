<?php
session_start();
header('Content-Type: application/json');

// Inicializa variáveis
$frete = 15.90; // Frete fixo para o exemplo
$subtotal = 0;
$desconto = 0;

// Calcula subtotal
foreach ($_SESSION['carrinho'] ?? [] as $item) {
    $subtotal += $item['preco'] * $item['qtd'];
}

// Aplica desconto do cupom se houver
if (isset($_SESSION['cupom'])) {
    $porcentagem = $_SESSION['cupom']['porcentagem'];
    $desconto = ($subtotal * $porcentagem) / 100;
}

$total = $subtotal + $frete - $desconto;

// Função de formatação
function formatarPreco($valor) {
    return number_format($valor, 2, ',', '.');
}

// Retorna JSON formatado
echo json_encode([
    'subtotal' => formatarPreco($subtotal),
    'frete' => formatarPreco($frete),
    'desconto' => formatarPreco($desconto),
    'total' => formatarPreco($total)
]);