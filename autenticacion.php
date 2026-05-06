<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: /public/sesiones.php?action=login');
    exit();
}
?>

