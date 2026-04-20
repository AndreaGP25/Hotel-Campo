<?php
session_start();
include 'config.inc.php';
include 'enviar_correo.php';

$email_usuario = $_SESSION['email_pendiente_verificacion'] ?? 'tu correo';
$mensaje = '';
$tipo    = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- LÓGICA DE REENVÍO ---
    if (isset($_POST['reenviar'])) {
        if (!$email_usuario || $email_usuario === 'tu correo') {
            $mensaje = 'Sesión expirada. Por favor regístrate de nuevo.';
            $tipo    = 'error';
        } else {
            $nuevo_codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $nueva_expira = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            $stmt = $conn->prepare("UPDATE usuarios SET codigo_verificacion = ?, codigo_expira = ? WHERE email = ? AND verificado = 0");
            $stmt->execute([$nuevo_codigo, $nueva_expira, $email_usuario]);

            $stmtNombre = $conn->prepare("SELECT nombre FROM usuarios WHERE email = ?");
            $stmtNombre->execute([$email_usuario]);
            $fila = $stmtNombre->fetch(PDO::FETCH_ASSOC);
            $nombre_usuario = $fila ? $fila['nombre'] : 'Usuario';

            $html = plantillaVerificacion($nombre_usuario, $nuevo_codigo);
            $enviado = enviarCorreo($email_usuario, $nombre_usuario, 'Nuevo código de verificación — Hotel Refugio del Valle', $html);

            $mensaje = $enviado ? 'Se ha reenviado un nuevo código.' : 'Error al enviar.';
            $tipo = $enviado ? 'exito' : 'error';
        }
    } 
    // --- LÓGICA DE VERIFICACIÓN (Aquí estaba el error) ---
    else {
        $codigo_input = $_POST['codigo_completo'] ?? '';

        $stmt = $conn->prepare("SELECT id_usuario, codigo_verificacion, codigo_expira FROM usuarios WHERE email = ? AND verificado = 0");
        $stmt->execute([$email_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $mensaje = 'No hay una cuenta pendiente para este correo.';
            $tipo    = 'error';
        } elseif ($usuario['codigo_verificacion'] !== $codigo_input) {
            $mensaje = 'El código ingresado es incorrecto.';
            $tipo    = 'error';
        } elseif (new DateTime() > new DateTime($usuario['codigo_expira'])) {
            $mensaje = 'El código ha expirado. Solicita uno nuevo.';
            $tipo    = 'error';
        } else {
            // ÉXITO: Marcamos como verificado
            $upd = $conn->prepare("UPDATE usuarios SET verificado = 1, codigo_verificacion = NULL, codigo_expira = NULL WHERE id_usuario = ?");
            $upd->execute([$usuario['id_usuario']]);

            unset($_SESSION['email_pendiente_verificacion']);
            $mensaje = '¡Cuenta activada con éxito! Redirigiendo...';
            $tipo    = 'exito';
            header("refresh:3;url=sesiones.php?action=login");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Cuenta | Hotel Refugio del Valle</title>
    <link rel="stylesheet" href="estilos/estilo-sesion.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f4f4; font-family: 'Montserrat', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .contenedor-verificacion { background: white; padding: 40px; border-radius: 10px; box-shadow: 0px 10px 25px rgba(0,0,0,0.1); text-align: center; max-width: 450px; width: 90%; }
        .logo-verificar { width: 80px; margin-bottom: 20px; }
        h2 { color: #1a3a2a; margin-bottom: 10px; }
        p { color: #666; font-size: 14px; line-height: 1.6; }
        .email-resaltado { color: #c8a96e; font-weight: bold; }
        .inputs-codigo { display: flex; justify-content: center; gap: 10px; margin: 30px 0; }
        .input-digito { width: 45px; height: 55px; font-size: 24px; text-align: center; border: 2px solid #ddd; border-radius: 8px; transition: border-color 0.3s; }
        .input-digito:focus { border-color: #c8a96e; outline: none; }
        .btn-verificar { background: #1a3a2a; color: #c8a96e; border: none; padding: 15px 30px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; transition: background 0.3s; }
        .btn-verificar:hover { background: #2a5a42; }
        .mensaje { margin-top: 20px; padding: 10px; border-radius: 5px; font-size: 14px; }
        .error { background: #f8d7da; color: #721c24; }
        .exito { background: #d4edda; color: #155724; }
    </style>
</head>
<body>

<div class="contenedor-verificacion">
    <img src="imagenes/logo.png" alt="Hotel Logo" class="logo-verificar">
    <h2>Verifica tu cuenta</h2>
    <p>Enviamos un código de 6 dígitos a <br><span class="email-resaltado"><?= htmlspecialchars($email_usuario) ?></span>.<br>Ingrésalo aquí para activar tu cuenta.</p>

    <?php if ($mensaje): ?>
        <div class="mensaje <?= $tipo ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST" id="form-verificacion">
        <div class="inputs-codigo">
            <input type="text" class="input-digito" maxlength="1" required>
            <input type="text" class="input-digito" maxlength="1" required>
            <input type="text" class="input-digito" maxlength="1" required>
            <input type="text" class="input-digito" maxlength="1" required>
            <input type="text" class="input-digito" maxlength="1" required>
            <input type="text" class="input-digito" maxlength="1" required>
        </div>
        
        <input type="hidden" name="codigo_completo" id="codigo_completo">
        
        <button type="submit" class="btn-verificar">VERIFICAR CUENTA</button>
    </form>

    <form method="POST" style="margin-top: 20px;">
        <p>¿No recibiste el código? 
            <button type="submit" name="reenviar" style="background:none; border:none; color:#c8a96e; cursor:pointer; font-weight:bold; text-decoration:underline;">Reenviar código</button>
        </p>
    </form>
</div>

<script>
    const inputs = document.querySelectorAll('.input-digito');
    const oculto = document.getElementById('codigo_completo');
    const formulario = document.getElementById('form-verificacion');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            actualizarOculto();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    function actualizarOculto() {
        let codigo = "";
        inputs.forEach(i => codigo += i.value);
        oculto.value = codigo;
    }

    formulario.addEventListener('submit', (e) => {
        actualizarOculto();
        if (oculto.value.length !== 6) {
            e.preventDefault();
            alert("Por favor completa los 6 dígitos.");
        }
    });
</script>

</body>
</html>
