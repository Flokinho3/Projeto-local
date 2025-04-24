<?php

// verifica se a sessão já foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // inicia a sessão se ainda  estiver iniciada
}

/*

baco de dados:  Servidor: 127.0.0.1:3306
Banco de dados: u139216883_Users
Tabela: Users 


# 	Nome 	Tipo 	     	 	 	 	 	 	
1 	ID      Primária 	int(11) 			 	 PAdrao 	  	  	
2 	Nome 	text 	    utf8mb4_unicode_ci 		 	 			  	  	
3 	Senha 	text 	    utf8mb4_unicode_ci 		 	 			  	  	
4 	Email 	text 	    utf8mb4_unicode_ci 		 	 			  	  	
5 	Img 	text 	    utf8mb4_unicode_ci 		 'Base.png' 
6	Ep	    int(11)				                  1
7	Cap	    int(11)				                  1
8	Text	int(11)			                      1			

*/

function conectar() {
    $host = "srv813.hstgr.io"; // ou outro host do seu provedor
    $port = 3306;
    $db   = "u139216883_Users"; // nome do seu banco
    $user = "u139216883_Thiago"; // nome do seu usuário
    $pass = "qpccK?vg7]xr5cy";    // a senha que você configurou

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados remoto: " . $e->getMessage());
    }
}
//Cadastro
function Cadastro($nome, $senha, $email) {
    $senha = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a senha
    // Conecta ao banco de dados
    $conn = conectar();
    // verifica se a conexão foi bem-sucedida
    if (!$conn) {
        $_SESSION['alert'] = "Erro ao conectar ao banco de dados!";
        header("Location: ../index.php"); // Redireciona para a página de login
        exit(); // Encerra o script
    }
    // verifica se o email já existe
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $_SESSION['alert'] = "Email já cadastrado!"; // Define a mensagem de erro
        header("Location: ../index.php"); // Redireciona para a página de login
        exit(); // Encerra o script
    }
    // verifica se o nome já existe
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Nome = :nome");
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $_SESSION['alert'] = "Nome já cadastrado!"; // Define a mensagem de erro
        header("Location: ../index.php"); // Redireciona para a página de login
        exit(); // Encerra o script
    }
    // Insere o novo usuário no banco de dados
    $stmt = $conn->prepare("INSERT INTO Users (Nome, Senha, Email) VALUES (:nome, :senha, :email)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $_SESSION['alert'] = "Cadastro realizado com sucesso!"; // Define a mensagem de sucesso
    header("Location: ../index.php"); // Redireciona para a página de login
    exit(); // Encerra o script
}
//Login
function login($nome, $senha) {
    // Conecta ao banco de dados
    $conn = conectar();
    // verifica se a conexão foi bem-sucedida
    if (!$conn) {
        $_SESSION['alert'] = "Erro ao conectar ao banco de dados!";
        header("Location: ../index.php"); // Redireciona para a página de login
        exit(); // Encerra o script
    }
    // Busca o usuário no banco de dados
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Nome = :nome");
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        // Verifica se a senha está correta
        if (password_verify($senha, $resultado['Senha'])) {
            $_SESSION['User'] = $resultado['Nome']; // Armazena o nome do usuário na sessão
            $_SESSION['alert'] = "Login realizado com sucesso!"; // Define a mensagem de sucesso
            header("Location: ../Home/Home.php"); // Redireciona para a página inicial do usuário
            exit(); // Encerra o script
        } else {
            $_SESSION['alert'] = "Senha incorreta!"; // Define a mensagem de erro
            header("Location: ../index.php"); // Redireciona para a página de login
            exit(); // Encerra o script
        }
    } else {
        $_SESSION['alert'] = "Usuário não encontrado!"; // Define a mensagem de erro
        header("Location: ../index.php"); // Redireciona para a página de login
        exit(); // Encerra o script
    }
}
//Buscar Usuário
function BuscarUsuario($nome) {
    // Conecta ao banco de dados
    $conn = conectar();
    // verifica se a conexão foi bem-sucedida
    if (!$conn) {
        $_SESSION['alert'] = "Erro ao conectar ao banco de dados!";
        header("Location: ../index.php"); // Redireciona para a página de login
        exit(); // Encerra o script
    }
    // Busca o usuário no banco de dados
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Nome = :nome");
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    // remove a senha do resultado
    unset($resultado['Senha']);
    return $resultado; // Retorna os dados do usuário
}
// Substituir Imagem
function SubistituirImagem($FILE) {
    if (is_dir($FILE)) {
        $files = glob($FILE . "/*");
        usort($files, fn($a, $b) => filemtime($a) - filemtime($b));
        if (count($files) > 0) {
            unlink($files[0]);
        }
    }
}
// Adicionar Imagem
function AdicionarImagem($userId, $imageName) {
    $conn = conectar();
    if (!$conn) {
        $_SESSION['alert'] = "Erro ao conectar ao banco de dados!";
        header("Location: ../index.php");
        exit();
    }

    $FILE = "../imagens/" . $userId;

    if (!is_dir($FILE)) {
        mkdir($FILE, 0777, true);
    }

    if (count(glob($FILE . "/*")) >= 3) {
        $_SESSION['alert'] = "Você já tem 3 imagens! A mais antiga será substituída!";
        SubistituirImagem($FILE);
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        $_SESSION['alert'] = "Formato de imagem não permitido! Apenas JPG, JPEG, PNG e GIF são aceitos.";
        header("Location: ../Home/Perfil.php");
        exit();
    }

    $targetPath = $FILE . "/" . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $stmt = $conn->prepare("UPDATE Users SET Img = :img WHERE ID = :id");
        $stmt->bindParam(':img', $imageName);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $_SESSION['alert'] = "Imagem adicionada com sucesso!";
    } else {
        $_SESSION['alert'] = "Erro ao adicionar imagem!";
    }

    $conn = null;
    header("Location: ../Home/Perfil.php");
    exit();
}
// Definir Imagem
function DefinirImagem($userIMG, $userId) {
    if (empty($userIMG) || $userIMG === 'Base.png') {
        return '../imagens/Base.png';
    } else {
        return '../imagens/' . $userId . '/' . $userIMG;
    }
}
// Deletar Imagem
function DeletarImagem($userId, $imagemSelecionada) {
    $conn = conectar();
    if (!$conn) {
        $_SESSION['alert'] = "Erro ao conectar ao banco de dados!";
        header("Location: ../index.php");
        exit();
    }

    $FILE = "../imagens/" . $userId . "/" . $imagemSelecionada;

    if (file_exists($FILE)) {
        unlink($FILE);
        $_SESSION['alert'] = "Imagem removida com sucesso!";
    } else {
        $_SESSION['alert'] = "Imagem não encontrada!";
    }

    $conn = null;
    header("Location: ../Home/Perfil.php");
    exit();
}
// Carrega as informações do personagem do usuario
function Perfil_persona ($userId) {
    $FILE_PERSONA = "../Home/Users/" . $userId . "/Persona.json";
    if (file_exists($FILE_PERSONA)) {
        $json = file_get_contents($FILE_PERSONA);
        $persona = json_decode($json, true);
        return $persona[0]; // Retorna o primeiro personagem
    } else {
        return null; // Retorna null se o arquivo não existir
    }
}

