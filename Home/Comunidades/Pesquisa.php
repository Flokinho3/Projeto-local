<?php

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['Logado']) || $_SESSION['Logado'] !== true) {
    header('Location: ../../index.php');
    exit;
}

$hour = date('H');
$greeting = $hour < 12 ? "Bom dia" : ($hour < 18 ? "Boa tarde" : "Boa noite");

$FILEINFO = "../../Users/" . $_SESSION['ID'] . "/";
$FILE_COMUNIDAES = "../../Comunidades/";

// Caminho da imagem de perfil
$img = $_SESSION['Img'];
if (empty($img)) {
    $img = '../../Imagens/Padrao.png'; // Caminho da imagem padrão
} else {
    $img = $FILEINFO . $img; // Caminho da imagem do usuário
}

// Randomiza o gradiente ao fundo da página
$gradientes = [
    'linear-gradient(to right, #ff7e5f, #feb47b, #ffcc33)',
    'linear-gradient(to right, #6a11cb, #2575fc, #00d4ff)',
    'linear-gradient(to right, #00c6ff, #0072ff, #1e90ff)',
    'linear-gradient(to right, #ff758c, #ff7eb3, #ff9a9e)',
    'linear-gradient(to right, #00d2ff, #3a6073, #1c92d2)'
];
$gradienteAleatorio = $gradientes[array_rand($gradientes)];
echo "<style>body { background: $gradienteAleatorio; }</style>";

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Alerta.css">
    <link rel="stylesheet" href="../../CSS/Sidebar.css">
    <link rel="stylesheet" href="../../CSS/Pesquisa.css">
    <title>Pesquisa</title>
</head>
<body>
    <div class="Sidebar">
        <h2>Menu</h2>
        <div class="Sidebar-img">
            <img src="<?php echo $img; ?>" alt="Foto do usuário">
        </div>
        <ul>
            <li><a href="../Home.php">Home</a></li>
            <li><a href="../Perfil/Perfil.php">Perfil</a></li>
            <li><a href="#">Configurações</a></li>
            <li><a href="../../Server/Logout.php">Sair</a></li>
        </ul>
    </div>

    <div class="container">
        <?php
        // Lê todos os arquivos de comunidades na pasta Comunidades e ignora pastas
        $comunidades = array_diff(scandir($FILE_COMUNIDAES), array('.', '..'));
        $comunidades = array_filter($comunidades, function($file) use ($FILE_COMUNIDAES) {
            return is_file($FILE_COMUNIDAES . $file);
        });

        if (count($comunidades) > 0) {
            echo '<h1>Comunidades</h1>';
            echo '<div class="cards-container">'; // Inicia o container dos cards
            foreach ($comunidades as $comunidade) {
                // Carrega o conteúdo JSON de cada arquivo de comunidade
                $comunidade_data = json_decode(file_get_contents($FILE_COMUNIDAES . '/' . $comunidade), true);

                // Verifica se o JSON contém os campos esperados
                if ($comunidade_data && isset($comunidade_data['nome'], $comunidade_data['descricao'], $comunidade_data['criador'], $comunidade_data['data_criacao'], $comunidade_data['cor_tema'], $comunidade_data['icone'], $comunidade_data['capa'])) {
                    // Dentro do loop para exibir as comunidades
                    echo '<div class="card" style="border-left: 5px solid ' . htmlspecialchars($comunidade_data['cor_tema']) . ';">';
                    echo '<img src="../../Comunidades/Imagens/' . htmlspecialchars($comunidade_data['capa']) . '" alt="Capa da comunidade" class="card-capa">';
                    echo '<div class="card-content">';
                    echo '<h3>' . htmlspecialchars($comunidade_data['nome']) . '</h3>';
                    echo '<p>' . htmlspecialchars($comunidade_data['descricao']) . '</p>';
                    echo '<small>Criado por: ' . htmlspecialchars($comunidade_data['criador']) . ' em ' . htmlspecialchars($comunidade_data['data_criacao']) . '</small>';
                    echo '<div class="temas">';
                    echo '<h4>Tema:</h4>';
                    echo '<span class="tema" style="color: ' . htmlspecialchars($comunidade_data['cor_tema']) . ';">' . htmlspecialchars($comunidade_data['tema']) . '</span>';
                    echo '</div>';
                    echo '<a href="../Comunidade/Comunidade.php?comunidade_id=' . urlencode($comunidade_data['nome']) . '">Entrar na comunidade</a>';
                    echo '<button class="like-button" onclick="Like(this)" style="background: none; border: none; cursor: pointer;">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">';
                    echo '<path d="M20.8 4.6c-1.5-1.5-3.9-1.5-5.4 0l-.4.4-.4-.4c-1.5-1.5-3.9-1.5-5.4 0-1.5 1.5-1.5 3.9 0 5.4l5.8 5.8 5.8-5.8c1.5-1.5 1.5-3.9 0-5.4z"></path>';
                    echo '</svg>';
                    echo '</button>';
                    echo '<small class="like-count">0</small>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            echo '</div>'; // Fim do container dos cards
        } else {
            echo '<h1>Nenhuma comunidade encontrada.</h1>';
        }
        ?>
    </div>
    <script>
        function Like(button) {
            console.log("Like button clicked!"); // Adiciona um log para depuração
            const card = button.closest('.card');
            const nomeComunidade = card.querySelector('h3').innerText;
            const likeCount = card.querySelector('.like-count');

            // Envia a requisição AJAX para o servidor
            const xhttp = new XMLHttpRequest();
            xhttp.open("POST", "Like/Like.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function () {
                console.log(this.responseText);
                if (this.readyState == 4 && this.status == 200) {
                    // Atualiza a quantidade de likes na página
                    let response = JSON.parse(this.responseText);
                    if (response.success) {
                        likeCount.innerText = response.likes;
                        button.style.color = "red"; // Altera a cor do botão para vermelho
                    }
                }
            };
            xhttp.send("comunidade=" + encodeURIComponent(nomeComunidade));
        }
    </script>

    <script src="../../JS/Alerta.js"></script>
    <script src="../../JS/Sidebar.js"></script>    
</body>
</html>
