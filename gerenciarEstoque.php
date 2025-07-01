<?php
require_once 'conexao.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
    exit;
}

$idProduto = $_POST['idProduto'] ?? null;
$acao = $_POST['acao'] ?? null;

if (!$idProduto || !$acao) {
    echo json_encode(['status' => 'error', 'message' => 'Parâmetros insuficientes']);
    exit;
}

try {

    $stmt = $pdo->prepare("SELECT estoqueItem FROM tb_produto WHERE id = :id");
    $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado']);
        exit;
    }

    $estoqueAtual = (int)$produto['estoqueItem'];

    if ($acao === 'incrementar') {
        $novoEstoque = $estoqueAtual + 1;
    } elseif ($acao === 'decrementar') {
        $novoEstoque = max(0, $estoqueAtual - 1); 
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ação inválida']);
        exit;
    }

    $stmtUpdate = $pdo->prepare("UPDATE tb_produto SET estoqueItem = :novoEstoque WHERE id = :id");
    $stmtUpdate->bindParam(':novoEstoque', $novoEstoque, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':id', $idProduto, PDO::PARAM_INT);
    $stmtUpdate->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Estoque atualizado',
        'novoEstoque' => $novoEstoque
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro no banco: ' . $e->getMessage()]);
    exit;
}
