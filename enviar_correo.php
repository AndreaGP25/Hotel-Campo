<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/campo/libs/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/campo/libs/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/campo/libs/PHPMailer/src/SMTP.php';

/**
 * Envía un correo HTML usando PHPMailer.
 *
 * @param string $destinatario  Email del receptor
 * @param string $nombre        Nombre del receptor (para el saludo)
 * @param string $asunto        Asunto del correo
 * @param string $cuerpo_html   Contenido HTML del mensaje
 * @return bool                 true si se envió, false si hubo error
 */
function enviarCorreo(string $destinatario, string $nombre, string $asunto, string $cuerpo_html): bool
{
    $mail = new PHPMailer(true);

    try {
        // Servidor
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USUARIO;
        $mail->Password   = SMTP_CLAVE;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PUERTO;
        $mail->CharSet    = 'UTF-8';

        // Remitente y destinatario
        $mail->setFrom(CORREO_REMITE, NOMBRE_HOTEL);
        $mail->addAddress($destinatario, $nombre);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo_html;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $cuerpo_html));

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('[Hotel Correo] Error al enviar a ' . $destinatario . ': ' . $mail->ErrorInfo);
        return false;
    }
}


/**
 * Genera el HTML del correo de verificación de cuenta.
 *
 * @param string $nombre Nombre del usuario
 * @param string $codigo Código de 6 dígitos
 * @return string        HTML listo para enviar
 */
