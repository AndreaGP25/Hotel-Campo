<?php
include $_SERVER['DOCUMENT_ROOT'] . '/campo/config.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $query = '%' . $query . '%';

    try {
        $sql = "SELECT titulo FROM habitaciones WHERE titulo LIKE :query LIMIT 5";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($resultados);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la búsqueda: ' . $e->getMessage()]);
    }
}
