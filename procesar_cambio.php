<?php

include 'config/config.inc.php';
session_start();

if (!isset($_SESSION['id_usuario_recuperar']) || !isset($_SESSION['token_valido'])) {
    header("Location: sesiones.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario_recuperar'];
    $token      = $_SESSION['token_valido'];
    $pass       = $_POST['new_password'];
    $confirm    = $_POST['confirm_password'];

    if (strlen($pass) < 8) {
        die("Error: La contraseña debe tener al menos 8 caracteres.");
    }

    if ($pass !== $confirm) {
        die("Error: Las contraseñas no coinciden. Regresa e intenta de nuevo.");
    }

    try {
        $conn->beginTransaction();

        $password_hashed = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
        $stmt->execute([$password_hashed, $id_usuario]);

        $stmt = $conn->prepare("UPDATE tokens_recuperacion SET usado = 1 WHERE token = ?");
        $stmt->execute([$token]);

        $conn->commit();

        unset($_SESSION['id_usuario_recuperar']);
        unset($_SESSION['token_valido']);

        echo "
        <div style='font-family: Arial; text-align: center; margin-top: 100px;'>
            <h2 style='color: #1a3a2a;'>¡Contraseña actualizada con éxito!</h2>
            <p>Ya puedes iniciar sesión con tu nueva clave.</p>
            <p>Redirigiendo al inicio en 3 segundos...</p>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'sesiones.php?action=login';
            }, 3000);
        </script>";

    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error en cambio de clave: " . $e->getMessage());
        die("Ocurrió un error técnico al actualizar la contraseña.");
    }
} else {
    header("Location: sesiones.php");
}
?>