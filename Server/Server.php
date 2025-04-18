<?php

function conectar() {
    $host = "dpg-d01662s9c44c73cu5srg-a.oregon-postgres.render.com";
    $port = 5432; // <- AQUI TAVA FALTANDO
    $db   = "yuno_24wb";
    $user = "yuno_24wb_user";
    $pass = "5CkiZMZVgL66NvRVeYebHDzREdPHAZWf";

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // verifica se a tabela existe
        $query = $conn->query("SELECT to_regclass('public.users') AS table_exists;");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result['table_exists'] === null) {
            // A tabela não existe, então crie-a
            $conn->exec("CREATE TABLE users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE
            )");
        } else {
        }
        return $conn; // Importante retornar a conexão
    } catch (PDOException $e) {
        return null;
    }
}

function Cadastro($username, $password, $email) {
    $conn = conectar(); // Conecta ao banco de dados
    if (!$conn) {
        return false; // Retorna falso se não conseguir conectar
    }
    // Verifica se já existe usuário com esse nome (consultando no banco)
    $query = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $query->execute([':username' => $username]);
    $existingUser = $query->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        return false; // Username já em uso
    }

    // Fazendo o hash da senha
    $senha = password_hash($password, PASSWORD_DEFAULT);

    // Prepara o comando SQL para inserir o novo usuário (sem o campo `id`)
    $query = $conn->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");

    // Executa a inserção
    $result = $query->execute([
        ':username' => $username,
        ':password' => $senha,
        ':email' => $email
    ]);

    return $result; // Retorna true se inserção foi bem-sucedida, false caso contrário
}


function Login($username, $password) {
    $conn = conectar(); // Conecta ao banco de dados
    if (!$conn) {
        return false; // Retorna falso se não conseguir conectar
    }
    // Prepara a consulta para verificar se o usuário existe
    $query = $conn->prepare("SELECT id, username, password, email FROM users WHERE username = :username");
    $query->execute([':username' => $username]);

    // Busca o usuário no banco de dados
    $userData = $query->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário foi encontrado e se a senha está correta
    if ($userData && password_verify($password, $userData['password'])) {
        // Salva as informações do usuário na sessão
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['id'] = $userData['id'];
        $_SESSION['alert'] = "Login realizado com sucesso!";
        return true;
    } else {
        return false; // Usuário não encontrado ou senha incorreta
    }
}

function BuscarUsuario($username) {
    $conn = conectar(); // Conecta ao banco de dados
    if (!$conn) {
        return null; // Retorna nulo se não conseguir conectar
    }
    // Prepara a consulta para buscar o usuário no banco de dados
    $query = $conn->prepare("SELECT id, username, email FROM users WHERE username = :username");
    $query->execute([':username' => $username]);

    // Busca os dados do usuário
    $dados = $query->fetch(PDO::FETCH_ASSOC);

    if (!$dados) {
        return null; // Se o usuário não for encontrado
    }

    unset($dados['password']); // Nunca manda a senha para fora
    return $dados; // Retorna os dados do usuário
}


?>
