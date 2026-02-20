document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('busqueda');
    const resultados = document.getElementById('resultados');

    inputBusqueda.addEventListener('input', async () => {
        const query = inputBusqueda.value.trim();

        if (query.length > 0) {
            try {
                const response = await fetch(`/campo/buscar.php?query=${encodeURIComponent(query)}`);
                const data = await response.json();

                resultados.innerHTML = ''; // Limpia los resultados previos

                if (data.length > 0) {
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'dropdown-item'; // Clase de Bootstrap para dropdown
                        li.textContent = item.titulo;
                        li.addEventListener('click', () => {
                            inputBusqueda.value = item.titulo; // Selecciona el texto
                            resultados.style.display = 'none'; // Oculta el desplegable
                        });
                        resultados.appendChild(li);
                    });
                    resultados.style.display = 'block'; // Muestra el dropdown
                } else {
                    resultados.innerHTML = '<li class="dropdown-item text-muted">No se encontraron resultados</li>';
                    resultados.style.display = 'block';
                }
            } catch (error) {
                console.error('Error al buscar:', error);
            }
        } else {
            resultados.style.display = 'none'; // Oculta si no hay texto
        }
    });

    // Oculta el desplegable si el usuario hace clic fuera
    document.addEventListener('click', (e) => {
        if (!inputBusqueda.contains(e.target) && !resultados.contains(e.target)) {
            resultados.style.display = 'none';
        }
    });
});
