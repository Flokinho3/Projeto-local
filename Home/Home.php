<?php
// inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado, se não estiver, redireciona para a página de login
if (!isset($_SESSION['username'])) {
    $_SESSION ['alert'] = "Você não está logado!";
    header("Location: ../index.php");
    exit();
}

include_once '../Server/Porteiro.php';
include_once "../Utilitarios/Alerta.php";

$User = BuscarUsuario($_SESSION['username']);


//debug
echo "<pre>";
print_r($User);
echo "</pre>";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo htmlspecialchars($User['username'] ?? 'Usuário'); ?></title>
    <link rel="stylesheet" href="../CSS/Home.css?=<?php echo time(); ?>">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class = "header">
        <h1>Bem-vindo, <?php echo htmlspecialchars($User['username'] ?? 'Usuário'); ?>!</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="Home.php">Home</a></li>
                <li><a href="Update.php">Atualizaçoes</a></li>
                <li><a href="../Server/Porteiro.php?logout=true">Sair</a></li>
            </ul>
        </nav>
    </div>
    <div class ="Container">
        <p>Email: <?php echo htmlspecialchars($User['email'] ?? 'sem@email.com'); ?></p>
    </div>    
</body>
</html>