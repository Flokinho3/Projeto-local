<?php

include_once 'Server.php'; // Inclui o arquivo de conexão e funções

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    login($username, $password);

}
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    Cadastro($username, $password, $email);

}
if (isset($_POST['buscar_usuario'])) {
    $username = $_POST['username'] ?? '';
    if (empty($username)) {
        $_SESSION['alert'] = "Nome de usuário vazio!";
        header("Location: ../index.php");
        exit();
    }
    $user = BuscarUsuario($username);
    return $user;

} 
if (isset($_POST['adicionar_imagem'])) {
    $userId = $_POST['ID']; // Corrigido para pegar o campo certo do form
    $imageName = $_FILES['image']['name'];

    // Chama a função que faz todo o trabalho de mover o arquivo e atualizar o banco
    AdicionarImagem($userId, $imageName);
}
if (isset($_POST['trocar_imagem'])) {
    $userId = $_POST['ID'];
    $imagemSelecionada = basename($_POST['imagem_selecionada']);
    //

    $conn = conectar();
    if (!$conn) {
        $_SESSION['alert'] = "Erro ao conectar ao banco!";
        header("Location: ../Home/Perfil.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE Users SET Img = :img WHERE ID = :id");
    $stmt->bindParam(':img', $imagemSelecionada);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    $_SESSION['alert'] = "Imagem de perfil atualizada!";
    header("Location: ../Home/Perfil.php");
    exit();
}
if (isset($_POST['substituir_imagem'])) {
    $userId = $_POST['ID'];
    $imageName = $_FILES['image']['name'];

    // Chama a função que faz todo o trabalho de mover o arquivo e atualizar o banco
    SubistituirImagem($userId, $imageName);
} 
if (isset($_POST['remover_imagem'])) {
    $userId = $_POST['ID'];
    $imagemSelecionada = basename($_POST['imagem_selecionada']);

    // Chama a função que faz todo o trabalho de mover o arquivo e atualizar o banco
    DeletarImagem($userId, $imagemSelecionada);
}

if (isset($_POST['Criar_Persona'])) {
    $userId = $_POST['Criar_Persona'];
    $nome = $_POST['nome'];
    $raca = $_POST['raca'];
    $classe = $_POST['classe'];
    // Chama a função que cria o personagem
    CriarPersona($userId, $nome, $raca, $classe);
}
// Logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

function BuscarEpisodiosCapitulos($userId) {
    $conn = conectar();
    if (!$conn) {
        return null;
    }
    // pesquisa o Ep e Cap do usuario
    $stmt = $conn->prepare("SELECT Ep, Cap, Text FROM Users WHERE ID = :id_user");
    $stmt->bindParam(':id_user', $userId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
