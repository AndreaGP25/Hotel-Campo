<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: /public/sesiones.php?action=login');
    exit();
}

// Límite de duración de sesión: 48 horas 
define('SESION_MAX_SEGUNDOS', 48 * 3600);   // 172 800 s

// Verificar si la sesión superó las 48 horas
if (isset($_SESSION['login_time'])) {

    $transcurridos = time() - (int) $_SESSION['login_time'];

    if ($transcurridos >= SESION_MAX_SEGUNDOS) {

        // Registrar fecha de fin de sesión en la base de datos
        // Solo si la conexión está disponible
        if (isset($conn) && isset($_SESSION['token'])) {
            try {
                $stmt = $conn->prepare(
                    "UPDATE sesiones SET fecha_fin = ? WHERE token = ? AND fecha_fin IS NULL"
                );
                $stmt->execute([date('Y-m-d H:i:s'), $_SESSION['token']]);
            } catch (PDOException $e) {
                error_log('[Hotel Sesión] Error al cerrar sesión expirada: ' . $e->getMessage());
            }
        }

        // Borrar la sesión de PHP 
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        // Redirigir a login con aviso
        header('Location: /public/sesiones.php?action=login&motivo=sesion_expirada');
        exit();
    }
}
