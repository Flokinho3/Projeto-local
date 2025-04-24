<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['User'])) {
    header("Location: ../../ErroSessao.php");
    exit;
}

include_once '../../Server/Porteiro.php';
include_once "../../Utilitarios/Alerta.php";

$User = BuscarUsuario($_SESSION['User']);
$User_img = DefinirImagem($User['Img'], $User["ID"]);
$User_img = "../" . $User_img; # corerçao de caminho para o avatar


$capituloRequerido = isset($_GET['capitulo']) ? "Cap_" . preg_replace('/[^0-9]/', '', $_GET['capitulo']) : null;
$episodioRequerido = preg_replace('/[^0-9]/', '', $_GET['episodio'] ?? '');

$capitulos = glob("Cap_*", GLOB_ONLYDIR);
$episodiosData = [];

// Carrega todos os episódios de todos os capítulos
foreach ($capitulos as $capitulo) {
    $nomeCapitulo = basename($capitulo);
    $arquivoJson = "$capitulo/episodios.json";

    if (file_exists($arquivoJson)) {
        $conteudo = file_get_contents($arquivoJson);
        $episodios = json_decode($conteudo, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($episodios)) {
            foreach ($episodios as $id => $dados) {
                $dados['ID'] = $id;
                $episodiosData[$nomeCapitulo][$id] = $dados;
            }
        }
    }
}

// Dados do episódio selecionado
$episodioSelecionado = null;
if ($capituloRequerido && $episodioRequerido && isset($episodiosData[$capituloRequerido][$episodioRequerido])) {
    $episodioSelecionado = $episodiosData[$capituloRequerido][$episodioRequerido];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo $episodioSelecionado['Titulo'] ?? 'Selecionar Episódio'; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
        img { max-width: 100%; height: auto; margin: 20px 0; }
        .opcoes ul { padding-left: 20px; }
    </style>
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($User['Nome']); ?></h1>
    <img src="<?php echo htmlspecialchars($User_img); ?>" width="80" alt="Avatar">

    <?php if ($episodioSelecionado): ?>
        <h2><?php echo htmlspecialchars($episodioSelecionado["Titulo"]); ?></h2>
        <p><em><?php echo htmlspecialchars($episodioSelecionado["Subtitulo"]); ?></em></p>
        <img src="<?php echo htmlspecialchars($episodioSelecionado["Fundo"]); ?>" alt="Imagem do Episódio">

        <div class="texto">
            <?php foreach ($episodioSelecionado["Texto"] as $paragrafo): ?>
                <p><?php echo htmlspecialchars($paragrafo); ?></p>
            <?php endforeach; ?>
        </div>

        <div class="opcoes">
            <strong>Opções:</strong>
            <ul>
                <?php foreach ($episodioSelecionado["Opcoes"] as $opcao): ?>
                    <li>
                        <a href="?capitulo=<?php echo urlencode(str_replace("Cap_", "", $capituloRequerido)); ?>&episodio=<?php echo urlencode($opcao["Proximo"]); ?>">
                            <?php echo htmlspecialchars($opcao["Texto"]); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    <?php else: ?>
        <h2>Selecione um capítulo e episódio válido</h2>
    <?php endif; ?>
</body>
</html>
