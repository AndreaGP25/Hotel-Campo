<?php
include $_SERVER['DOCUMENT_ROOT'] . '/public/config/config.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $busqueda = '%' . $query . '%';
    $resultados = [];

    try {
        // Buscar en habitaciones
        $sql = "SELECT titulo AS nombre, 'Habitación' AS tipo, id FROM habitaciones 
                WHERE titulo LIKE :query AND disponibilidad = 1 LIMIT 4";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':query', $busqueda, PDO::PARAM_STR);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultados[] = [
                'nombre' => $row['nombre'],
                'tipo'   => $row['tipo'],
                'url'    => '/public/habitacion.php'
            ];
        }

        // Buscar en servicios
        $sql = "SELECT titulo AS nombre, 'Servicio' AS tipo FROM servicios 
                WHERE titulo LIKE :query LIMIT 4";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':query', $busqueda, PDO::PARAM_STR);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultados[] = [
                'nombre' => $row['nombre'],
                'tipo'   => $row['tipo'],
                'url'    => '/public/servicios.php'
            ];
        }

        // Limitar total a 7 resultados
        $resultados = array_slice($resultados, 0, 7);
        header('Content-Type: application/json');
        echo json_encode($resultados);

    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
    }
}