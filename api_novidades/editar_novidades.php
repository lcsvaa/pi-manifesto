<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once '../conexao.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Método não permitido', 405);
    }

    $id       = (int) ($_POST['idNovidade'] ?? 0);
    $titulo   = trim($_POST['titulo']   ?? '');
    $data     = trim($_POST['data']     ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $file     = $_FILES['imagem']       ?? null;

    if ($id <= 0) {
        throw new RuntimeException('ID inválido', 422);
    }
    if ($titulo === '' || $data === '' || $conteudo === '') {
        throw new RuntimeException('Campos obrigatórios ausentes', 422);
    }

    $stmt = $pdo->prepare('SELECT imagemNovidade FROM tb_novidades WHERE idNovidade = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        throw new RuntimeException('Novidade não encontrada', 404);
    }
    $imagemAtual = $row['imagemNovidade'];

    $novoNome = $imagemAtual;
    if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Upload inválido', 400);
        }
        if (!in_array(mime_content_type($file['tmp_name']), ['image/jpeg','image/png','image/gif'], true)) {
            throw new RuntimeException('Tipo de imagem não permitido', 415);
        }
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $novoNome = uniqid('nov_', true) . '.' . $ext;
        $dest = __DIR__ . '/../uploads/' . $novoNome;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new RuntimeException('Falha ao mover a imagem', 500);
        }
        
        @unlink(__DIR__ . '/../uploads/' . $imagemAtual);
    }

    
    $sql = 'UPDATE tb_novidades 
               SET titulo = :titulo,
                   dataNovidade = :data,
                   imagemNovidade = :imagem,
                   conteudo = :conteudo
             WHERE idNovidade = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo'  => $titulo,
        ':data'    => $data,
        ':imagem'  => $novoNome,
        ':conteudo'=> $conteudo,
        ':id'      => $id
    ]);

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code((int) ($e->getCode() ?: 400));
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
