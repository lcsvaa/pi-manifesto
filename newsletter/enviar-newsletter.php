<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once '../conexao.php';   

try {        // manifestoclothingstore@gmail.com   w7ks3M@9_Y[Z
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Método não permitido', 405);
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new RuntimeException('E‑mail inválido', 422);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO tb_newsletter (emailNews) VALUES (:email)'
    );
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    echo json_encode([
        'ok'      => true,
        'message' => 'E‑mail cadastrado com sucesso!'
    ], JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    
    $status = $e instanceof PDOException && $e->errorInfo[1] === 1062
        ? 409  
        : ($e->getCode() ?: 500);

    http_response_code($status);

    echo json_encode([
        'ok'      => false,
        'message' => 'O E-mail inserido já está cadastrado!'
    ], JSON_THROW_ON_ERROR);
}
