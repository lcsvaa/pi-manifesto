<?php
require_once 'conexao.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID do produto não foi enviado.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM tb_produto WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Produto removido com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao remover o produto.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro no banco: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida.']);
}