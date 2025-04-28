<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login e Cadastro</title>
    <link rel="stylesheet" href="CSS/Alerta.css"> 
    <link rel="stylesheet" href="CSS/index.css?=<?php echo time(); ?>"> 
</head>
<body>
    <h2>Login e Cadastro</h2>
    <div id="tabs">
        <button id="tab-login" onclick="showForm('login')">Login</button>
        <button id="tab-cadastro" onclick="showForm('cadastro')">Cadastro</button>
    </div>

    <!-- Formulário de Login -->
    <div id="login-form" class="form-container active"> <!-- Define login como padrão -->
        <h3>Login</h3>
        <form id="formLogin">
            <input type="email" id="emailLogin" placeholder="Email" required><br>
            <input type="password" id="senhaLogin" placeholder="Senha" required><br>
            <button type="submit">Entrar</button>
        </form>
    </div>

    <!-- Formulário de Cadastro -->
    <div id="cadastro-form" class="form-container">
        <h3>Cadastro</h3>
        <form id="formCadastro">
            <input type="text" id="nome" placeholder="Nome" required><br>
            <input type="email" id="email" placeholder="Email" required><br>
            <input type="password" id="senha" placeholder="Senha" required><br>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <div id="alert-container"></div>
    <script src="JS/Alerta.js"></script>
    <script>
        // Função para alternar entre os formulários
        function showForm(formType) {
            if (formType === 'login') {
                document.getElementById('login-form').classList.add('active');
                document.getElementById('cadastro-form').classList.remove('active');
                document.getElementById('tab-login').style.backgroundColor = '#4CAF50'; // Cor ativa
                document.getElementById('tab-cadastro').style.backgroundColor = '';
            } else {
                document.getElementById('cadastro-form').classList.add('active');
                document.getElementById('login-form').classList.remove('active');
                document.getElementById('tab-cadastro').style.backgroundColor = '#4CAF50'; // Cor ativa
                document.getElementById('tab-login').style.backgroundColor = '';
            }
        }

        // Enviar formulário de login
        document.getElementById('formLogin').addEventListener('submit', function(e) {
            e.preventDefault();

            console.log('Dados capturados:', { email: document.getElementById('emailLogin').value, senha: document.getElementById('senhaLogin').value }); // Log para depuração

            const email = document.getElementById('emailLogin').value;
            const senha = document.getElementById('senhaLogin').value;

            fetch('Server/Porteiro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'login',
                    email: email,
                    senha: senha
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {
                    showAlert(data.mensagem || 'Login realizado com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.href = 'Home/Home.php';
                    }, 500);
                } else {
                    if (Array.isArray(data.mensagens)) {
                        data.mensagens.forEach(msg => showAlert(msg, 'error'));
                    } else {
                        showAlert(data.mensagem || 'Erro ao realizar login.', 'error');
                    }
                }
            })
            .catch(error => {
                showAlert('Erro de conexão com o servidor!', 'error');
            });
        });

        // Enviar formulário de cadastro
        document.getElementById('formCadastro').addEventListener('submit', function(e) {
            e.preventDefault();

            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('senha').value.trim();

            console.log('Dados capturados:', { nome, email, senha }); // Log para depuração

            fetch('Server/Porteiro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'cadastro',
                    nome: nome,
                    email: email,
                    senha: senha
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log('Resposta do servidor:', data); // Log para depuração
                if (data && data.status === 'ok') {
                    showAlert(data.mensagem || 'Cadastro realizado com sucesso!', 'success');
                } else if (data && Array.isArray(data.mensagens)) {
                    data.mensagens.forEach(msg => showAlert(msg, 'error'));
                } else {
                    showAlert(data.mensagem || 'Erro ao cadastrar.', 'error');
                }
            })
            .catch(error => {
                console.error('Erro de conexão:', error);
                showAlert('Erro de conexão com o servidor!', 'error');
            });
        });

        // Define o botão de login como ativo inicialmente
        document.getElementById('tab-login').style.backgroundColor = '#4CAF50'; // Cor ativa
        document.getElementById('tab-cadastro').style.backgroundColor = '';
    </script>
</body>
</html>
