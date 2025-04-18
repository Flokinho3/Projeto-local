<?php
$DATA_ATUAL = date('Y-m-d');

// Caminho do arquivo JSON
$FILE_UPDATE = "Update/Update.json";

// Verifica se o arquivo existe
if (!file_exists($FILE_UPDATE)) {
    die("Arquivo JSON não encontrado.");
}

// Lê o conteúdo do arquivo
$data = file_get_contents($FILE_UPDATE);

// Decodifica os dados JSON
$jsonData = json_decode($data, true);

// Verifica se houve erro na decodificação
if ($jsonData === null) {
    die("Erro ao decodificar o JSON: " . json_last_error_msg());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto - <?php echo $jsonData['vercao']; ?></title>
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(ellipse at bottom, #0d0d1a 0%, #000000 100%), 
                        url('https://img.freepik.com/free-photo/glowing-sky-sphere-orbits-starry-galaxy-generated-by-ai_188544-15599.jpg?t=st=1744985761~exp=1744989361~hmac=a04f8a5fc8b756e9884f22e0e2752933315fc9d2d1cc5a40088d21ac7f935d33&w=996') no-repeat center center fixed;
            background-size: cover;
            color: #00ffff;
            margin: 0;
            padding: 40px;
        }

        h1, h2, h3 {
            text-shadow: 0 0 5px #00ffff;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 26px;
            margin-top: 30px;
        }

        h3 {
            font-size: 20px;
            color: #ff00ff;
            margin-top: 20px;
        }

        p, li {
            font-size: 16px;
            color: #ffffff;
        }

        ul {
            list-style-type: square;
            padding-left: 20px;
        }

        li {
            margin: 8px 0;
            text-shadow: 0 0 3px #00ffff70;
        }

        ::selection {
            background-color: #00ffff44;
            color: #fff;
        }

        .header {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 36px;
            color: #00ffff;
        }

        .header nav {
            margin-top: 10px;
        }

        .header nav ul {
            list-style-type: none;
            padding: 0;
        }

        .header nav ul li {
            display: inline;
            margin: 0 15px;
        }

        .header nav ul li a {
            color: #00ffff;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s;
        }

        .header nav ul li a:hover {
            color: #ff00ff;
        }
    </style>
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
    <h1>Projeto - <?php echo $jsonData['vercao']; ?></h1>
    <p><strong>Descrição:</strong> <?php echo $jsonData['descricao']; ?></p>

    <h2>Detalhes:</h2>
    <?php foreach ($jsonData['detalhes'] as $detalhe): ?>
        <h3>Status: <?php echo $detalhe['status']; ?></h3>
        <ul>
            <?php foreach ($detalhe['descricao'] as $descricao): ?>
                <li><?php echo $descricao; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</body>
</html>
