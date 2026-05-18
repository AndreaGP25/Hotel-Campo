<?php
include $_SERVER['DOCUMENT_ROOT'] . '/public/config/config.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/public/enviar_correo.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header('Location: /public/sesiones.php?action=login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/historial_reservaciones.php');
    exit();
}

// Validar input
$id_reservacion = filter_input(INPUT_POST, 'id_reservacion', FILTER_VALIDATE_INT);
if (!$id_reservacion || $id_reservacion <= 0) {
    header('Location: /public/historial_reservaciones.php?error=' . urlencode('ID de reservación inválido.'));
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];

try {
    // Obtener la reservación y verificar que pertenece al usuario
    $stmt = $conn->prepare(
        "SELECT r.id, r.habitacion_id, r.nombre, r.email,
                r.fecha_llegada, r.estado, h.titulo AS habitacion
           FROM reservaciones r
           JOIN habitaciones  h ON r.habitacion_id = h.id
          WHERE r.id = ?
            AND r.email = (SELECT email FROM usuarios WHERE id_usuario = ?)
          LIMIT 1"
    );
    $stmt->execute([$id_reservacion, $id_usuario]);
    $reservacion = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar que la reservación existe y pertenece al usuario
    if (!$reservacion) {
        header('Location: /public/historial_reservaciones.php?error=' . urlencode('Reservación no encontrada o no te pertenece.'));
        exit();
    }

    // Verificar que no esté ya cancelada
    if ($reservacion['estado'] === 'cancelada') {
        header('Location: /public/historial_reservaciones.php?error=' . urlencode('Esta reservación ya fue cancelada anteriormente.'));
        exit();
    }

    // Verificar que no esté ya completada la estancia
    // Si faltan 48 horas o menos, la cancelación ya no está permitida.
    $ahora         = new DateTime();
    $fecha_llegada = new DateTime($reservacion['fecha_llegada']);
    $diferencia    = $ahora->diff($fecha_llegada);
    $horas_restantes = ($diferencia->days * 24) + $diferencia->h;

    if (!$diferencia->invert === false && $horas_restantes <= 48) {
        // invert = 0 significa que fecha_llegada está en el futuro
        // Pero si ya pasó (invert = 1) tampoco se puede
    }

    $ts_ahora    = $ahora->getTimestamp();
    $ts_llegada  = $fecha_llegada->getTimestamp();
    $segundos_restantes = $ts_llegada - $ts_ahora;

    if ($segundos_restantes <= (48 * 3600)) {
        $mensaje = $segundos_restantes <= 0
            ? 'No se puede cancelar: la fecha de llegada ya pasó.'
            : 'No se puede cancelar: faltan menos de 48 horas para la fecha de llegada.';
        header('Location: /public/historial_reservaciones.php?error=' . urlencode($mensaje));
        exit();
    }

    // Ejecutar cancelación en transacción
    $conn->beginTransaction();

    // Marcar la reservación como cancelada
    $upd = $conn->prepare(
        "UPDATE reservaciones SET estado = 'cancelada' WHERE id = ?"
    );
    $upd->execute([$id_reservacion]);

    // Restaurar disponibilidad de la habitación
    $rest = $conn->prepare(
        "UPDATE habitaciones SET disponibilidad = disponibilidad + 1 WHERE id = ?"
    );
    $rest->execute([$reservacion['habitacion_id']]);

    $conn->commit();

    // Enviar correo de confirmación de cancelación
    $asunto     = 'Cancelación de Reservación — Hotel Refugio del Valle';
    $cuerpoHTML = plantillaCancelacionReservacion(
        $reservacion['nombre'],
        $id_reservacion,
        $reservacion['fecha_llegada'],
        $reservacion['habitacion']
    );
    enviarCorreo($reservacion['email'], $reservacion['nombre'], $asunto, $cuerpoHTML);

    header('Location: /public/historial_reservaciones.php?ok=' . urlencode('Tu reservación fue cancelada exitosamente.'));
    exit();

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log('[Hotel Cancelación] Error BD: ' . $e->getMessage());
    header('Location: /public/historial_reservaciones.php?error=' . urlencode('Error al procesar la cancelación. Intenta de nuevo.'));
    exit();
}
