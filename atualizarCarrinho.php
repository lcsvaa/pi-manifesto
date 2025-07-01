<?php
session_start();
header('Content-Type: application/json');

// Suporte para JSON (POST)
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? ($_POST['id'] ?? null);
$tamanho = $data['tamanho'] ?? ($_POST['tamanho'] ?? '');

// Suporte para GET com ?key=...&quantidade=...
if (isset($_GET['key']) && isset($_GET['quantidade'])) {
    $key = $_GET['key'];
    $quantidade = max(1, intval($_GET['quantidade']));

    if (!isset($_SESSION['carrinho'][$key])) {
        echo json_encode(['status' => 'error', 'message' => 'Item não encontrado']);
        exit;
    }

    $_SESSION['carrinho'][$key]['qtd'] = $quantidade;
    echo json_encode(['status' => 'ok', 'message' => 'Quantidade atualizada']);
    exit;
}

// Suporte a POST/json sem chave
if (!$id || !isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida']);
    exit;
}

$quantidade = $data['quantidade'] ?? ($_POST['quantidade'] ?? 1);
$quantidade = max(1, intval($quantidade));

foreach ($_SESSION['carrinho'] as &$item) {
    if ($item['id'] == $id && $item['tamanho'] == $tamanho) {
        $item['qtd'] = $quantidade;
        echo json_encode(['status' => 'ok', 'message' => 'Quantidade atualizada']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Item não encontrado']);