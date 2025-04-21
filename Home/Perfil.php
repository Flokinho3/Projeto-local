<?php
// inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado, se não estiver, redireciona para a página de login
if (!isset($_SESSION['User'])) {
    $_SESSION ['alert'] = "Você não está logado!";
    header("Location: ../index.php");
    exit();
}

include_once '../Server/Porteiro.php';
include_once "../Utilitarios/Alerta.php";


$User = BuscarUsuario($_SESSION['User']);
$User_img = DefinirImagem($User['Img'] , $User["ID"]); // Define a imagem padrão caso não exista

//debug
echo "<pre>";
print_r($User);
echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo htmlspecialchars($User['User'] ?? 'Usuário'); ?></title>
    <link rel="stylesheet" href="../CSS/Perfil.css?=<?php echo time(); ?>">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>
<body>
    

    <div class = "header">
        <h1>Bem-vindo, <?php echo htmlspecialchars($User['Nome'] ?? 'Usuário'); ?>!</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="Home.php">Home</a></li>
                <li><a href="Perfil.php">Perfil</a></li>
                <li><a href="Update.php">Atualizaçoes</a></li>
                <li><a href="../Server/Porteiro.php?logout=true">Sair</a></li>
            </ul>
        </nav>
    </div>
    <div class = "Container">
        <div class = "User">
            <div class = "User-Info">
                <div class = "User-Image">
                    <?php
                    // Verifica se a imagem existe e exibe-a, caso contrário, exibe uma imagem padrão
                    if (file_exists($User_img)) {
                        echo "<img src='$User_img' alt='Imagem de Perfil' class='profile-image'>";
                    } else {
                        echo "<img src='../images/Base.png' alt='Imagem de Perfil Padrão' class='profile-image'>";
                    }
                    ?>
                </div>
                <h2>Informações</h2>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($User['Nome'] ?? 'Usuário'); ?></p>
                </br>
            </div>
        </div>
        <div class="carousel-container">
            <div class="carousel-wrapper">
                <div class="carousel">
                    <?php
                    $pastaUsuario = "../images/" . $User["ID"];
                    $imagens = glob($pastaUsuario . "/*");
                    if (count($imagens) > 0) {
                        foreach ($imagens as $imagem) {
                            echo "<div class='carousel-item'>";
                            echo "<img src='$imagem' alt='Imagem de Perfil'>";
                            echo "<div class='carousel-buttons'>";
                            echo "<form action='../Server/Porteiro.php' method='post'>";
                            echo "<input type='hidden' name='trocar_imagem' value='1'>";
                            echo "<input type='hidden' name='ID' value='" . htmlspecialchars($User['ID']) . "'>";
                            echo "<input type='hidden' name='imagem_selecionada' value='" . htmlspecialchars($imagem) . "'>";
                            echo "<input type='submit' value='Selecionar' class='btn-carousel'>";
                            echo "</form>";
                            echo "<form action='../Server/Porteiro.php' method='post'>";
                            echo "<input type='hidden' name='remover_imagem' value='1'>";
                            echo "<input type='hidden' name='ID' value='" . htmlspecialchars($User['ID']) . "'>";
                            echo "<input type='hidden' name='imagem_selecionada' value='" . htmlspecialchars($imagem) . "'>";
                            echo "<input type='submit' value='Remover' class='btn-carousel btn-remove'>";
                            echo "</form>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Nenhuma imagem encontrada.</p>";
                    }
                    ?>
                </div>
            </div>
            <button id="prev" class="carousel-button">❮</button>
            <button id="next" class="carousel-button">❯</button>
        </div>

    </div>  
    <div class ="Add_img">
        <h2>Adicionar Imagem de Perfil</h2>
        <form action="../Server/Porteiro.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="adicionar_imagem" value="1">
            <input type="file" name="image" accept="image/*" required>
            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($User['ID']); ?>">
            <input type="submit" value="Enviar">
        </form>
    </div> 
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.querySelector('.carousel');
        const items = document.querySelectorAll('.carousel-item');
        const prevBtn = document.getElementById('prev');
        const nextBtn = document.getElementById('next');
        let currentIndex = 0;

        // Hide carousel controls if there are no items or only one item
        if (items.length <= 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }

        function updateCarousel() {
            const itemWidth = 100; // Each item is 100% wide
            carousel.style.transform = `translateX(-${currentIndex * itemWidth}%)`;
        }

        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = items.length - 1; // Loop to the end
            }
            updateCarousel();
        });

        nextBtn.addEventListener('click', function() {
            if (currentIndex < items.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0; // Loop to the beginning
            }
            updateCarousel();
        });

        // Initial update
        updateCarousel();
    });
    </script>

</body>
</html>