<?php
header('Content-Type: application/json');
require 'conexao.php';

try {
    $sql = "SELECT id, ctgNome FROM tb_categoria ORDER BY ctgNome";
    $stmt = $pdo->query($sql);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'categorias' => $categorias]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}