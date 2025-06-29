<?php
require_once 'conexao.php';

header('Content-Type: application/json');

session_start();
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT 
        idEndereco, 
        apelidoEndereco, 
        cep, 
        rua, 
        numero, 
        complemento, 
        bairro, 
        cidade, 
        idUsuario
        FROM tb_endereco 
        WHERE idUsuario = ? 
        ORDER BY apelidoEndereco LIKE '(Principal)%' DESC, idEndereco DESC");
    $stmt->execute([$userId]);
    $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($enderecos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}