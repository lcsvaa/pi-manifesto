<?php
require_once 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida.']);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID do produto não foi enviado.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Buscar imagens para apagar arquivos fisicos
    $stmtImagens = $pdo->prepare("SELECT nomeImagem FROM tb_imagemProduto WHERE idProduto = :id");
    $stmtImagens->execute([':id' => $id]);
    $imagens = $stmtImagens->fetchAll(PDO::FETCH_ASSOC);

    // Apagar registros de tamanhos vinculados
    $stmtDeleteTamanhos = $pdo->prepare("DELETE FROM tb_produto_tamanho WHERE idProduto = :id");
    $stmtDeleteTamanhos->execute([':id' => $id]);

    // Apagar registros de imagens vinculadas
    $stmtDeleteImagens = $pdo->prepare("DELETE FROM tb_imagemProduto WHERE idProduto = :id");
    $stmtDeleteImagens->execute([':id' => $id]);

    // Apagar produto
    $stmtDeleteProduto = $pdo->prepare("DELETE FROM tb_produto WHERE id = :id");
    $stmtDeleteProduto->execute([':id' => $id]);

    $pdo->commit();

    // Apagar arquivos fisicos após commit
    foreach ($imagens as $img) {
        $path = __DIR__ . '/uploads/produtos/' . $img['nomeImagem'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Produto removido com sucesso.']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Erro no banco: ' . $e->getMessage()]);
}