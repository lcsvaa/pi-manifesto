<?php
header('Content-Type: application/json');
require 'conexao.php';

$id = (int) ($_POST['idCupom'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
    exit;
}

try {
    // Buscar status atual
    $stmt = $pdo->prepare("SELECT statusCupom FROM tb_cupom WHERE idCupom = ?");
    $stmt->execute([$id]);
    $cupom = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cupom) {
        echo json_encode(['status' => 'error', 'message' => 'Cupom não encontrado']);
        exit;
    }

    // Inverter o status com segurança
    $statusAtual = strtolower(trim($cupom['statusCupom']));
    $novoStatus = ($statusAtual === 'ativo') ? 'desativado' : 'ativo';

    // Atualizar no banco
    $update = $pdo->prepare("UPDATE tb_cupom SET statusCupom = ? WHERE idCupom = ?");
    $ok = $update->execute([$novoStatus, $id]);

    echo json_encode([
        'status' => $ok ? 'success' : 'error',
        'message' => $ok ? "Status alterado para $novoStatus." : "Erro ao atualizar status."
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro no servidor']);
}