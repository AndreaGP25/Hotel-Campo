document.querySelectorAll('.read-more-btn').forEach((button) => {
    button.addEventListener('click', () => {
        const moreText = button.previousElementSibling;
        if (moreText.style.display === 'block') {
            moreText.style.display = 'none';
            button.textContent = 'Leer más...';
        } else {
            moreText.style.display = 'block';
            button.textContent = 'Leer menos';
        }
    });
});