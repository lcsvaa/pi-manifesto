<?php
session_start();

// Verifica se é admin
if (isset($_SESSION['is_admin'])) {
    return; // Permite acesso para admin
}

// Verifica se é usuário comum
if (isset($_SESSION['user_id'])) {
    return; // Permite acesso para usuário comum
}

// Se não for nenhum dos dois, redireciona para login
session_unset();
session_destroy();
header('Location: login.php?error=acesso_negado');
exit();
?>