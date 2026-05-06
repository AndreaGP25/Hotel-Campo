const huespedesInput = document.getElementById('huespedes');
const habitacionesSelect = document.getElementById('num_habitaciones');
const habitacionesContainer = document.getElementById('habitaciones-container');

// Actualizar opciones de habitaciones según la cantidad de huéspedes
huespedesInput.addEventListener('input', () => {
    const huespedes = parseInt(huespedesInput.value, 10);

    Array.from(habitacionesSelect.options).forEach(option => {
        const habitaciones = parseInt(option.value, 10);

        if (huespedes === 1) {
            option.disabled = habitaciones !== 1; 
        } else if (huespedes === 2) {
            option.disabled = habitaciones > 2; // Máximo 2 habitaciones permitidas
        } else if (huespedes >= 3 && huespedes <= 4) {
            option.disabled = false; // Todas las opciones habilitadas
        } else if (huespedes >= 5 && huespedes <= 8) {
            option.disabled = habitaciones === 1; // Desactivar solo 1 habitación
        } else if (huespedes >= 9 && huespedes <= 12) {
            option.disabled = habitaciones < 3; // Solo permitir 3 habitaciones
        }
    });

    const habilitada = Array.from(habitacionesSelect.options).find(option => !option.disabled);
    if (habilitada) {
        habitacionesSelect.value = habilitada.value;
    }

    actualizarHabitaciones(parseInt(habitacionesSelect.value, 10));
});

habitacionesSelect.addEventListener('change', () => {
    actualizarHabitaciones(parseInt(habitacionesSelect.value, 10));
});

// Actualizar opciones de selección de habitaciones
function actualizarHabitaciones(cantidad) {
    const seleccionadas = new Set();

    Array.from(habitacionesContainer.children).forEach((habitacionDiv, index) => {
        const select = habitacionDiv.querySelector('select');

        if (index < cantidad) {
            habitacionDiv.style.display = 'block';
            select.required = true;
        } else {
            habitacionDiv.style.display = 'none';
            select.required = false;
        }
    });

    actualizarOpcionesHabitaciones();
}

function actualizarOpcionesHabitaciones() {
    const seleccionadas = new Map(); // Contador de habitaciones seleccionadas por tipo

    // Recopilar habitaciones seleccionadas
    Array.from(document.querySelectorAll('#habitaciones-container select')).forEach(select => {
        const habitacionId = select.value;
        if (habitacionId) {
            if (seleccionadas.has(habitacionId)) {
                seleccionadas.set(habitacionId, seleccionadas.get(habitacionId) + 1);
            } else {
                seleccionadas.set(habitacionId, 1);
            }
        }
    });

    // Actualizar las opciones según la disponibilidad
    Array.from(document.querySelectorAll('#habitaciones-container select')).forEach(select => {
        Array.from(select.options).forEach(option => {
            const habitacionId = option.value;

            if (habitacionId) {
                // Obtener la disponibilidad desde el campo oculto
                const disponibilidadInput = document.getElementById(`disponibilidad_${habitacionId}`);
                const disponibilidad = parseInt(disponibilidadInput.value, 10) || 0;
                const seleccionada = seleccionadas.get(habitacionId) || 0;

                // Deshabilitar si las selecciones exceden la disponibilidad
                if (seleccionada >= disponibilidad && select.value !== habitacionId) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            }
        });
    });
}


// Detectar cambios en los select de habitaciones
habitacionesContainer.addEventListener('change', (event) => {
    if (event.target.tagName === 'SELECT') {
        actualizarOpcionesHabitaciones();
    }
});


// Validaciones de fechas
const fechaLlegadaInput = document.getElementById('fecha_llegada');
const fechaSalidaInput = document.getElementById('fecha_salida');
const errorLlegada = document.getElementById('error_llegada');
const errorSalida = document.getElementById('error_salida');
const hoy = new Date().toISOString().split('T')[0];

// Validación de fecha de llegada
fechaLlegadaInput.addEventListener('input', () => {
    if (fechaLlegadaInput.value < hoy) {
        errorLlegada.style.display = 'block';
        fechaLlegadaInput.setCustomValidity('La fecha de llegada no puede ser anterior a hoy.');
    } else {
        errorLlegada.style.display = 'none';
        fechaLlegadaInput.setCustomValidity('');
    }

    // Validar fecha de salida si ya está seleccionada
    if (fechaSalidaInput.value && fechaSalidaInput.value <= fechaLlegadaInput.value) {
        errorSalida.style.display = 'block';
        fechaSalidaInput.setCustomValidity('La fecha de salida debe ser posterior a la fecha de llegada.');
    } else {
        errorSalida.style.display = 'none';
        fechaSalidaInput.setCustomValidity('');
    }
});

