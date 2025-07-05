<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once '../conexao.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Método não permitido', 405);
    }

    $id = (int) ($_POST['idNovidade'] ?? 0);
    if ($id <= 0) {
        throw new RuntimeException('ID inválido', 422);
    }

    
    $stmt = $pdo->prepare('SELECT imagemNovidade FROM tb_novidades WHERE idNovidade = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        throw new RuntimeException('Novidade não encontrada', 404);
    }

    
    $del = $pdo->prepare('DELETE FROM tb_novidades WHERE idNovidade = :id');
    $del->execute([':id' => $id]);

    
    @unlink(__DIR__ . '/../uploads/' . $row['imagemNovidade']);

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code((int) ($e->getCode() ?: 400));
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
