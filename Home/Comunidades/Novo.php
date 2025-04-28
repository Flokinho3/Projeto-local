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

// Caminho da imagem de perfil
$img = empty($_SESSION['Img']) ? '../../Imagens/Padrao.png' : $FILEINFO . $_SESSION['Img'];

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
    <link rel="stylesheet" href="../../CSS/NovaComunidade.css">
    <link rel="stylesheet" href="../../CSS/Sidebar.css">
    <title>Nova Comunidade</title>
</head>
<body>
    <div class="Sidebar">
        <h2>Menu</h2>
        <div class="Sidebar-img">
            <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="Foto do usuário">
        </div>
        <ul>
            <li><a href="../Home.php">Home</a></li>
            <li><a href="../Perfil/Perfil.php">Perfil</a></li>
            <li><a href="#">Configurações</a></li>
            <li><a href="../Server/Logout.php">Sair</a></li>
        </ul>
    </div>

    <div class="container">
        <h1><?php echo htmlspecialchars($greeting, ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($_SESSION["Nome"], ENT_QUOTES, 'UTF-8'); ?></h1>
        <form id="nova-comunidade-form" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome da Comunidade:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required></textarea>

            <label for="imagem">Ícone de exibição:</label>
            <input type="file" id="imagem" name="imagem" accept=".jpg, .jpeg, .png" required>

            <label for="cor_tema">Tema da comunidade:</label>
            <input type="color" id="Cor_tema" name="Cor_tema" value="#000000" required>

            <label for="capa">Capa de apresentação:</label>
            <input type="file" id="capa" name="capa" accept=".jpg, .jpeg, .png" required>

            <label for="temas">Tema da comunidade:</label>
            <select name="temas" id="tema" required>
                <option value="Anime">Anime</option>
                <option value="Games">Games</option>
                <option value="Tecnologia">Tecnologia</option>
                <option value="Música">Música</option>
                <option value="Arte">Arte</option>
                <option value="Esportes">Esportes</option>
                <option value="Literatura">Literatura</option>
                <option value="Ciência">Ciência</option>
                <option value="Cinema">Cinema</option>
                <option value="Outro">Outro</option>
            </select>
            <input type="text" id="outro-tema" name="outro_tema" placeholder="Especifique o tema" style="display: none;">

            <button type="button" onclick="Envia()">Criar Comunidade</button>
        </form>
    </div>
    <script>
        // Exibe o campo de texto se o tema "Outro" for selecionado
        const temaSelect = document.getElementById('tema');
        const outroTemaInput = document.getElementById('outro-tema');

        temaSelect.addEventListener('change', function () {
            outroTemaInput.style.display = this.value === 'Outro' ? 'block' : 'none';
        });

        function Envia() {
            // Validações
            const nome = document.getElementById('nome').value.trim();
            const descricao = document.getElementById('descricao').value.trim();
            const corTema = document.getElementById('Cor_tema').value;
            const tema = document.getElementById('tema').value;
            const capa = document.getElementById('capa').files;
            const imagem = document.getElementById('imagem').files;

            if (!nome) return alert('Por favor, preencha o nome da comunidade.');
            if (!descricao) return alert('Por favor, preencha a descrição da comunidade.');
            if (!corTema) return alert('Por favor, selecione uma cor para o tema da comunidade.');
            if (!tema) return alert('Por favor, selecione um tema para a comunidade.');
            if (capa.length === 0) return alert('Por favor, selecione uma imagem para a capa da comunidade.');
            if (imagem.length === 0) return alert('Por favor, selecione uma imagem para o ícone da comunidade.');

            // Cria o FormData
            const formData = new FormData();
            formData.append('nome', nome);
            formData.append('descricao', descricao);
            formData.append('Cor_tema', corTema);
            formData.append('imagem', imagem[0]);
            formData.append('capa', capa[0]);
            formData.append('tema', tema);
            if (tema === 'Outro') {
                const outroTema = outroTemaInput.value.trim();
                if (!outroTema) return alert('Por favor, especifique o tema.');
                formData.append('outro_tema', outroTema);
            }
            formData.append('action', 'novaComunidade');

            // Exibe o overlay de carregamento
            showLoading('Enviando informações...');

            // Envia a requisição via Fetch API
            fetch('../../Server/Porteiro.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'ok') {
                        alert(data.mensagem || 'Comunidade criada com sucesso!');
                        window.location.href = 'Pesquisa.php';
                    } else {
                        const mensagens = data.mensagens || [data.mensagem || 'Erro ao criar comunidade.'];
                        mensagens.forEach(msg => alert(msg));
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Erro:', error);
                    alert('Erro de conexão com o servidor.');
                });
        }
    </script>
    <script src="../../JS/Alerta.js"></script>
    <script src="../../JS/Sidebar.js"></script>
</body>
</html>