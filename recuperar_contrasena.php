<?php
include 'config/config.inc.php';
include 'enviar_correo.php';
session_start();

$mensaje = '';
$mostrar_modal = false;

// PROCESAR SOLICITUD DE ENLACE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Obtener el ID y el nombre del usuario solo si está verificado
    $stmt = $conn->prepare("SELECT id_usuario, nombre FROM usuarios WHERE email = ? AND verificado = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        // Guardar token en la base de datos
        $stmt = $conn->prepare("INSERT INTO tokens_recuperacion (id_usuario, token, expira_en) VALUES (?, ?, ?)");
        $stmt->execute([$user['id_usuario'], $token, $expira]);
        
        // Enviar el url de recuperación
        $enlace = "http://" . $_SERVER['HTTP_HOST'] . "/public/recuperar_contrasena.php?token=" . $token;
        $html = plantillaRecuperacion($user['nombre'], $enlace);
        $enviado = enviarCorreo($email, $user['nombre'], 'Restablece tu contraseña — Hotel Refugio del Valle', $html);

        if ($enviado) {
            $mensaje = "Si ese correo está registrado recibirás un enlace en unos minutos. Revisa también tu carpeta de spam.";
            $mostrar_modal = true; 
        } else {
            $mensaje = "Hubo un error al enviar el correo. Por favor, intenta más tarde.";
        }
    } else {
        $mensaje = "Si ese correo está registrado recibirás un enlace en unos minutos.";
    }
}

// PROCESAR VALIDACIÓN DE TOKEN
if (isset($_GET['token'])) {
    $token_input = $_GET['token'];
    
    $stmt = $conn->prepare("SELECT id_usuario FROM tokens_recuperacion WHERE token = ? AND usado = 0 AND expira_en > NOW()");
    $stmt->execute([$token_input]);
    $t = $stmt->fetch();

    if ($t) {
        $_SESSION['token_valido'] = $token_input;
        $_SESSION['id_usuario_recuperar'] = $t['id_usuario'];
        $vista_nueva_clave = true;
    } else {
        $mensaje = "El token es inválido o ha expirado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Hotel Refugio del Valle</title>
    <link rel="stylesheet" href="css/estilo-sesion.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #f4f4f4; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .contenedor-recuperar { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; width: 100%; max-width: 450px; }
        .alerta-azul { background-color: #e3f2fd; border: 1px solid #90caf9; color: #0d47a1; padding: 15px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .llave-icono { width: 60px; margin-bottom: 10px; opacity: 0.8; }
        h2 { color: #c8a96e; font-size: 22px; margin-bottom: 10px; }
        .btn-dorado { background: #1a3a2a; color: #c8a96e; border: none; padding: 12px; width: 100%; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 15px; text-transform: uppercase; }
        .modal { display: <?= $mostrar_modal ? 'flex' : 'none' ?>; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 35px; border-radius: 12px; width: 90%; max-width: 380px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.3); }
        .input-token { width: 100%; padding: 12px; margin: 20px 0; border: 2px solid #c8a96e; border-radius: 6px; text-align: center; font-size: 16px; box-sizing: border-box; }
    </style>
</head>
<body>

    <div class="contenedor-recuperar">
        <img src="images/logo.png" style="width: 100px; margin-bottom: 25px;">
        
        <?php if ($mensaje): ?>
            <div class="alerta-azul"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if (!isset($vista_nueva_clave)): ?>
            <img src="https://cdn-icons-png.flaticon.com/512/565/565547.png" class="llave-icono">
            <h2>¿Olvidaste tu contraseña?</h2>
            <p style="color: #666; font-size: 14px;">Escribe el correo electrónico de tu cuenta y te enviaremos un enlace para restablecer tu contraseña.</p>

            <form method="POST">
                <input type="email" name="email" placeholder="marianacabbel@gmail.com" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 15px; box-sizing: border-box;">
                <button type="submit" class="btn-dorado">ENVIAR ENLACE</button>
            </form>
            <a href="sesiones.php" style="display: block; margin-top: 20px; color: #888; text-decoration: none; font-size: 13px;">← Regresar al inicio</a>

        <?php else: ?>
            <img src="https://cdn-icons-png.flaticon.com/512/6195/6195699.png" class="llave-icono">
            <h2>Nueva contraseña</h2>
            <p style="color: #666; font-size: 14px;">Elige una contraseña segura para tu cuenta.</p>
            <form action="procesar_cambio.php" method="POST">
                <input type="password" name="new_password" placeholder="Nueva contraseña" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 15px; box-sizing: border-box;">
                <p style="font-size: 11px; color: #999; text-align: left; margin: 5px 0 15px;">Mínimo 8 caracteres. Más segura con mayúsculas, números y símbolos.</p>
                <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
                <button type="submit" class="btn-dorado">GUARDAR NUEVA CONTRASEÑA</button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
