<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: /campo/sesiones.php?action=login');
    exit();
}
?>

