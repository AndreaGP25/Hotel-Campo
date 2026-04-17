<?php

include 'config.inc.php';
include 'enviar_correo.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sesiones.php?action=register');
    exit();
}

$nombre           = trim(htmlspecialchars($_POST['nombre']));
$email            = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$telefono         = htmlspecialchars($_POST['telefono']);
$password         = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$rol              = 'prospecto';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirigirError('El correo electrónico no tiene un formato válido.');
}

if ($password !== $confirm_password) {
    redirigirError('Las contraseñas no coinciden.');
}

if (strlen($password) < 8) {
    redirigirError('La contraseña debe tener al menos 8 caracteres.');
}

//Verificar si el correo ya existe 
$stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    redirigirError('Este correo electrónico ya está registrado.');
}

// Preparar datos para inserción 
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$codigo          = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expira_en       = date('Y-m-d H:i:s', strtotime('+15 minutes'));

try {
    // IMPORTANTE: verificado inicia en 0
    $sql = "INSERT INTO usuarios (nombre, email, telefono, password, rol, verificado, codigo_verificacion, codigo_expira) 
            VALUES (?, ?, ?, ?, ?, 0, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nombre, $email, $telefono, $password_hashed, $rol, $codigo, $expira_en]);

    $id_nuevo_usuario = $conn->lastInsertId();

} catch (PDOException $e) {
    error_log('[Hotel Registro] Error BD: ' . $e->getMessage());
    redirigirError('Error al guardar el registro. Por favor intenta de nuevo.');
}

//Enviar correo (SIMULADO)
$html_correo = plantillaVerificacion($nombre, $codigo);

$enviado = enviarCorreo($email, $nombre, 'Verifica tu cuenta — Hotel Refugio del Valle', $html_correo);

if (!$enviado) {
    error_log("[Hotel] No se pudo enviar correo a $email, pero el usuario fue creado.");
}

//Guardar email en sesión para usarlo en verificar_cuenta.php
session_start();
$_SESSION['email_pendiente_verificacion'] = $email;

//Redirigir a la pantalla de verificación 
header('Location: verificar_cuenta.php');
exit();


function redirigirError(string $msg): void
{
    header('Location: sesiones.php?action=register&error=' . urlencode($msg));
    exit();
}
?>
