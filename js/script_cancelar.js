const modal     = document.getElementById('modal-cancelar');
const inputId   = document.getElementById('input-id-reservacion');
const modalDesc = document.getElementById('modal-descripcion');

function abrirModal(idReservacion, habitacion, fechaLlegada) {
    inputId.value   = idReservacion;
    modalDesc.textContent =
        'Estás a punto de cancelar la reservación #' + idReservacion +
        ' (' + habitacion + ') con check-in el ' + fechaLlegada +
        '. Esta acción no se puede deshacer.';
    modal.classList.add('visible');
}

function cerrarModal() {
    modal.classList.remove('visible');
    inputId.value = '';
}

modal.addEventListener('click', function(e) {
    if (e.target === modal) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});