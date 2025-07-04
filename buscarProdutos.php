<?php
header('Content-Type: application/json');
require 'conexao.php';

$termo = $_GET['termo'] ?? '';
$termo = trim($termo);

try {
    $sql = "SELECT p.id, p.nomeItem, p.valorItem, p.estoqueItem, 
                   c.ctgNome AS categoria, 
                   col.colecaoNome AS colecao,
                   img.nomeImagem AS imagem
            FROM tb_produto p
            JOIN tb_categoria c ON p.idCategoria = c.id
            JOIN tb_colecao col ON p.idColecao = col.id
            LEFT JOIN tb_imagemProduto img ON img.idProduto = p.id AND img.statusImagem = 'principal'
            WHERE p.nomeItem LIKE ?
            ORDER BY p.nomeItem";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$termo%"]);

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