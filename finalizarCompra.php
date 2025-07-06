<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'erro', 'msg' => 'Requisição inválida']);
    exit;
}

$dados = $_POST;
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    echo json_encode(['status' => 'erro', 'msg' => 'Carrinho vazio.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Inserir dados de endereço e cliente
    $stmt = $pdo->prepare("INSERT INTO tb_endereco (cep, endereco, numero, complemento, bairro, cidade, estado) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $dados['cep'], $dados['endereco'], $dados['numero'], $dados['complemento'],
        $dados['bairro'], $dados['cidade'], $dados['estado']
    ]);
    $idEndereco = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO tb_usuario (nome, email, telefone, idEndereco)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $dados['nome'], $dados['email'], $dados['telefone'], $idEndereco
    ]);
    $idCliente = $pdo->lastInsertId();

    // 2. Inserir pedido
    $stmt = $pdo->prepare("INSERT INTO tb_compra (idUsuario, frete, formaPagamento, total, dataCompra)
                           VALUES (?, ?, ?, ?, NOW())");

    $frete = ($dados['shipping'] === 'express') ? 29.90 : 15.90;
    $total = 0;

    foreach ($carrinho as $item) {
        $total += $item['preco'] * $item['qtd'];
    }
    $total += $frete;

    $stmt->execute([$idCliente, $frete, $dados['formaPagamento'] ?? 'indefinido', $total]);
    $idCompra = $pdo->lastInsertId();

    // 3. Inserir itens da compra
    $stmtItem = $pdo->prepare("INSERT INTO tb_itemCompra (idCompra, idProduto, quantidade, precoUnitario, tamanho)
                               VALUES (?, ?, ?, ?, ?)");
    foreach ($carrinho as $item) {
        $stmtItem->execute([
            $idCompra,
            $item['id'],
            $item['qtd'],
            $item['preco'],
            $item['tamanho']
        ]);
    }

    $pdo->commit();
    unset($_SESSION['carrinho']);

    echo json_encode(['status' => 'ok', 'msg' => 'Pedido realizado com sucesso!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'erro', 'msg' => 'Erro ao finalizar o pedido.']);
}