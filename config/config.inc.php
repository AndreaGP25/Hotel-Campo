<?php
/*
===============================================================================
@Sistema de Tutorias Versión: 1.0
@config/config.inc.php
Descripción: Archivo de config/configuración general de los parámetros del sistema
Versión: 1.0
===============================================================================
// Parámetros necesarios para la conexión con la base de datos
*/
$GLOBALS["servidor"] = "sql103.infinityfree.com";
$GLOBALS["usuario"] = "if0_41730483";
$GLOBALS["contrasena"] = "refugiovalle";
$GLOBALS["base_datos"] = "if0_41730483_campo";

// Directorio raiz
$GLOBALS["raiz_sitio"] = "https://hotelrefugiodelvalle.kesug.com/";

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
