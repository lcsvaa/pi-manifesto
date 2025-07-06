<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once '../conexao.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Método não permitido', 405);
    }

    $titulo   = trim($_POST['titulo']   ?? '');
    $data     = trim($_POST['data']     ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $file     = $_FILES['imagem']       ?? null;

    if ($titulo === '' || $data === '' || $conteudo === '' || !$file) {
        throw new RuntimeException('Campos obrigatórios ausentes', 422);
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Upload inválido', 400);
    }
    if (!in_array(mime_content_type($file['tmp_name']), ['image/jpeg','image/png','image/gif'], true)) {
        throw new RuntimeException('Tipo de imagem não permitido', 415);
    }

    $destDir = __DIR__ . '/../uploads/novidades/';
    if (!is_dir($destDir)) {
        if (!mkdir($destDir, 0755, true) && !is_dir($destDir)) {
            throw new RuntimeException('Falha ao criar a pasta de imagens', 500);
        }
    }

    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nome = uniqid('nov_', true) . '.' . $ext;
    $dest = $destDir . $nome;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Falha ao mover a imagem', 500);
    }

    $sql = 'INSERT INTO tb_novidades (titulo, dataNovidade, imagemNovidade, conteudo)
            VALUES (:titulo, :data, :imagem, :conteudo)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo'   => $titulo,
        ':data'     => $data,
        ':imagem'   => $nome,
        ':conteudo' => $conteudo
    ]);

    echo json_encode([
        'success' => true,
        'id'      => $pdo->lastInsertId()
    ]);
} catch (Throwable $e) {
    http_response_code((int) ($e->getCode() ?: 400));
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
