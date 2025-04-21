<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfólio - Thiago</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans">

    <header class="bg-gray-800 p-6 shadow-md">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
        <h1 class="text-3xl font-bold">Thiago.dev</h1>
        <nav class="space-x-6">
            <a href="Home.php" class="hover:text-purple-400">Home</a>
            <a href="#projetos" class="hover:text-purple-400">Projetos</a>
            <a href="#sobre" class="hover:text-purple-400">Sobre</a>
            <a href="#contato" class="hover:text-purple-400">Contato</a>
        </nav>
    </div>
</header>

<section class="text-center py-20 bg-gradient-to-b from-gray-900 to-gray-800">
    <h2 class="text-4xl font-bold mb-4">Olá, eu sou o Thiago 👋</h2>
    <p class="text-lg text-gray-300">Desenvolvedor web focado em criar experiências modernas e funcionais.</p>
    <a href="#projetos" class="mt-6 inline-block px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">Ver Projetos</a>
</section>

<section id="projetos" class="py-16 max-w-6xl mx-auto px-4">
    <h3 class="text-3xl font-bold mb-10 text-center">Projetos em Destaque 🚀</h3>
    <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-gray-800 p-6 rounded-xl shadow hover:scale-105 transition">
            <h4 class="text-xl font-semibold mb-2">Site Web Ativo!</h4>
            <p class="text-gray-400 text-sm mb-4">Login, cadastro, exibição de perfil com imagem. PHP + PostgreSQL.</p>
            <a href="https://github.com/Flokinho3/Projeto-local.git" class="text-purple-400 hover:underline">Ver mais</a>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl shadow hover:scale-105 transition">
            <h4 class="text-xl font-semibold mb-2">Ia pessoal (Via cmd)</h4>
            <p class="text-gray-400 text-sm mb-4">Ia pessoal leve e eficiente</p>
            <a href="https://github.com/Flokinho3/Ia-pessoal-Gemine.git" class="text-purple-400 hover:underline">Ver mais</a>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl shadow hover:scale-105 transition">
            <h4 class="text-xl font-semibold mb-2">Em breve...</h4>
            <p class="text-gray-400 text-sm mb-4">Mais projetos vindo aí. O grind não para. 😤</p>
        </div>
    </div>
</section>

<section id="sobre" class="py-16 bg-gray-800 text-center px-4">
    <h3 class="text-3xl font-bold mb-6">Sobre Mim 🧠</h3>
    <p class="max-w-2xl mx-auto text-gray-300 text-lg">
    Tenho estudado programação com foco em back-end (PHP, PostgreSQL) e um toque de front-end moderno. Curto criar sistemas com lógica bem estruturada e interfaces limpas.
    </p>
</section>

<section id="contato" class="py-16 max-w-4xl mx-auto px-4 text-center">
    <h3 class="text-3xl font-bold mb-6">Vamos trocar uma ideia? ✉️</h3>
    <p class="text-gray-400 mb-4">Me chama no e-mail ou nas redes sociais:</p>
    <div class="space-x-4">
        <a href="mailto:thiagosiegamg@gmail.com" class="text-purple-400 hover:underline">Email</a>
        <a href="https://github.com/Flokinho3" target="_blank" class="text-purple-400 hover:underline">GitHub</a>
    </div>
</section>

<footer class="text-center py-6 text-gray-500 bg-gray-900">
    &copy; 2025 Thiago.dev – Todos os direitos reservados
</footer>

</body>
</html>
