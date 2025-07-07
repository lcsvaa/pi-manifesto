<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'erro', 'msg' => 'Requisição inválida']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'erro', 'msg' => 'Usuário não logado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$idUsuario = $_SESSION['user_id'];
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    echo json_encode(['status' => 'erro', 'msg' => 'Carrinho vazio']);
    exit;
}

$formaPagamento = $input['formaPagamento'] ?? null;
if (!$formaPagamento) {
    echo json_encode(['status' => 'erro', 'msg' => 'Forma de pagamento não informada']);
    exit;
}

$frete = ($input['shipping'] ?? 'standard') === 'express' ? 29.90 : 15.90;

try {
    $pdo->beginTransaction();

    // Calcular total
    $totalProdutos = 0;
    foreach ($carrinho as $item) {
        $totalProdutos += $item['preco'] * $item['qtd'];
    }
    $totalCompra = $totalProdutos + $frete;

    // Inserir compra
    $stmt = $pdo->prepare("INSERT INTO tb_compra (idUsuario, valorTotal) VALUES (?, ?)");
    $stmt->execute([$idUsuario, $totalCompra]);
    $idCompra = $pdo->lastInsertId();

    // Preparar statements
    $stmtItem = $pdo->prepare("INSERT INTO tb_itemCompra (idCompra, idProduto, quantidade, valorUnitario, tamanho) VALUES (?, ?, ?, ?, ?)");
    $stmtEstoqueTamanho = $pdo->prepare("UPDATE tb_produto_tamanho SET estoque = estoque - ? WHERE idProduto = ? AND tamanho = ?");
    $stmtEstoqueSimples = $pdo->prepare("UPDATE tb_produto SET estoqueItem = estoqueItem - ? WHERE id = ?");
    $stmtCheckTamanho = $pdo->prepare("SELECT estoque FROM tb_produto_tamanho WHERE idProduto = ? AND tamanho = ?");
    $stmtCheckSimples = $pdo->prepare("SELECT estoqueItem FROM tb_produto WHERE id = ?");

    // Verificar estoque antes de inserir
    foreach ($carrinho as $item) {
        $idProduto = $item['id'];
        $quantidade = $item['qtd'];
        $tamanho = $item['tamanho'] ?? null;

        if ($tamanho && $tamanho !== 'Único') {
            // Produto com tamanho
            $stmtCheckTamanho->execute([$idProduto, $tamanho]);
            $estoque = $stmtCheckTamanho->fetchColumn();

            if ($estoque === false) {
                throw new Exception("Produto com tamanho '$tamanho' não encontrado (ID $idProduto).");
            }
            if ($estoque < $quantidade) {
                throw new Exception("Estoque insuficiente para o produto ID $idProduto, tamanho $tamanho. Disponível: $estoque, solicitado: $quantidade.");
            }
        } else {
            // Produto sem tamanho
            $stmtCheckSimples->execute([$idProduto]);
            $estoque = $stmtCheckSimples->fetchColumn();

            if ($estoque === false) {
                throw new Exception("Produto sem tamanho não encontrado (ID $idProduto).");
            }
            if ($estoque < $quantidade) {
                throw new Exception("Estoque insuficiente para o produto ID $idProduto. Disponível: $estoque, solicitado: $quantidade.");
            }
        }
    }

    // Inserir itens e atualizar estoque
    foreach ($carrinho as $item) {
        $idProduto = $item['id'];
        $quantidade = $item['qtd'];
        $valorUnitario = $item['preco'];
        $tamanho = $item['tamanho'] ?? 'Único';

        // Gravar item
        $stmtItem->execute([$idCompra, $idProduto, $quantidade, $valorUnitario, $tamanho]);

        // Atualizar estoque
        if ($tamanho && $tamanho !== 'Único') {
            $stmtEstoqueTamanho->execute([$quantidade, $idProduto, $tamanho]);
        } else {
            $stmtEstoqueSimples->execute([$quantidade, $idProduto]);
        }
    }

    // Inserir pagamento
    $stmtPagamento = $pdo->prepare("INSERT INTO tb_pagamento (statusPagamento, tipoPagamento, compraid) VALUES (?, ?, ?)");
    $stmtPagamento->execute(['Processando', $formaPagamento, $idCompra]);

    $pdo->commit();
    unset($_SESSION['carrinho']);

    echo json_encode(['status' => 'ok', 'msg' => 'Pedido realizado com sucesso!']);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'status' => 'erro',
        'msg' => 'Erro ao finalizar o pedido.',
        'error' => $e->getMessage()
    ]);
}
