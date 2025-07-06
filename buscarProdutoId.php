<?php
header('Content-Type: application/json');
require 'conexao.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
    exit;
}

try {
    // Consulta principal do produto
    $sql = "SELECT 
                p.id, p.nomeItem, p.descItem, p.valorItem, p.estoqueItem, 
                p.idCategoria, p.idColecao,
                c.ctgNome AS categoria, 
                col.colecaoNome AS colecao,
                img.nomeImagem AS imagem
            FROM tb_produto p
            JOIN tb_categoria c ON p.idCategoria = c.id
            JOIN tb_colecao col ON p.idColecao = col.id
            LEFT JOIN tb_imagemProduto img 
                ON img.idProduto = p.id AND img.statusImagem = 'principal'
            WHERE p.id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado']);
        exit;
    }

    // Consulta os estoques por tamanho (se houver)
    $stmtTamanhos = $pdo->prepare("SELECT tamanho, estoque FROM tb_produto_tamanho WHERE idProduto = ?");
    $stmtTamanhos->execute([$produto['id']]);
    $tamanhos = $stmtTamanhos->fetchAll(PDO::FETCH_KEY_PAIR); // ['P' => 10, 'M' => 5, 'G' => 0]

    // Preenche os estoques individuais (ou 0 se não existir)
    $produto['estoqueP'] = $tamanhos['P'] ?? 0;
    $produto['estoqueM'] = $tamanhos['M'] ?? 0;
    $produto['estoqueG'] = $tamanhos['G'] ?? 0;

    // Define se o produto é de tamanho único
    $produto['tamanhoUnico'] = empty($tamanhos);

    // Retorna o JSON
    echo json_encode(['status' => 'success', 'produto' => $produto]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar produto: ' . $e->getMessage()]);
}
