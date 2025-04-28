<?php
if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    $input = $_POST;
}

file_put_contents('log.txt', print_r($_POST, true), FILE_APPEND);
include_once 'funcoes.php';
header('Content-Type: application/json; charset=utf-8');
session_start();

// mostra no console o que está sendo enviado
file_put_contents('log.txt', print_r($input, true), FILE_APPEND);

// Verifica se a ação foi enviada corretamente
if (empty($input) || !isset($input['action'])) {
    echo json_encode(['status' => 'erro', 'mensagens' => ['Ação inválida']]);
    exit;
}

try {
    switch ($input['action']) {
        case 'cadastro':
            // Lógica para cadastro
            $erros = [];
    
            if (empty($input['nome'])) {
                $erros[] = 'O nome é obrigatório.';
            }
    
            if (empty($input['email'])) {
                $erros[] = 'O email é obrigatório.';
            } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $erros[] = 'Formato de email inválido.';
            }
    
            if (empty($input['senha'])) {
                $erros[] = 'A senha é obrigatória.';
            } elseif (strlen($input['senha']) < 6) {
                $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
            }
    
            if (!empty($erros)) {
                echo json_encode(['status' => 'erro', 'mensagens' => $erros]);
                exit;
            }

            $resposta = cadastrarUsuario($input['nome'], $input['email'], $input['senha']);
            echo json_encode($resposta);
            break;

        case 'login':
            $erros = [];

            if (empty($input['email'])) {
                $erros[] = 'O email é obrigatório.';
            }

            if (empty($input['senha'])) {
                $erros[] = 'A senha é obrigatória.';
            }

            if (!empty($erros)) {
                echo json_encode(['status' => 'erro', 'mensagens' => $erros]);
                exit;
            }

            $resposta = loginUsuario($input['email'], $input['senha']);
            echo json_encode($resposta);
            break;

        case 'trocaImg':
            // Verifica se o arquivo foi enviado e não houve erro no upload
            if (!isset($_FILES['img']) || $_FILES['img']['error'] !== 0) {
                echo json_encode(['status' => 'erro', 'mensagens' => ['Erro ao fazer upload da imagem.']]);
                exit;
            }

            // Verifica se o arquivo é uma imagem válida
            $imgType = $_FILES['img']['type'];
            $validTypes = ['image/jpeg', 'image/png', 'image/gif'];  // Tipos de imagens permitidos
            if (!in_array($imgType, $validTypes)) {
                echo json_encode(['status' => 'erro', 'mensagens' => ['Tipo de arquivo inválido. Apenas imagens JPG, PNG e GIF são permitidas.']]);
                exit;
            }

            // Cria um nome único para a imagem
            $fileName = uniqid('img_', true) . '.' . pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            
            // Define o diretório onde as imagens serão armazenadas
            $uploadDir = '../Users/' . $_SESSION['ID'] . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);  // Cria o diretório caso não exista
            }

            // verifica se tem alguma imagem dentro do diretório
            $files = glob($uploadDir . '*'); // Pega todos os arquivos do diretório
            foreach ($files as $file) { // Loop pelos arquivos encontrados
                if (is_file($file)) { // Verifica se é um arquivo
                    unlink($file); // Deleta o arquivo
                }
            }

            // Caminho completo do arquivo a ser salvo
            $uploadFile = $uploadDir . $fileName;

            atualizarImg($fileName, $_SESSION['ID']);
            // Move o arquivo para o diretório de upload
            if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadFile)) {
                // Atualiza a imagem do usuário na sessão
                $_SESSION['Img'] = $fileName;
                echo json_encode(['status' => 'ok', 'mensagem' => 'Imagem atualizada com sucesso!', 'imagem' => $fileName]);

            } else {
                echo json_encode(['status' => 'erro', 'mensagens' => ['Falha ao mover o arquivo para o diretório.']]);
            }
            break;

        case 'novaComunidade':
            $erros = [];

            if (empty($input['nome'])) {
                $erros[] = 'O nome da comunidade é obrigatório.';
            }

            if (empty($input['descricao'])) {
                $erros[] = 'A descrição da comunidade é obrigatória.';
            }

            if (empty($input['Cor_tema'])) {
                $erros[] = 'A cor do tema é obrigatória.';
            }

            if (empty($input['tema'])) {
                $erros[] = 'O tema da comunidade é obrigatório.';
            }

            if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== 0) {
                $erros[] = 'Erro ao fazer upload da imagem do ícone.';
            }

            if (!isset($_FILES['capa']) || $_FILES['capa']['error'] !== 0) {
                $erros[] = 'Erro ao fazer upload da imagem de capa.';
            }

            if (!empty($erros)) {
                echo json_encode(['status' => 'erro', 'mensagens' => $erros]);
                exit;
            }

            $resposta = criarComunidade($input, $_SESSION['ID']);
            echo json_encode($resposta);
            break;
        default:
            echo json_encode(['status' => 'erro', 'mensagens' => ['Ação desconhecida']]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagens' => ['Erro interno no servidor', $e->getMessage()]]);
}
?>
