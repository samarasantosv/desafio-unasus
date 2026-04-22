<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a variável de sessão que você criou no login existe
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para o loout  e não para o login, para evitar loops de redirecionamento
    header('Location: logout.php');
    exit();
}
?>