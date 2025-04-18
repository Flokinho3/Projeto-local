<?php

include_once 'Server.php'; // Include the database connection file

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // inicia a sessão se ainda não estiver iniciada
}

if (isset($_POST['login'])) {
    // Processa login
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (Login($username, $password)) {
        $_SESSION['alert'] = "Login realizado com sucesso!";
        header ("Location: ../Home/Home.php"); // Redireciona para a página inicial
    } else {
        $_SESSION['alert'] = "Usuário ou senha incorretos!";
        header ("Location: ../index.php"); // Redireciona para a página de login
    }
} elseif (isset($_POST['register'])) {
    // Processa registro
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    if (Cadastro($username, $password, $email)) {
        $_SESSION['alert'] = "Usuário cadastrado com sucesso!";
        header ("Location: ../Home/Home.php"); // Redireciona para a página de login
    } else {
        $_SESSION['alert'] = "Erro ao cadastrar usuário!";
        header ("Location: ../index.php"); // Redireciona para a página de registro
    }
} elseif (isset($_POST['buscar_usuario'])) {
    $username = $_POST['username'] ?? '';

    if (empty($username)) {
        $_SESSION['alert'] = "Nome de usuário vazio!";
        header("Location: ../index.php");
        exit();
    }

    $user = BuscarUsuario($username);

    return $user;
}

// verifica se no link tem o logout=true
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy(); // Destroi a sessão
    header("Location: ../index.php"); // Redireciona para a página de login
    exit();
}

?>