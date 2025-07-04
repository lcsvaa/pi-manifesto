<?php
header('Content-Type: application/json');
require 'conexao.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
    exit;
}

try {
    $sql = "SELECT p.id, p.nomeItem, p.descItem, p.valorItem, p.estoqueItem, 
                   p.idCategoria, p.idColecao,
                   c.ctgNome AS categoria, 
                   col.colecaoNome AS colecao,
                   img.nomeImagem AS imagem,
            FROM tb_produto p
            JOIN tb_categoria c ON p.idCategoria = c.id
            JOIN tb_colecao col ON p.idColecao = col.id
            LEFT JOIN tb_imagemProduto img ON img.idProduto = p.id AND img.statusImagem = 'principal'
            WHERE p.id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado']);
        exit;
    }

    if (!isset($produto['statusProduto']) || $produto['statusProduto'] === null) {
        $produto['statusProduto'] = 'ativo';
    }

    echo json_encode(['status' => 'success', 'produto' => $produto]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
