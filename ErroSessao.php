<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sessão Expirada</title>
    <link rel="stylesheet" href="CSS/Erro.css"> <!-- estilo opcional -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            padding: 40px;
            text-align: center;
        }
        .card {
            background-color: #fff;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        a {
            color: #721c24;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            background-color: #f5c6cb;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>😕 Sessão inválida ou expirada</h2>
        <p>Sua sessão está corrompida ou você foi desconectado.</p>
        <form method="post">
            <button type="submit" name="clear_session" class="btn">Limpar Sessão e Voltar ao Login</button>
        </form>
    </div>

    <?php
    if (isset($_POST['clear_session'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    ?>
</body>
</html>