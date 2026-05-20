<?php
/*
===============================================================================
@Sistema Hotel Refugio del Valle
@config/config.inc.php
Descripción: Archivo de configuración general de los parámetros del sistema
===============================================================================
*/

// Parámetros necesarios para la conexión con la base de datos
$GLOBALS["servidor"]   = "sql103.infinityfree.com";
$GLOBALS["usuario"]    = "if0_41730483";
$GLOBALS["contrasena"] = "refugiovalle";
$GLOBALS["base_datos"] = "if0_41730483_campo";

// Directorio raíz
$GLOBALS["raiz_sitio"] = "https://hotelrefugiodelvalle.kesug.com/";

// Parámetros para el envío de correos
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_USUARIO',  'hotelrefugiodelvalle@gmail.com');
define('SMTP_CLAVE',    'nudt aoys otxw bhhb');
define('SMTP_PUERTO',    587);
define('CORREO_REMITE', 'hotelrefugiodelvalle@gmail.com');
define('NOMBRE_HOTEL',  'Hotel Refugio del Valle');

try {
    $conn = new PDO(
        "mysql:host=" . $GLOBALS["servidor"] . ";dbname=" . $GLOBALS["base_datos"] . ";charset=utf8",
        $GLOBALS["usuario"],
        $GLOBALS["contrasena"]
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

if (mt_rand(1, 100) === 1) {
    try {
        // Limpiar sesiones expiradas
        $vigencia = date('Y-m-d H:i:s', strtotime('-2 days'));
        $conn->exec("DELETE FROM sesiones WHERE fecha_inicio < '$vigencia'");
        
        // Limpiar tokens de recuperación expirados
        $conn->exec(
            "DELETE FROM tokens_recuperacion
              WHERE expira_en < NOW()"
        );

    } catch (PDOException $e) {
        error_log('[Hotel P1] Error en depuración automática: ' . $e->getMessage());
    }
}
?>
