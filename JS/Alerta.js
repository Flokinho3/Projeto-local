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
