<?php

session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: /campo/sesiones.php?action=login');
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/campo/config.inc.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no especificado.");
}

try {
    
    $sql = "SELECT imagen FROM habitaciones WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $habitacion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$habitacion) {
        die("Habitación no encontrada.");
    }

    
    $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . '/campo/' . $habitacion['imagen'];
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen); 
    }

   
    $sql = "DELETE FROM habitaciones WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);

    
    header("Location: /campo/admin/indexAdmin.php");
    exit;
} catch (PDOException $e) {
    die("Error al eliminar la habitación: " . $e->getMessage());
}
?>
