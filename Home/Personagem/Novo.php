<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['User'])) {
    $_SESSION['alert'] = "Você não está logado!";
    header("Location: ../../index.php");
    exit();
}

include_once '../../Server/Porteiro.php';
include_once "../../Utilitarios/Alerta.php";

$User = BuscarUsuario($_SESSION['User']);

// Descrições organizadas
$descricoes = [
    'racas' => [
        "Humano" => "São seres recém-chegados ao planeta. De onde vieram ou para onde vão, ninguém sabe. Eles são conhecidos por sua capacidade de adaptação e resistência. Os humanos são versáteis e podem se especializar em qualquer classe, mas são mais comuns como guerreiros ou magos.",
        "Demônio" => "Os demônios são seres malignos que habitam a terra. Conhecidos por sua força e habilidades mágicas. Frequentemente vistos como vilões, mas alguns podem ser aliados poderosos.",
        "Anjo" => "Seres celestiais conhecidos por sua bondade e compaixão. Costumam ser heróis... mas nem sempre.",
        "Demi-Humano" => "Híbridos de humanos com outras raças. Muitas vezes tratados como escória e usados como servos ou escravos. Apesar disso, possuem força e poderes mágicos."
    ],
    'classes' => [
        "Guerreiro" => "Mestres do combate corpo a corpo. Reconhecidos por sua força bruta e resistência absurda.",
        "Mago" => "Dominadores da magia arcana. Inteligentes e estratégicos, mas fisicamente frágeis.",
        "Arqueiro" => "Precisão mortal à distância. Arqueiros são pacientes, calculistas e letais.",
        "Assassino" => "Mestres da furtividade e do dano explosivo. Preferem agir nas sombras e finalizar rápido."
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Personagem</title>
    <link rel="stylesheet" href="../../CSS/NovoPersonagem.css?=<?php echo time(); ?>">
</head>
<body>

<div class="header">
    <h1>Bem-vindo, <?php echo htmlspecialchars($User['Nome'] ?? 'Usuário'); ?>!</h1>
    <nav>
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="Home.php">Home</a></li>
            <li><a href="Perfil.php">Perfil</a></li>
            <li><a href="Update.php">Atualizações</a></li>
            <li><a href="../Server/Porteiro.php?logout=true">Sair</a></li>
        </ul>
    </nav>
</div>

<div class="container">
    <h2>Criar Novo Personagem</h2>

    <form action="../../Server/Porteiro.php" method="POST" enctype="multipart/form-data">
        <?php if (!empty($User['Personagem'])): ?>
            <p style="color: red;">Aviso: Seu personagem atual será substituído ao criar um novo.</p>
        <?php endif; ?>

        <input type="hidden" name="Criar_Persona" value="<?php echo htmlspecialchars($User['ID']); ?>">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($User['Nome'] ?? ''); ?>" required>

        <label for="raca">Raça:</label>
        <select id="raca" name="raca" required>
            <?php foreach ($descricoes['racas'] as $raca => $_): ?>
                <option value="<?php echo $raca; ?>"><?php echo $raca; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="classe">Classe:</label>
        <select id="classe" name="classe" required>
            <?php foreach ($descricoes['classes'] as $classe => $_): ?>
                <option value="<?php echo $classe; ?>"><?php echo $classe; ?></option>
            <?php endforeach; ?>
        </select>

        <div class="descricao">
            <h3></h3>
        </div>

        <button type="submit">Criar Personagem</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const descricao = document.querySelector('.descricao h3');
        const raca = document.getElementById('raca');
        const classe = document.getElementById('classe');

        const descricoes = <?php echo json_encode($descricoes, JSON_UNESCAPED_UNICODE); ?>;

        function atualizarDescricao() {
            const r = raca.value;
            const c = classe.value;

            const descRaca = descricoes.racas[r] || '';
            const descClasse = descricoes.classes[c] || '';

            descricao.innerHTML = `<strong>Raça:</strong> ${descRaca}<br><br><strong>Classe:</strong> ${descClasse}`;
            descricao.style.textAlign = "left";
        }

        raca.addEventListener('change', atualizarDescricao);
        classe.addEventListener('change', atualizarDescricao);
        atualizarDescricao(); // Inicial
    });
</script>

</body>
</html>
