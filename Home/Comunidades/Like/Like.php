<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['Logado']) || $_SESSION['Logado'] !== true) {
    echo json_encode(["success" => false, "message" => "Você precisa estar logado."]);
    exit;
}

$comunidadeName = $_POST['comunidade']; // Nome da comunidade enviado via POST
$userID = $_SESSION['ID']; // ID do usuário logado

// Caminho do arquivo da comunidade
$FILE_COMUNIDAES = "../../../Comunidades/";
$comunidadeFile = $FILE_COMUNIDAES . $comunidadeName . ".json";

if (file_exists($comunidadeFile)) {
    $comunidadeData = json_decode(file_get_contents($comunidadeFile), true);

    if ($comunidadeData) {
        // Lógica para verificar se o usuário já deu like nesta comunidade
        $userLikeDir = "../../../Users/" . $userID . "/likes/";

        if (!is_dir($userLikeDir)) {
            mkdir($userLikeDir, 0777, true); // Cria o diretório se não existir
        }

        $userLikeFile = $userLikeDir . $comunidadeName . ".json";

        if (file_exists($userLikeFile)) {
            // Se já tiver dado like, remove o like
            $comunidadeData['likes'] -= 1;
            unlink($userLikeFile);  // Remove o arquivo de like do usuário
        } else {
            // Se não tiver dado like, incrementa o like
            $comunidadeData['likes'] += 1;
            file_put_contents($userLikeFile, json_encode($comunidadeData));  // Cria a cópia no diretório do usuário
        }

        // Atualiza o arquivo original com o novo número de likes
        file_put_contents($comunidadeFile, json_encode($comunidadeData));

        echo json_encode(["success" => true, "likes" => $comunidadeData['likes']]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao ler o arquivo da comunidade."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Comunidade não encontrada."]);
}
?>
