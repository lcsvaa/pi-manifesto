<?php
header('Content-Type: application/json');
require 'conexao.php';

try {
    $sql = "SELECT p.id, p.nomeItem, p.valorItem, p.estoqueItem, c.ctgNome as categoria, col.colecaoNome as colecao,
                   img.nomeImagem as imagem,
                   p.statusProduto
            FROM tb_produto p
            JOIN tb_categoria c ON p.idCategoria = c.id
            JOIN tb_colecao col ON p.idColecao = col.id
            LEFT JOIN tb_imagemProduto img ON img.idProduto = p.id AND img.statusImagem = 'principal'
            ORDER BY p.nomeItem";
    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($produtos as &$prod) {
        if (!isset($prod['statusProduto']) || $prod['statusProduto'] === null) {
            $prod['statusProduto'] = 'ativo'; 
        }
    }

    echo json_encode(['status' => 'success', 'produtos' => $produtos]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}