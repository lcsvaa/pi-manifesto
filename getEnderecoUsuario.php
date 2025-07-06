<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'erro', 'msg' => 'Usuário não autenticado.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT nome, email, telefone, cep, endereco, numero, complemento, bairro, cidade, estado FROM tb_endereco WHERE idUsuario = :id LIMIT 1");
    $stmt->execute([':id' => $userId]);
    $endereco = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($endereco) {
        echo json_encode(['status' => 'ok', 'dados' => $endereco]);
    } else {
        echo json_encode(['status' => 'vazio']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'msg' => 'Erro ao buscar endereço']);
}