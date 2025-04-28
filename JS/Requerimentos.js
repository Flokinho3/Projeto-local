function submitImage() {
    const imgInput = document.getElementById('img-upload');
    
    // Verifica se o usuário selecionou um arquivo
    if (imgInput.files.length === 0) {
        alert('Por favor, selecione uma imagem para enviar.');
        return;
    }

    const formData = new FormData();
    formData.append('img', imgInput.files[0]);
    
    // Adiciona a ação à requisição
    formData.append('action', 'trocaImg');

    // Exibe o overlay de carregamento
    showLoading('Enviando imagem...');

    // Envia a requisição via Fetch API
    fetch('../../Server/Porteiro.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Primeiro, obtenha o texto da resposta
    .then(text => {
        console.log('Resposta do servidor:', text); // Log da resposta
        const data = JSON.parse(text); // Tente converter para JSON
        hideLoading();
    
        if (data.status === 'ok') {
            alert(data.mensagem || 'Imagem atualizada com sucesso!');
            const newImage = '../../Imagens_user/' + data.imagem;
            document.querySelector('.perfil-img').src = newImage;
        } else {
            if (Array.isArray(data.mensagens)) {
                data.mensagens.forEach(msg => alert(msg));
            } else {
                alert(data.mensagem || 'Erro ao atualizar imagem.');
            }
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Erro:', error);
        alert('Erro de conexão com o servidor.');
    });
}

function showLoading(message = 'Carregando...') {
    const loading = document.createElement('div');
    loading.id = 'loading-overlay';
    loading.style.position = 'fixed';
    loading.style.top = '0';
    loading.style.left = '0';
    loading.style.width = '100%';
    loading.style.height = '100%';
    loading.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    loading.style.display = 'flex';
    loading.style.alignItems = 'center';
    loading.style.justifyContent = 'center';
    loading.style.zIndex = '9999';
    loading.style.color = 'white';
    loading.style.fontSize = '24px';
    loading.innerText = message;

    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loading-overlay');
    if (loading) {
        loading.remove();
    }
}
