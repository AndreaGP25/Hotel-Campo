<?php
include 'config/config.inc.php';
include 'enviar_correo.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sesiones.php?action=register');
    exit();
}

// Recogida y limpieza de datos
$nombre   = trim(htmlspecialchars($_POST['nombre']));
$email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$telefono = preg_replace('/[^0-9]/', '', $_POST['telefono']); 
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$rol      = 'prospecto';

// VALIDACIONES INICIALES

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirigirError('El correo electrónico no tiene un formato válido.');
}

if (strlen($telefono) !== 10) {
    redirigirError('El número telefónico debe tener exactamente 10 dígitos.');
}

if ($password !== $confirm_password) {
    redirigirError('Las contraseñas no coinciden.');
}

if (strlen($password) < 8) {
    redirigirError('La contraseña debe tener al menos 8 caracteres.');
}

// LÓGICA DE BASE DE DATOS

try {
    // Verificar si el correo ya existe
    $stmt = $conn->prepare("SELECT id_usuario, verificado FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuarioExistente = $stmt->fetch();

    // Generar datos de verificación
    $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expira_en = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    if ($usuarioExistente) {
        if ($usuarioExistente['verificado'] == 1) {
            redirigirError('Este correo electrónico ya está registrado y verificado.');
        } else {
            // REUTILIZAR CUENTA: Si existe pero no está verificado, actualizamos sus datos
            $sql = "UPDATE usuarios SET nombre=?, telefono=?, password=?, codigo_verificacion=?, codigo_expira=?, rol=? WHERE id_usuario=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $telefono, $password_hashed, $codigo, $expira_en, $rol, $usuarioExistente['id_usuario']]);
        }
    } else {
        // INSERTAR NUEVA CUENTA
        $sql = "INSERT INTO usuarios (nombre, email, telefono, password, rol, verificado, codigo_verificacion, codigo_expira) 
                VALUES (?, ?, ?, ?, ?, 0, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $email, $telefono, $password_hashed, $rol, $codigo, $expira_en]);
    }

    // ENVÍO DE CORREO

    $html_correo = plantillaVerificacion($nombre, $codigo);
    $enviado = enviarCorreo($email, $nombre, 'Verifica tu cuenta — Hotel Refugio del Valle', $html_correo);

    if (!$enviado) {
        error_log("[Hotel] Fallo envío de correo a: $email. El usuario fue guardado pero no recibió el código.");
    }

    // Iniciar sesión para que verificar_cuenta.php sepa a quién validar
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['email_pendiente_verificacion'] = $email;
    
    header('Location: verificar_cuenta.php');
    exit();

} catch (PDOException $e) {
    error_log('[Hotel Registro] Error BD: ' . $e->getMessage());
    redirigirError('Error técnico en el registro. Intenta más tarde.');
}

function redirigirError(string $msg): void {
    header('Location: sesiones.php?action=register&error=' . urlencode($msg));
    exit();
}