function plantillaVerificacion(string $nombre, string $codigo): string
{
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
            <!-- Cabecera -->
            <tr>
              <td style='background:#1a3a2a;padding:32px 40px;text-align:center;'>
                <h1 style='margin:0;color:#c8a96e;font-size:22px;letter-spacing:2px;
                           text-transform:uppercase;'>Hotel Refugio del Valle</h1>
              </td>
            </tr>
            <!-- Cuerpo -->
            <tr>
              <td style='padding:40px;color:#333;'>
                <h2 style='margin:0 0 16px;color:#1a3a2a;font-size:20px;'>
                  Verifica tu cuenta, {$nombre}
                </h2>
                <p style='margin:0 0 24px;line-height:1.7;color:#555;'>
                  Gracias por registrarte. Usa el siguiente código para confirmar
                  tu correo electrónico. El código es válido por <strong>15 minutos</strong>.
                </p>
                <!-- Código destacado -->
                <div style='text-align:center;margin:32px 0;'>
                  <span style='display:inline-block;background:#f0f8f0;border:2px dashed #1a3a2a;
                               border-radius:10px;padding:20px 48px;font-size:40px;
                               font-weight:bold;letter-spacing:10px;color:#1a3a2a;'>
                    {$codigo}
                  </span>
                </div>
                <p style='margin:0;font-size:13px;color:#999;line-height:1.6;'>
                  Si no creaste esta cuenta, puedes ignorar este mensaje de forma segura.
                </p>
              </td>
            </tr>
            <!-- Pie -->
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


/**
 * Genera el HTML del correo de confirmación de reservación.
 *
 * @param string $nombre        Nombre del huésped
 * @param int    $id_reservacion ID de la reservación
 * @param string $fecha_llegada  Fecha de check-in
 * @param string $fecha_salida   Fecha de check-out
 * @param float  $monto_total    Monto pagado
 * @param array  $detalles       Array con detalles de habitaciones
 * @return string               HTML listo para enviar
 */
function plantillaConfirmacionReservacion(
    string $nombre,
    int $id_reservacion,
    string $fecha_llegada,
    string $fecha_salida,
    float $monto_total,
    array $detalles = []
): string {
    // Formatear fechas
    $fecha_llegada_formateada = date('d/m/Y', strtotime($fecha_llegada));
    $fecha_salida_formateada = date('d/m/Y', strtotime($fecha_salida));

    // Generar detalles de habitaciones
    $detalles_html = '';
    if (!empty($detalles)) {
        foreach ($detalles as $detalle) {
            $detalles_html .= "<tr style='border-bottom:1px solid #eee;'>
                <td style='padding:12px 0;'>{$detalle['titulo']}</td>
                <td style='padding:12px 0;text-align:right;'>\${$detalle['precio']} x {$detalle['noches']} noche(s)</td>
                <td style='padding:12px 0;text-align:right;font-weight:bold;'>\${$detalle['subtotal']}</td>
            </tr>";
        }
    }

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
            <!-- Cabecera -->
            <tr>
              <td style='background:#1a3a2a;padding:32px 40px;text-align:center;'>
                <h1 style='margin:0;color:#c8a96e;font-size:22px;letter-spacing:2px;
                           text-transform:uppercase;'>Hotel Refugio del Valle</h1>
              </td>
            </tr>
            <!-- Cuerpo -->
            <tr>
              <td style='padding:40px;color:#333;'>
                <h2 style='margin:0 0 16px;color:#1a3a2a;font-size:20px;'>
                  ¡Reservación confirmada, {$nombre}!
                </h2>
                <p style='margin:0 0 24px;line-height:1.7;color:#555;'>
                  Gracias por elegir el Hotel Refugio del Valle. Tu reservación ha sido procesada exitosamente.
                  A continuación encontrarás los detalles de tu estadía.
                </p>

                <!-- Número de reservación -->
                <div style='background:#f0f8f0;border-left:4px solid #1a3a2a;padding:16px;margin:24px 0;border-radius:4px;'>
                  <p style='margin:0;font-size:12px;color:#666;text-transform:uppercase;letter-spacing:1px;'>
                    Número de reservación
                  </p>
                  <p style='margin:8px 0 0;font-size:24px;font-weight:bold;color:#1a3a2a;'>
                    #{$id_reservacion}
                  </p>
                </div>

                <!-- Fechas -->
                <table width='100%' cellpadding='0' cellspacing='0' style='margin:24px 0;'>
                  <tr>
                    <td style='padding:12px;background:#f9f9f9;border-radius:6px 0 0 6px;'>
                      <p style='margin:0;font-size:12px;color:#999;text-transform:uppercase;'>Check-in</p>
                      <p style='margin:8px 0 0;font-size:18px;font-weight:bold;color:#1a3a2a;'>
                        {$fecha_llegada_formateada}
                      </p>
                    </td>
                    <td style='padding:12px;background:#f9f9f9;border-radius:0 6px 6px 0;text-align:right;'>
                      <p style='margin:0;font-size:12px;color:#999;text-transform:uppercase;'>Check-out</p>
                      <p style='margin:8px 0 0;font-size:18px;font-weight:bold;color:#1a3a2a;'>
                        {$fecha_salida_formateada}
                      </p>
                    </td>
                  </tr>
                </table>

                <!-- Detalles de habitaciones y pago -->
                <h3 style='margin:24px 0 12px;color:#1a3a2a;font-size:16px;border-bottom:2px solid #1a3a2a;padding-bottom:8px;'>
                  Detalles del pago
                </h3>
                <table width='100%' cellpadding='0' cellspacing='0' style='margin:16px 0;font-size:13px;'>
                  {$detalles_html}
                  <tr style='background:#f9f9f9;font-weight:bold;'>
                    <td style='padding:12px 0;'>MONTO TOTAL</td>
                    <td colspan='2' style='padding:12px 0;text-align:right;color:#1a3a2a;font-size:18px;'>
                      \${$monto_total}
                    </td>
                  </tr>
                </table>

                <!-- Información importante -->
                <div style='background:#fff3cd;border:1px solid #ffc107;border-radius:6px;padding:16px;margin:24px 0;'>
                  <p style='margin:0;font-size:12px;color:#333;'>
                    <strong>⚠️ Información importante:</strong>
                  </p>
                  <ul style='margin:8px 0 0;padding-left:20px;font-size:12px;color:#555;'>
                    <li>Check-in: 2:00 PM | Check-out: 12:00 PM</li>
                    <li>Presenta este correo o tu número de reservación en recepción</li>
                    <li>Para check-in anticipado o check-out tardío, contacta al hotel</li>
                  </ul>
                </div>

                <!-- Contacto -->
                <p style='margin:24px 0 8px;font-size:13px;color:#666;'>
                  <strong>¿Necesitas ayuda?</strong><br>
                  Teléfono: <a href='tel:+52987123456' style='color:#1a3a2a;text-decoration:none;'>(987) 123-456</a><br>
                  Email: <a href='mailto:info@hotelrefugio.com' style='color:#1a3a2a;text-decoration:none;'>info@hotelrefugio.com</a>
                </p>
              </td>
            </tr>
            <!-- Pie -->
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


/**
 * Genera el HTML del correo de recuperación de contraseña.
 *
 * @param string $nombre Nombre del usuario
 * @param string $enlace URL completa del enlace de restablecimiento
 * @return string        HTML listo para enviar
 */
function plantillaRecuperacion(string $nombre, string $enlace): string
{
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
            <!-- Cabecera -->
            <tr>
              <td style='background:#1a3a2a;padding:32px 40px;text-align:center;'>
                <h1 style='margin:0;color:#c8a96e;font-size:22px;letter-spacing:2px;
                           text-transform:uppercase;'>Hotel Refugio del Valle</h1>
              </td>
            </tr>
            <!-- Cuerpo -->
            <tr>
              <td style='padding:40px;color:#333;'>
                <h2 style='margin:0 0 16px;color:#1a3a2a;font-size:20px;'>
                  Restablece tu contraseña, {$nombre}
                </h2>
                <p style='margin:0 0 24px;line-height:1.7;color:#555;'>
                  Recibimos una solicitud para restablecer la contraseña de tu cuenta.
                  Haz clic en el botón de abajo. El enlace expira en <strong>30 minutos</strong>.
                </p>
                <!-- Botón CTA -->
                <div style='text-align:center;margin:32px 0;'>
                  <a href='{$enlace}'
                     style='display:inline-block;background:#1a3a2a;color:#c8a96e;
                            padding:16px 40px;border-radius:6px;text-decoration:none;
                            font-size:16px;letter-spacing:1px;font-weight:bold;'>
                    Restablecer contraseña
                  </a>
                </div>
                <p style='margin:0 0 8px;font-size:13px;color:#999;'>
                  O copia y pega este enlace en tu navegador:
                </p>
                <p style='margin:0;font-size:12px;color:#aaa;word-break:break-all;'>
                  {$enlace}
                </p>
                <p style='margin:24px 0 0;font-size:13px;color:#999;line-height:1.6;'>
                  Si no solicitaste restablecer tu contraseña, ignora este mensaje.
                  Tu cuenta está segura.
                </p>
              </td>
            </tr>
            <!-- Pie -->
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
?>