// Carregar Persona
function carregarPersona($userId) {
    $FILE_PERSONA = "../Home/Users/" . $userId . "/Persona.json";
    if (file_exists($FILE_PERSONA)) {
        $json = file_get_contents($FILE_PERSONA);
        $persona = json_decode($json, true);
        return $persona;
    } else {
        return []; // Retorna um array vazio se o arquivo não existir
    }
}

// Salvar Persona
function salvarPersona($userId, $persona) {
    $FILE_PERSONA = "../Home/Users/" . $userId . "/Persona.json";
    $json = json_encode($persona, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if (file_put_contents($FILE_PERSONA, $json) !== false) {
        return true; // Retorna true se o arquivo foi salvo com sucesso
    } else {
        return false; // Retorna false se houve um erro ao salvar o arquivo
    }
}

// Atualizar Persona
function AtualizarPersona($userId, $persona) {
    $FILE_PERSONA = "../Home/Users/" . $userId . "/Persona.json";
    if (file_exists($FILE_PERSONA)) {
        $json = json_encode($persona, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($FILE_PERSONA, $json) !== false) {
            return true; // Retorna true se o arquivo foi salvo com sucesso
        } else {
            return false; // Retorna false se houve um erro ao salvar o arquivo
        }
    } else {
        return false; // Retorna false se o arquivo não existir
    }
}

// Criar Persona
function CriarPersona($userId, $nome, $raca, $classe) {
    // Verifica e cria o diretório, se necessário
    $dirPath = "../Home/Users/" . $userId;
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);  // Cria o diretório se ele não existir
    }

    // Carrega o personagem existente
    $persona = carregarPersona($userId);

    // Adiciona o novo personagem
    $persona[] = [
        'Nome' => $nome,
        'Raca' => $raca,
        'Classe' => $classe,
        'Level' => 1,
        'XP' => 0,
        'Vida' => 100,
        'Mana' => 100,
        'Forca' => 10,
        'Dinheiro' => 0,
        'Inventario' => [],
        'Habilidades' => [],
        'Equipamentos' => [],
        'Status' => [],
        'Ep_' => 1,
        'Cap_' => 1,
        'CriadoEm' => date('Y-m-d H:i:s'),
    ];

    // Salva o personagem no arquivo JSON
    if (!salvarPersona($userId, $persona)) {
        $_SESSION['alert'] = "Erro ao salvar o personagem!";
        header("Location: ../Home/Home.php");
        exit();
    }

    // Mensagem de sucesso
    $_SESSION['alert'] = "Personagem criado com sucesso!";
    $_SESSION['Personagem'] = true;

    // Redireciona para a página inicial do usuário
    header("Location: ../Home/Home.php");
    exit();
}


?>
