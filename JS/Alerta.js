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
        document.body.removeChild(loading);
    }
}

function showAlert(message, type = 'success', duration = 3000) {
    const container = document.getElementById('alert-container');

    if (!container) {
        console.error('Alert container not found!');
        return;
    }

    const toast = document.createElement('div');
    toast.classList.add('toast', type === 'success' ? 'toast-success' : 'toast-error');
    toast.innerText = message;

    container.appendChild(toast);

    // Tempo para desaparecer
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)'; // deslizar para a direita enquanto some
        setTimeout(() => {
            if (container.contains(toast)) {
                container.removeChild(toast);
            }
        }, 500);
    }, duration);
}
