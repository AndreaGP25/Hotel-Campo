<?php
include 'config.inc.php';

$tipo = $_GET['tipo'] ?? null;

if ($tipo) {
    $sql = "SELECT id, titulo FROM habitaciones WHERE categoria = :categoria AND disponibilidad = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':categoria' => $tipo]);
    $habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($habitaciones);
}
?>


