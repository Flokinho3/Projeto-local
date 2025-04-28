<?php
/*

	#	Nome	Tipo	    Colação	Atributos	    Nulo	Padrão	        Extra	
	1	Nome	text	    utf8mb4_unicode_ci		Não	    Nenhum				
	2	Senha	text	    utf8mb4_unicode_ci		Não	    Nenhum				
	3	Email	text	    utf8mb4_unicode_ci		Não	    Nenhum				
	4	Img	    text	    utf8mb4_unicode_ci		Não	    'Padrao.png'				
	5	ID      Primária	int(11)			        Não	    Nenhum		    AUTO_INCREMENT	

*/

include_once 'Serve.php';

function cadastrarUsuario($nome, $email, $senha) {
    $conn = conectar();

    $nome = mysqli_real_escape_string($conn, $nome);
    $email = mysqli_real_escape_string($conn, $email);
    $senhaOriginal = $senha; // Guarda a senha original para checagem extra se quiser
    $senha = mysqli_real_escape_string($conn, $senha);
    $senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT); // Criptografa a senha
    $img = 'Padrao.png';

    $erros = [];

    // Validação extra dentro da função
    if (empty($nome)) {
        $erros[] = 'O nome é obrigatório.';
    }

    if (empty($email)) {
        $erros[] = 'O email é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Formato de email inválido.';
    }

    if (empty($senhaOriginal)) {
        $erros[] = 'A senha é obrigatória.';
    } elseif (strlen($senhaOriginal) < 6) {
        $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
    }

    // Se houver erros, já retorna
    if (!empty($erros)) {
        return ['status' => 'erro', 'mensagens' => $erros];
    }

    // Verifica se o email já existe
    $check = mysqli_query($conn, "SELECT ID FROM User WHERE Email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        return ['status' => 'erro', 'mensagens' => ['Email já cadastrado.']];
    }

    $query = "INSERT INTO User (Nome, Email, Senha, Img) VALUES ('$nome', '$email', '$senhaCriptografada', '$img')";
    if (mysqli_query($conn, $query)) {
        return ['status' => 'ok', 'mensagens' => ['Cadastro realizado com sucesso!']];
    } else {
        return ['status' => 'erro', 'mensagens' => ['Erro ao cadastrar usuário.']];
    }
}

function loginUsuario($email, $senha) {
    $conn = conectar();

    $email = mysqli_real_escape_string($conn, $email);
    $senha = mysqli_real_escape_string($conn, $senha);

    $erros = [];

    if (empty($email)) {
        $erros[] = 'O email é obrigatório.';
    }

    if (empty($senha)) {
        $erros[] = 'A senha é obrigatória.';
    }

    if (!empty($erros)) {
        return ['status' => 'erro', 'mensagens' => $erros];
    }

    // Procura o usuário
    $query = "SELECT * FROM User WHERE Email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verifica a senha
        if (password_verify($senha, $user['Senha'])) {
            $_SESSION['ID'] = $user['ID'];
            $_SESSION['Nome'] = $user['Nome'];
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['Img'] = $user['Img'];
            $_SESSION['Logado'] = true;

            return ['status' => 'ok', 'mensagens' => ['Login realizado com sucesso!']];
        } else {
            return ['status' => 'erro', 'mensagens' => ['Senha incorreta.']];
        }
    } else {
        return ['status' => 'erro', 'mensagens' => ['Email não encontrado.']];
    }
}

function atualizarImg($id, $img) {
    $conn = conectar();

    $id = mysqli_real_escape_string($conn, $id);
    $img = mysqli_real_escape_string($conn, $img);

    $query = "UPDATE User SET Img = '$img' WHERE ID = '$id'";
    if (mysqli_query($conn, $query)) {
        return ['status' => 'ok', 'mensagens' => ['Imagem atualizada com sucesso!']];
    } else {
        return ['status' => 'erro', 'mensagens' => ['Erro ao atualizar imagem.']];
    }
}

function Verificar_Img($file) {
    $extensoes = ['jpg', 'jpeg', 'png', 'gif'];
    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoes)) {
        return false;
    }

    // Verifica tipo MIME real
    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
        return false;
    }
    /*
    // Verifica tamanho
    if ($file['size'] > 2 * 1024 * 1024) { // 2MB
        return false;
    }
    */
    // Nomeia arquivo de forma única
    $nomeAleatorio = uniqid('img_', true) . '.' . $extensao;
    return $nomeAleatorio;
}


function criarComunidade($informacoes, $idUsuario) {
    $conn = conectar();

    $nome = mysqli_real_escape_string($conn, $informacoes['nome']);
    $descricao = mysqli_real_escape_string($conn, $informacoes['descricao']);
    $corTema = mysqli_real_escape_string($conn, $informacoes['Cor_tema']);
    $tema = mysqli_real_escape_string($conn, $informacoes['tema']);

    // Trabalhar com uploads
    $icone = Verificar_Img($_FILES['imagem']);
    $capa = Verificar_Img($_FILES['capa']);

    if ($icone === false || $capa === false) {
        return ['status' => 'erro', 'mensagens' => ['Imagens inválidas.']];
    }

    $pasta = '../Comunidades/Imagens/';
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $icone);
    move_uploaded_file($_FILES['capa']['tmp_name'], $pasta . $capa);

    // Agora insere também o nome dos arquivos no banco
    $query = "INSERT INTO Comunidades (Nome, Descricao, Cor_tema, Tema, ID_Criador, Icone, Capa) 
              VALUES ('$nome', '$descricao', '$corTema', '$tema', '$idUsuario', '$icone', '$capa')";

    //criaçao do json para visualizaçao
    $comunidade = [
        'nome' => $nome,
        'descricao' => $descricao,
        'cor_tema' => $corTema,
        'tema' => $tema,
        'icone' => $icone,
        'capa' => $capa,
        'criador' => $idUsuario,
        'data_criacao' => date('Y-m-d H:i:s'),
        'likes' => 0
    ];
    $json = json_encode($comunidade, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $caminho = '../Comunidades/' . $nome . '.json';
    if (!file_exists($caminho)) {
        file_put_contents($caminho, $json);
    } else {
        return ['status' => 'erro', 'mensagens' => ['Comunidade já existe.']];
    }

    if (mysqli_query($conn, $query)) {
        return ['status' => 'ok', 'mensagens' => ['Comunidade criada com sucesso!']];
    } else {
        return ['status' => 'erro', 'mensagens' => ['Erro ao criar comunidade.']];
    }
}

?>
