<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // inicia a sessão se ainda não estiver iniciada
}

// verifica se tem alguma mensagem na sessão
if (isset($_SESSION['alert'])) {
    // exibe a mensagem
    echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
    // limpa a mensagem da sessão
    unset($_SESSION['alert']);
}
// exempo de uso
// $_SESSION['alert'] = "Mensagem de teste!";
// header("Location: ../index.php"); // Redireciona para a página de login
// exit(); // Encerra o script

?>