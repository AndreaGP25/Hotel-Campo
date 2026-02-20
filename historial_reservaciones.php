<?php
include $_SERVER['DOCUMENT_ROOT'] . '/campo/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/campo/autenticacion.php';

$id_usuario = $_SESSION['id_usuario'];

try {
    // Consulta para obtener el historial de reservaciones del usuario
    $sql = "SELECT r.nombre, r.email, r.telefono, r.fecha_llegada, r.fecha_salida, r.fecha_reservacion, h.titulo AS habitacion
            FROM reservaciones r
            JOIN habitaciones h ON r.habitacion_id = h.id
            WHERE r.email = (SELECT email FROM usuarios WHERE id_usuario = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario]);

    // Obtener resultados
    $reservaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener el historial de reservaciones: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Reservaciones</title>
    <link rel="stylesheet" href="/campo/estilos/estilo-historial.css">
</head>

<body>
    <div class="overlay"></div>
    <main class="contenedor-historial">
        <h1>Historial de Reservaciones</h1>
        <?php if (empty($reservaciones)): ?>
            <p class="mensaje-sin-datos">No tienes reservaciones registradas.</p>
        <?php else: ?>
            <table class="tabla-historial">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Habitación</th>
                        <th>Fecha Llegada</th>
                        <th>Fecha Salida</th>
                        <th>Fecha Reservación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservaciones as $reservacion): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservacion['nombre']); ?></td>
                            <td><?= htmlspecialchars($reservacion['email']); ?></td>
                            <td><?= htmlspecialchars($reservacion['telefono'] ?: 'N/A'); ?></td>
                            <td><?= htmlspecialchars($reservacion['habitacion']); ?></td>
                            <td><?= htmlspecialchars($reservacion['fecha_llegada']); ?></td>
                            <td><?= htmlspecialchars($reservacion['fecha_salida']); ?></td>
                            <td><?= htmlspecialchars($reservacion['fecha_reservacion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>


</html>