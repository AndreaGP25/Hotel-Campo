// Limpiar el error de la URL al cargar la página
window.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href);
    if (url.searchParams.has('error')) {
        url.searchParams.delete('error');
        window.history.replaceState({}, '', url);
    }
});

// Limpiar el texto si el usuario selecciona otro archivo
const inputImagen = document.getElementById('imagen');
if (inputImagen) {
    inputImagen.addEventListener('change', function() {
    const mensaje = document.getElementById('error-imagen');
    const archivo = this.files[0];
    const maxSize = 200 * 1024; // 200 KB

    if (archivo && archivo.size > maxSize) {
        if (mensaje) {
            mensaje.innerText = "La imagen no puede pesar más de 200 KB.";
            mensaje.style.display = 'block';
        }
    } else {
        if (mensaje) mensaje.style.display = 'none';
    }
});
}
