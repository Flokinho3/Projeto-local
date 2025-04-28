document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.Sidebar');

    document.addEventListener('mousemove', function(event) {
        if (event.clientX < 10) {
            // Se o mouse estiver a menos de 30px da borda esquerda
            sidebar.classList.add('active');
        } else if (event.clientX > 260) {
            // Se o mouse se afastar mais que 260px da esquerda (sidebar + um espacinho)
            sidebar.classList.remove('active');
        }
    });
});
