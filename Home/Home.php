<?php
// inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado, se não estiver, redireciona para a página de login
if (!isset($_SESSION['User'])) {
    $_SESSION ['alert'] = "Você não está logado!";
    header("Location: ../index.php");
    exit();
}

include_once '../Server/Porteiro.php';
include_once "../Utilitarios/Alerta.php";


$User = BuscarUsuario($_SESSION['User']);
$User_img = DefinirImagem($User['Img'] , $User["ID"]); // Define a imagem padrão caso não exista


echo "<pre>";
print_r($User);
echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo htmlspecialchars($User['Nome'] ?? 'Usuário'); ?></title>
    <link rel="stylesheet" href="../CSS/Home.css?=<?php echo time(); ?>">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class = "header">
        <h1>Bem-vindo, <?php echo htmlspecialchars($User['Nome'] ?? 'Usuário'); ?>!</h1>
        <img src="<?php echo htmlspecialchars($User_img); ?>" alt="Imagem de perfil" class="profile-img" style="width: 50px; height: 50px; border-radius: 50%;">
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="Home.php">Home</a></li>
                <li><a href="Perfil.php">Perfil</a></li>
                <li><a href="Update.php">Atualizaçoes</a></li>
                <li><a href="../Server/Porteiro.php?logout=true">Sair</a></li>
            </ul>
        </nav>
    </div>
    <div class ="Container">
        <p>Email: <?php echo htmlspecialchars($User['Email'] ?? 'sem@email.com'); ?></p>
    </div>    
</body>
</html>