<?php

// verifica se o usuario está logado
session_start();
if (!isset($_SESSION['Logado']) || $_SESSION['Logado'] !== true) {
    header('Location: ../../index.php');
    exit;
}

$img = $_SESSION['Img'];
if (empty($img) || $img === 'Padrao.png') {
    $img = '../../Imagens/Padrao.png'; // Caminho da imagem padrão
} else {
    $img = '../../Users/' . $_SESSION['ID'] . '/' . $img; // Caminho da imagem do usuário
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
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?php echo htmlspecialchars($_SESSION["Nome"], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="../../CSS/Alerta.css">
    <link rel="stylesheet" href="../../CSS/Perfil.css">
    <link rel="stylesheet" href="../../CSS/Sidebar.css">
</head>
<body>
    <!-- Botão para abrir/fechar Sidebar (apenas visível em dispositivos móveis) -->
    <span class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</span>

    <div class="Sidebar">
        <h2>Menu</h2>
        <div class="Sidebar-img">
            <img src="<?php echo $img; ?>" alt="Foto do usuário">
        </div>
        <ul>
            <li><a href="../Home.php">Home</a></li>
            <li><a href="Perfil.php">Perfil</a></li>
            <li><a href="#">Configurações</a></li>
            <li><a href="../../Server/Logout.php">Sair</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Bem-vindo, <?php echo $_SESSION['Nome']; ?>!</h1>
        <img src="<?php echo $img; ?>" alt="Foto do usuário" class="perfil-img">
        
        <div class="User"></div>
            <h2>Informações do Usuário</h2>
            <p><strong>Nome:  </strong> <?php echo htmlspecialchars($_SESSION['Nome'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Email: </strong> <?php echo htmlspecialchars($_SESSION['Email'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>ID:    </strong> <?php echo htmlspecialchars($_SESSION['ID'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="Img_User">
            <h2>Trocar Imagem</h2>
            <form id="troca_img" enctype="multipart/form-data">
                <input type="file" name="img" id="img-upload" />
                <button type="button" onclick="submitImage()">Enviar</button>
            </form>
        </div>
    </div>
    <script>
        function submitImage() {
            const imgInput = document.getElementById('img-upload');
            
            // Verifica se o usuário selecionou um arquivo
            if (imgInput.files.length === 0) {
                alert('Por favor, selecione uma imagem para enviar.');
                return;
            }

            const formData = new FormData();
            formData.append('img', imgInput.files[0]);
            formData.append('action', 'trocaImg');

            // Exibe o overlay de carregamento
            showLoading('Enviando imagem...');

            // Envia a requisição via Fetch API
            fetch('../../Server/Porteiro.php', {
                method: 'POST',
                body: formData // FormData vai cuidar do Content-Type automaticamente
            })
            .then(response => response.json())
            .then(data => {
                hideLoading(); // Esconde o carregamento

                if (data.status === 'ok') {
                    alert(data.mensagem || 'Imagem atualizada com sucesso!');
                    // Atualiza a imagem na página
                    const newImage = '../../Imagens_user/' + data.imagem;  // Ajuste conforme a resposta do servidor
                    document.querySelector('.perfil-img').src = newImage;
                } else {
                    // Caso o servidor retorne erro, exibe as mensagens
                    if (Array.isArray(data.mensagens)) {
                        data.mensagens.forEach(msg => alert(msg));
                    } else {
                        alert(data.mensagem || 'Erro ao atualizar imagem.');
                    }
                }
            })
            .catch(error => {
                hideLoading(); // Esconde o carregamento mesmo em caso de erro
                console.error('Erro:', error);
                alert('Erro de conexão com o servidor.');
            });
        }
        // Função para alternar o Sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.Sidebar');
            sidebar.classList.toggle('active');
        }

    </script>

    
    <script src="../../JS/Alerta.js"></script>
    <script src="../../JS/Sidebar.js"></script>  
    <script src="../../JS/Requerimentos.js"></script>    
</body>
</html>
