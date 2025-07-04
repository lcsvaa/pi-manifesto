<?php
require_once 'conexao.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'message' => ''];

try {
    if ($action === 'remove') {
        // Exclusão de imagem
        $id = $_GET['id'];
        
        // Primeiro obtemos o nome do arquivo para deletar
        $stmt = $pdo->prepare("SELECT nomeImagem FROM tb_imagem WHERE idImagem = ?");
        $stmt->execute([$id]);
        $imagem = $stmt->fetch();
        
        if ($imagem) {
            // Excluir do banco
            $stmt = $pdo->prepare("DELETE FROM tb_imagem WHERE idImagem = ?");
            $stmt->execute([$id]);
            
            // Excluir arquivo físico
            $filePath = 'uploads/carrossel/' . $imagem['nomeImagem'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $response['success'] = true;
        } else {
            $response['message'] = 'Imagem não encontrada';
        }
    } else {
        // Upload/atualização de imagem
        if (isset($_POST['idImagem'])) {
            // Atualização (pode ter ou não nova imagem)
            $status = $_POST['status'] ?? 'inativa';
            
            if (!empty($_FILES['imagem']['tmp_name'])) {
                // Tem nova imagem - fazer upload
                $uploadDir = 'uploads/carrossel/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $originalName = basename($_FILES['imagem']['name']);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $baseName = substr(pathinfo($originalName, PATHINFO_FILENAME), 0, 100);
                $fileName = uniqid() . '_' . $baseName . '.' . $extension;
                $targetFile = $uploadDir . $fileName;
                
                // Validação básica da imagem
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($imageFileType, $allowedTypes)) {
                    $response['message'] = 'Apenas arquivos JPG, JPEG, PNG e GIF são permitidos';
                } elseif ($_FILES['imagem']['size'] > 5000000) { // 5MB
                    $response['message'] = 'O arquivo é muito grande (máximo 5MB)';
                } elseif (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                    // Primeiro obtemos o nome do arquivo antigo para deletar
                    $stmt = $pdo->prepare("SELECT nomeImagem FROM tb_imagem WHERE idImagem = ?");
                    $stmt->execute([$_POST['idImagem']]);
                    $oldImage = $stmt->fetch();
                    
                    // Atualiza no banco
                    $stmt = $pdo->prepare("UPDATE tb_imagem SET nomeImagem = ?, statusImagem = ? WHERE idImagem = ?");
                    $stmt->execute([$fileName, $status, $_POST['idImagem']]);
                    
                    // Excluir arquivo físico antigo
                    if ($oldImage && file_exists($uploadDir . $oldImage['nomeImagem'])) {
                        unlink($uploadDir . $oldImage['nomeImagem']);
                    }
                    
                    $response['success'] = true;
                } else {
                    $response['message'] = 'Erro ao fazer upload da imagem';
                }
            } else {
                // Atualização sem nova imagem
                $stmt = $pdo->prepare("UPDATE tb_imagem SET statusImagem = ? WHERE idImagem = ?");
                $stmt->execute([$status, $_POST['idImagem']]);
                $response['success'] = true;
            }
        } elseif (!empty($_FILES['imagem']['tmp_name'])) {
            // Nova imagem (inserção)
            $uploadDir = 'uploads/carrossel/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $originalName = basename($_FILES['imagem']['name']);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $baseName = substr(pathinfo($originalName, PATHINFO_FILENAME), 0, 100);
            $fileName = uniqid() . '_' . $baseName . '.' . $extension;
            $targetFile = $uploadDir . $fileName;
            
            // Validação básica da imagem
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($imageFileType, $allowedTypes)) {
                $response['message'] = 'Apenas arquivos JPG, JPEG, PNG e GIF são permitidos';
            } elseif ($_FILES['imagem']['size'] > 5000000) { // 5MB
                $response['message'] = 'O arquivo é muito grande (máximo 5MB)';
            } elseif (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                $status = $_POST['status'] ?? 'inativa';
                
                $stmt = $pdo->prepare("INSERT INTO tb_imagem (nomeImagem, statusImagem) VALUES (?, ?)");
                $stmt->execute([$fileName, $status]);
                
                $response['success'] = true;
            } else {
                $response['message'] = 'Erro ao fazer upload da imagem';
            }
        } else {
            $response['message'] = 'Nenhuma imagem enviada e nenhum ID de imagem fornecido';
        }
    }
} catch (PDOException $e) {
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
}

echo json_encode($response);