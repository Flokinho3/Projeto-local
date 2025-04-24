<?php


// inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['User'])) {
    header("Location: ../ErroSessao.php");
    exit;
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
    <div class="Persona">
        <?php
        $persona = Perfil_persona($User["ID"]);
        if ($persona) {
            // Card flip: Frente com nome, nível e imagem, verso com as demais informações
            echo '<div class="card">';
            echo '  <div class="card-inner">';
            // Frente do card
            echo '    <div class="card-front">';
            echo '      <h3>' . htmlspecialchars($persona['Nome']) . '</h3>';
            echo '      <p>Level: ' . htmlspecialchars($persona['Level']) . '</p>';
            echo '      <img src="' . htmlspecialchars($User_img) . '" alt="Imagem de perfil" class="profile-img">';
            echo '    </div>';
            // Verso do card
            echo '    <div class="card-back">';
            echo '      <p><strong>Raça:</strong> ' . htmlspecialchars($persona['Raca']) . '</p>';
            echo '      <p><strong>Classe:</strong> ' . htmlspecialchars($persona['Classe']) . '</p>';
            echo '      <p><strong>XP:</strong> ' . htmlspecialchars($persona['XP']) . '</p>';
            echo '      <p><strong>Vida:</strong> ' . htmlspecialchars($persona['Vida']) . '</p>';
            echo '      <p><strong>Dinheiro:</strong> ' . htmlspecialchars($persona['Dinheiro']) . '</p>';
            echo '      <p><strong>Status:</strong> ' . htmlspecialchars(implode(", ", $persona['Status'])) . '</p>';
            echo '      <p><strong>Criado em:</strong> ' . htmlspecialchars($persona['CriadoEm']) . '</p>';
            echo '      <br>';
            $link = 'Game/main.php?capitulo=' . urlencode($User['Cap']) . 
                    '&episodio=' . urlencode($User['Ep']) . 
                    '&text=' . urlencode($User['Text']);
            
            echo        "<a href='$link'>Coninuar!</a>";
    
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        } else {
            echo '<a href="Personagem/Novo.php">Criar Novo Personagem</a>';
        }
        ?>
    </div>  
</body>
</html>