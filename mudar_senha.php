<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];
    
    try {
        $stmt = $pdo->prepare("SELECT senha FROM tb_usuario WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            throw new Exception("Usuário não encontrado");
        }
        
        if (password_verify($current_password, $user['senha'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE tb_usuario SET senha = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
                
                $_SESSION['success_message'] = "Senha alterada com sucesso!";
            } else {
                $_SESSION['error_message'] = "As senhas não coincidem!";
            }
        } else {
            $_SESSION['error_message'] = "Senha atual incorreta!";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro ao atualizar senha: " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
    
    header('Location: profile.php#security');
    exit();
}
?>