document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('busqueda');
    const resultados    = document.getElementById('resultados');

    inputBusqueda.addEventListener('input', async () => {
        const query = inputBusqueda.value.trim();

        if (query.length < 2) {
            resultados.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/campo/buscar.php?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (data.error) {
                resultados.innerHTML = '<li class="dropdown-item text-danger">Error al buscar</li>';
                resultados.style.display = 'block';
                return;
            }
            resultados.innerHTML = '';

            if (data.length > 0) {

                // Agrupar por URL
                const grupos = {};
                data.forEach(item => {
                    if (!grupos[item.url]) grupos[item.url] = [];
                    grupos[item.url].push(item);
                });

                Object.entries(grupos).forEach(([url, items]) => {

                    // Encabezado de sección
                    if (Object.keys(grupos).length > 1) {
                        const header = document.createElement('li');
                        header.className = 'dropdown-header text-muted small px-3 pt-2';
                        header.textContent = items[0].tipo === 'Habitación'
                            ? 'Habitaciones'
                            : 'Servicios';
                        resultados.appendChild(header);
                    }

                    items.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'dropdown-item d-flex justify-content-between align-items-center';
                        li.style.cursor = 'pointer';

                        // Nombre del resultado
                        const span = document.createElement('span');
                        span.textContent = item.nombre;

                        // Badge con el tipo
                        const badge = document.createElement('span');
                        badge.className = item.tipo === 'Habitación'
                            ? 'badge bg-secondary ms-2'
                            : 'badge bg-success ms-2';
                        badge.textContent = item.tipo;

                        li.appendChild(span);
                        li.appendChild(badge);
                        
                        li.addEventListener('click', () => {
                            inputBusqueda.value = item.nombre;
                            resultados.style.display = 'none';
                            window.location.href = item.url;
                        });

                        resultados.appendChild(li);
                    });
                });

                resultados.style.display = 'block';

            } else {
                resultados.innerHTML = '<li class="dropdown-item text-muted">Sin resultados</li>';
                resultados.style.display = 'block';
            }

        } catch (error) {
            console.error('Error al buscar:', error);
        }
    });

    // Cerrar al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!inputBusqueda.contains(e.target) && !resultados.contains(e.target)) {
            resultados.style.display = 'none';
        }
    });
});