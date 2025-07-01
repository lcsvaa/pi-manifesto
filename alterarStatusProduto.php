<?php
header('Content-Type: application/json');
require 'conexao.php';

$idProduto = intval($_POST['id'] ?? 0);
$novoStatus = $_POST['status'] ?? '';

if ($idProduto <= 0 || !in_array($novoStatus, ['ativo', 'desativado'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE tb_produto SET statusProduto = :status WHERE id = :id");
    $stmt->execute([
        ':status' => $novoStatus,
        ':id' => $idProduto
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Status alterado com sucesso.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar status: ' . $e->getMessage()]);
}