<?php
/*
===============================================================================
@Sistema de Tutorias Versión: 1.0
@config.inc.php
Descripción: Archivo de configuración general de los parámetros del sistema
Versión: 1.0
===============================================================================
//Parámetros necesarios para la conexión con la base de datos
*/
$GLOBALS["servidor"] = "localhost";
$GLOBALS["usuario"] = "root";
$GLOBALS["contrasena"] = "";
$GLOBALS["base_datos"] = "campo";

//Directorio raiz
$GLOBALS["raiz_sitio"] = "http://localhost/campo/";

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
