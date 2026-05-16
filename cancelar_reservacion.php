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


// ---------------- Plantilla de correo para confirmación de cancelación
function plantillaCancelacionReservacion(
    string $nombre,
    int    $id_reservacion,
    string $fecha_llegada,
    string $habitacion
): string {
    $fecha_fmt = date('d/m/Y', strtotime($fecha_llegada));

    return "
    <!DOCTYPE html>
    <html lang='es'>
    <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width'></head>
    <body style='margin:0;padding:0;background:#f4f4f4;font-family:Georgia,serif;'>
      <table width='100%' cellpadding='0' cellspacing='0'>
        <tr><td align='center' style='padding:40px 16px;'>
          <table width='560' cellpadding='0' cellspacing='0'
                 style='background:#fff;border-radius:12px;overflow:hidden;
                        box-shadow:0 4px 24px rgba(0,0,0,.08);'>
            <tr>
              <td style='background:#1a3a2a;padding:32px 40px;text-align:center;'>
                <h1 style='margin:0;color:#c8a96e;font-size:22px;letter-spacing:2px;
                           text-transform:uppercase;'>Hotel Refugio del Valle</h1>
              </td>
            </tr>
            <tr>
              <td style='padding:40px;color:#333;'>
                <h2 style='margin:0 0 16px;color:#c0392b;font-size:20px;'>
                  Reservación cancelada
                </h2>
                <p style='margin:0 0 24px;line-height:1.7;color:#555;'>
                  Hola <strong>{$nombre}</strong>, te confirmamos que tu reservación
                  ha sido cancelada exitosamente.
                </p>
                <div style='background:#fdecea;border-left:4px solid #c0392b;
                            padding:16px;margin:24px 0;border-radius:4px;'>
                  <p style='margin:0 0 6px;font-size:12px;color:#999;
                            text-transform:uppercase;letter-spacing:1px;'>
                    Reservación cancelada
                  </p>
                  <p style='margin:0;font-size:22px;font-weight:bold;color:#c0392b;'>
                    #{$id_reservacion}
                  </p>
                </div>
                <table width='100%' cellpadding='0' cellspacing='0'
                       style='font-size:14px;color:#555;margin:16px 0;'>
                  <tr>
                    <td style='padding:8px 0;border-bottom:1px solid #eee;'>
                      <strong>Habitación:</strong>
                    </td>
                    <td style='padding:8px 0;border-bottom:1px solid #eee;
                                text-align:right;'>{$habitacion}</td>
                  </tr>
                  <tr>
                    <td style='padding:8px 0;'><strong>Fecha de llegada:</strong></td>
                    <td style='padding:8px 0;text-align:right;'>{$fecha_fmt}</td>
                  </tr>
                </table>
                <p style='margin:24px 0 0;font-size:13px;color:#999;line-height:1.6;'>
                  Si tienes alguna duda o crees que esto fue un error, comunícate con
                  nosotros a
                  <a href='mailto:hotelrefugiodelvalle@gmail.com'
                     style='color:#1a3a2a;'>hotelrefugiodelvalle@gmail.com</a>.
                </p>
              </td>
            </tr>
            <tr>
              <td style='background:#f9f9f9;padding:20px 40px;text-align:center;
                         font-size:12px;color:#aaa;border-top:1px solid #eee;'>
                &copy; " . date('Y') . " Hotel Refugio del Valle · Mérida, Yucatán
              </td>
            </tr>
          </table>
        </td></tr>
      </table>
    </body>
    </html>";
}
