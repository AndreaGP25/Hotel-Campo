<?php
session_start();
include 'config.inc.php'; 

if (isset($_SESSION['token'])) {

    $sql = "UPDATE sesiones SET fecha_fin = ? WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([date('Y-m-d H:i:s'), $_SESSION['token']]);
}


session_destroy();
header("Location: index.php"); 
exit();
?>