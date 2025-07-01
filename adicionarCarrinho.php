<?php
session_start();

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['id'])) {
    echo json_encode(['status' => 'erro', 'msg' => 'Dados inválidos']);
    exit;
}

$id = $input['id'];
$tamanho = $input['tamanho'] ?? 'Único';
$imagem = $input['imagem'] ?? 'default.png';

$key = $id . '_' . $tamanho;

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_SESSION['carrinho'][$key])) {
    $_SESSION['carrinho'][$key]['qtd'] += $input['qtd'];
} else {
    $_SESSION['carrinho'][$key] = [
        'id' => $id,
        'nome' => $input['nome'],
        'preco' => $input['preco'],
        'qtd' => $input['qtd'],
        'tamanho' => $tamanho,
        'imagem' => $imagem
    ];
}

echo json_encode(['status' => 'ok']);
