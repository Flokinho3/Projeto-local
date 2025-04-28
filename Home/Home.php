<?php

// verifica se o usuario está logado
session_start();
if (!isset($_SESSION['Logado']) || $_SESSION['Logado'] !== true) {
    header('Location: ../index.php');
    exit;
}

$hour = date('H');
$greeting = $hour < 12 ? "Bom dia" : ($hour < 18 ? "Boa tarde" : "Boa noite");


$img = $_SESSION['Img'];
if (empty($img)) {
    $img = '../Imagens/Padrao.png'; // Caminho da imagem padrão
} else {
    $img = '../Imagens_user/' . $_SESSION['ID'] . '/' . $img; // Caminho da imagem do usuário
}

// randomiza o gradiente ao fundo da pagina
$gradientes = [
    'linear-gradient(to right, #ff7e5f, #feb47b)',
    'linear-gradient(to right, #6a11cb, #2575fc)',
    'linear-gradient(to right, #00c6ff, #0072ff)',
    'linear-gradient(to right, #ff758c, #ff7eb3)',
    'linear-gradient(to right, #00d2ff, #3a6073)'
];
$gradienteAleatorio = $gradientes[array_rand($gradientes)];
echo "<style>body { background: $gradienteAleatorio; }</style>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Alerta.css">
    <link rel="stylesheet" href="../CSS/Home.css">
    <link rel="stylesheet" href="../CSS/Sidebar.css">
    <title><?php echo htmlspecialchars($_SESSION["Nome"], ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <div class="Sidebar">
        <h2>Menu</h2>
        <div class="Sidebar-img">
            <img src="<?php echo $img; ?>" alt="Foto do usuário">
        </div>
        <ul>
            <li><a href="Home.php">Home</a></li>
            <li><a href="Perfil/Perfil.php">Perfil</a></li>
            <li><a href="#">Configurações</a></li>
            <li><a href="../Server/Logout.php">Sair</a></li>
        </ul>
    </div>
    <div class="container">
        <h1><?php echo $greeting . ", " . $_SESSION['Nome']; ?></h1>
        <div class="Favoritos_Comunidades">
            <div class="Favoritos">
                <h2>Favoritos</h2>
                <ul id="favoritos-lista">
                    <!-- Lista de favoritos será preenchida aqui via JavaScript -->
                </ul>
            </div>
            <div class="Comunidades">
                <h2>Comunidades</h2>
                <ul id="comunidades-lista">
                    <!-- Lista de comunidades será preenchida aqui via JavaScript -->
                </ul>
            </div>
    </div>
    
    <script src="../JS/Alerta.js"></script>
    <script src="../JS/Sidebar.js"></script>    
</body>
</html>