let indiceActual = -1;
let imagenes = [];
let elementoEnLightbox = null;
let placeholderActual = null;

window.addEventListener('DOMContentLoaded', () => {
    imagenes = Array.from(document.querySelectorAll('.producto__imagen'));
});

function mostrarImagen(elemento, indice) {
    indiceActual = indice;
    moverAlLightbox(elemento);
    document.getElementById('lightbox').style.display = 'flex';
}

function mostrarImagenGrafico(src, indice) {
    indiceActual = indice;
    
    const imagenGrande = document.getElementById('imagenGrande');
    imagenGrande.src = src;
    imagenGrande.style.display = 'block';
    document.getElementById('lightbox').style.display = 'flex';
}

function moverAlLightbox(elemento) {
    if (elementoEnLightbox && placeholderActual) {
        placeholderActual.replaceWith(elementoEnLightbox);
        elementoEnLightbox.style.cssText = '';
    }

    placeholderActual = document.createElement('img');
    placeholderActual.className = elemento.className;
    placeholderActual.style.visibility = 'hidden';
    elemento.replaceWith(placeholderActual);

    const imagenGrande = document.getElementById('imagenGrande');
    imagenGrande.style.display = 'none';
    
    elemento.style.width = '100%';
    elemento.style.maxHeight = '90vh';
    elemento.style.objectFit = 'contain';
    elemento.style.pointerEvents = 'none';
    
    document.getElementById('lightbox').appendChild(elemento);
    elementoEnLightbox = elemento;
}

function cerrarLightbox(event) {
    const lightbox = document.getElementById('lightbox');
    if (event.target === lightbox) {
        if (elementoEnLightbox && placeholderActual) {
            placeholderActual.replaceWith(elementoEnLightbox);
            elementoEnLightbox.style.cssText = '';
            elementoEnLightbox = null;
            placeholderActual = null;
        }
        const imagenGrande = document.getElementById('imagenGrande');
        imagenGrande.style.display = 'block';
        imagenGrande.src = '';
        lightbox.style.display = 'none';
    }
}

function cambiarImagen(direccion) {
    if (elementoEnLightbox && placeholderActual) {
        placeholderActual.replaceWith(elementoEnLightbox);
        elementoEnLightbox.style.cssText = '';
    }

    indiceActual = (indiceActual + direccion + imagenes.length) % imagenes.length;
    const siguiente = imagenes[indiceActual];
    moverAlLightbox(siguiente);
}