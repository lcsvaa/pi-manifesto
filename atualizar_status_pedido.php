<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$idPedido = $input['idPedido'] ?? null;
$novoStatus = $input['novoStatus'] ?? null;

$permitidos = ['Processando pagamento', 'Pago', 'Preparando pra enviar', 'Enviado', 'Recebido', 'Cancelado'];

if (!$idPedido || !$novoStatus || !in_array($novoStatus, $permitidos)) {
    echo json_encode(['status' => 'erro', 'msg' => 'Dados inválidos.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE tb_compra SET statusCompra = ? WHERE id = ?");
    $stmt->execute([$novoStatus, $idPedido]);
    echo json_encode(['status' => 'ok']);
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'msg' => 'Erro ao atualizar.', 'erro' => $e->getMessage()]);
}