// Validación de fecha de salida
fechaSalidaInput.addEventListener('input', () => {
    if (fechaSalidaInput.value <= fechaLlegadaInput.value) {
        errorSalida.style.display = 'block';
        fechaSalidaInput.setCustomValidity('La fecha de salida debe ser posterior a la fecha de llegada.');
    } else {
        errorSalida.style.display = 'none';
        fechaSalidaInput.setCustomValidity('');
    }
});

// validaciones del pago
const numeroTarjetaInput = document.getElementById('numero_tarjeta');
const fechaExpiracionInput = document.getElementById('fecha_expiracion')
const cvvInput = document.getElementById('cvv');
const errorNumeroTarjeta = document.getElementById('error_numeroTarjeta');
const errorCVV = document.getElementById('error_cvv');


// Validación del número de tarjeta
numeroTarjetaInput.addEventListener('input', () => {
    const cumpleFormatoTarjeta = /^\d{16}$/.test(numeroTarjetaInput.value);

    if (!cumpleFormatoTarjeta) {
        errorNumeroTarjeta.style.display = 'block';
        numeroTarjetaInput.setCustomValidity('El número de tarjeta debe tener 16 dígitos.');
    } else {
        errorNumeroTarjeta.style.display = 'none';
        numeroTarjetaInput.setCustomValidity('');
    }
});

// Validación de la fecha de expiración de la tarjeta
fechaExpiracionInput.addEventListener('input', function () {
    const [anio, mes] = fechaExpiracionInput.value.split('-').map(Number);
    const fechaActual = new Date();
    const anioActual = fechaActual.getFullYear();
    const mesActual = fechaActual.getMonth() + 1; 
	
    if (anio < anioActual || (anio === anioActual && mes < mesActual)) {
        this.setCustomValidity('La tarjeta ha expirado.');
    } else {
        this.setCustomValidity('');
    }
});

// Validación del CVV
cvvInput.addEventListener('input', () => {
    const cumpleFormatoCVV = /^\d{3,4}$/.test(cvvInput.value);

    if (!cumpleFormatoCVV) {
        errorCVV.style.display = 'block';
        cvvInput.setCustomValidity('El CVV debe tener entre 3 y 4 dígitos.');
    } else {
        errorCVV.style.display = 'none';
        cvvInput.setCustomValidity('');
    }
});


// monto de pago
// Función para calcular el monto total
function calcularMonto() {
    const fechaLlegada = new Date(document.getElementById('fecha_llegada').value);
    const fechaSalida = new Date(document.getElementById('fecha_salida').value);
    const dias = (fechaSalida - fechaLlegada) / (1000 * 60 * 60 * 24); // Diferencia en días

    // Verificar que las fechas sean válidas
    if (isNaN(dias) || dias < 1) {
        document.getElementById('monto_total').value = '0.00';
        return;
    }

    let total = 0;

    // Sumar el precio de cada habitación seleccionada
    const selects = document.querySelectorAll('#habitaciones-container select');
    selects.forEach(select => {
        if (select.value) {
            const precio = parseFloat(select.selectedOptions[0].dataset.precio);
            total += precio * dias;
        }
    });

    // Mostrar el total en el campo correspondiente
    document.getElementById('monto_total').value = total.toFixed(2);
}

document.getElementById('habitaciones-container').addEventListener('change', calcularMonto);
document.getElementById('fecha_llegada').addEventListener('input', calcularMonto);
document.getElementById('fecha_salida').addEventListener('input', calcularMonto);

document.getElementById('cancelar-btn').addEventListener('click', function () {
    const confirmacion = confirm("¿Estás seguro de que deseas cancelar y regresar a la página de inicio?");
    if (confirmacion) {
        window.location.href = 'index.php';
    }
});

document.getElementById('reservacion-btn').addEventListener('click', function (event) {
    const confirmacion = confirm("¿Estás seguro de que deseas realizar esta reservación?");
    if (!confirmacion) {
        event.preventDefault(); 
    }
});

