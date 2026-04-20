<?php
/*
===============================================================================
@Sistema de Tutorias Versión: 1.0
@config.inc.php
Descripción: Archivo de configuración general de los parámetros del sistema
Versión: 1.0
===============================================================================
// Parámetros necesarios para la conexión con la base de datos
*/
$GLOBALS["servidor"] = "localhost";
$GLOBALS["usuario"] = "root";
$GLOBALS["contrasena"] = "";
$GLOBALS["base_datos"] = "campo";

// Directorio raiz
$GLOBALS["raiz_sitio"] = "http://localhost/campo/";

// Parámetros para el envío de correos
define('SMTP_HOST',    'smtp.gmail.com');
define('SMTP_USUARIO', 'hotelrefugiodelvalle@gmail.com');
define('SMTP_CLAVE',   'nudt aoys otxw bhhb');
define('SMTP_PUERTO',   587);
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
?>
