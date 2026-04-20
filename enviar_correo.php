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