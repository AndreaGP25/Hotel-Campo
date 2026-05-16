<?php
include $_SERVER['DOCUMENT_ROOT'] . '/public/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/public/autenticacion.php';

$id_usuario = $_SESSION['id_usuario'];

try {
    $sql = "SELECT r.id, r.habitacion_id, r.nombre, r.email, r.telefono,
                   r.fecha_llegada, r.fecha_salida, r.fecha_reservacion,
                   r.estado,
                   h.titulo AS habitacion
              FROM reservaciones r
              JOIN habitaciones  h ON r.habitacion_id = h.id
             WHERE r.email = (SELECT email FROM usuarios WHERE id_usuario = ?)
             ORDER BY r.fecha_reservacion DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario]);
    $reservaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener el historial de reservaciones: " . $e->getMessage();
    exit();
}

$ahora = new DateTime();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Reservaciones</title>
    <link rel="stylesheet" href="/public/css/estilo-historial.css">
</head>

<body>
    <div class="overlay"></div>
    <main class="contenedor-historial">
        <h1>Historial de Reservaciones</h1>

        <?php
        if (!empty($_GET['ok'])):
        ?>
            <div class="alerta alerta-ok">
                <?= htmlspecialchars($_GET['ok']) ?>
            </div>
        <?php elseif (!empty($_GET['error'])): ?>
            <div class="alerta alerta-error">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($reservaciones)): ?>
            <p class="mensaje-sin-datos">No tienes reservaciones registradas.</p>
        <?php else: ?>
            <table class="tabla-historial">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Habitación</th>
                        <th>Fecha Llegada</th>
                        <th>Fecha Salida</th>
                        <th>Fecha Reservación</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservaciones as $r):

                        // Determinar si aún es cancelable (> 48 hrs)
                        $fecha_llegada      = new DateTime($r['fecha_llegada']);
                        $seg_restantes      = $fecha_llegada->getTimestamp() - $ahora->getTimestamp();
                        $cancelable         = ($r['estado'] === 'activa') && ($seg_restantes > 48 * 3600);

                        if ($r['estado'] === 'cancelada') {
                            $badge_clase = 'badge-cancelada';
                            $badge_texto = 'Cancelada';
                        } elseif ($seg_restantes <= 0) {
                            // Estadía completada
                            $badge_clase = 'badge-vencida';
                            $badge_texto = 'Completada';
                        } else {
                            $badge_clase = 'badge-activa';
                            $badge_texto = 'Activa';
                        }

                        // Mensaje informativo cuando ya no es cancelable pero sigue activa
                        $tooltip = '';
                        if ($r['estado'] === 'activa' && $seg_restantes > 0 && !$cancelable) {
                            $tooltip = 'No cancelable: faltan menos de 48 horas para el check-in.';
                        }
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($r['id']) ?></td>
                            <td><?= htmlspecialchars($r['habitacion']) ?></td>
                            <td><?= htmlspecialchars($r['fecha_llegada']) ?></td>
                            <td><?= htmlspecialchars($r['fecha_salida']) ?></td>
                            <td><?= htmlspecialchars($r['fecha_reservacion']) ?></td>
                            <td>
                                <span class="badge-estado <?= $badge_clase ?>"
                                      <?= $tooltip ? 'title="' . htmlspecialchars($tooltip) . '"' : '' ?>>
                                    <?= $badge_texto ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($cancelable): ?>
                                    <button class="btn-cancelar"
                                            onclick="abrirModal(<?= $r['id'] ?>, '<?= htmlspecialchars($r['habitacion'], ENT_QUOTES) ?>', '<?= htmlspecialchars($r['fecha_llegada'], ENT_QUOTES) ?>')">
                                        Cancelar
                                    </button>
                                <?php elseif ($tooltip): ?>
                                    <span style="font-size:11px;color:#999;" title="<?= htmlspecialchars($tooltip) ?>">
                                        No cancelable
                                    </span>
                                <?php else: ?>
                                    <span style="font-size:11px;color:#ccc;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <div class="modal-overlay" id="modal-cancelar">
        <div class="modal-box">
            <h3>¿Cancelar reservación?</h3>
            <p id="modal-descripcion">
                Esta acción no se puede deshacer.
            </p>
            <div class="modal-acciones">
                <form method="POST" action="/public/cancelar_reservacion.php" id="form-cancelar">
                    <input type="hidden" name="id_reservacion" id="input-id-reservacion" value="">
                    <button type="submit" class="btn-modal-confirmar">Sí, cancelar</button>
                </form>
                <button class="btn-modal-cancelar" onclick="cerrarModal()">No, volver</button>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/public/footer.php'; ?>

    <script>
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
    </script>
</body>
</html>
