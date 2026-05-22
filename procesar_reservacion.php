<?php
<<<<<<< Updated upstream
include 'config/config.inc.php';
=======
include 'config.inc.php';
>>>>>>> Stashed changes
include 'enviar_correo.php';

// Verificar el estado de la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: sesiones.php?action=login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos de la reservación
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha_llegada = $_POST['fecha_llegada'];
    $fecha_salida = $_POST['fecha_salida'];
    $habitaciones = array_filter([
        $_POST['tipo_habitacion_1'] ?? null,
        $_POST['tipo_habitacion_2'] ?? null,
        $_POST['tipo_habitacion_3'] ?? null,
    ]);

    // Consulta la disponibilidad de cada habitación
$disponibilidades = [];
$sql = "SELECT id, disponibilidad FROM habitaciones";
$result = $conn->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $disponibilidades[$row['id']] = $row['disponibilidad'];
}

// Contar selecciones del usuario
$selecciones = array_count_values($habitaciones);

// Validar disponibilidad
foreach ($selecciones as $id => $cantidadSeleccionada) {
    if ($cantidadSeleccionada > $disponibilidades[$id]) {
        die("Error: Solo hay {$disponibilidades[$id]} habitación(es) disponibles para el tipo de habitación seleccionada.");
    }
}

    // **CONTINÚA EL PROCESO SI TODO ES VÁLIDO**

    // Calcular la duración de la estancia
    $inicio = new DateTime($fecha_llegada);
    $fin = new DateTime($fecha_salida);
    $dias = $inicio->diff($fin)->days;

    if ($dias < 1) {
        die("La duración de la estancia debe ser al menos de 1 día.");
    }

    // Calcular el monto total basado en los precios de las habitaciones
	$monto = 0;
	$datosHabitaciones = []; // guardar datos para el correo

	foreach ($habitaciones as $habitacionId) {
    	$sql = "SELECT precio, titulo FROM habitaciones WHERE id = :id";
    	$stmt = $conn->prepare($sql);
    	$stmt->execute([':id' => $habitacionId]);
    	$habitacion = $stmt->fetch(PDO::FETCH_ASSOC);

    	$precio = $habitacion['precio'];
    	$monto += $precio * $dias;

    	$datosHabitaciones[$habitacionId] = [ // guardar snapshot
        	'titulo' => $habitacion['titulo'],
        	'precio' => $precio,
    	];
	}

    // Capturar datos de pago
    $nombre_titular = $_POST['nombre_titular'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $fecha_expiracion = $_POST['fecha_expiracion'];
    $cvv = $_POST['cvv'];
	
	
	$fecha_expiracion = $_POST['fecha_expiracion'];
	$fecha = DateTime::createFromFormat('Y-m', $fecha_expiracion);
	$fecha->modify('last day of this month');
	$fecha_expiracion = $fecha->format('Y-m-d');

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Procesar reservación
        foreach ($habitaciones as $habitacionId) {
            $sql = "INSERT INTO reservaciones (habitacion_id, nombre, email, telefono, fecha_llegada, fecha_salida, fecha_reservacion) 
                    VALUES (:habitacion_id, :nombre, :email, :telefono, :fecha_llegada, :fecha_salida, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':habitacion_id' => $habitacionId,
                ':nombre' => $nombre,
                ':email' => $email,
                ':telefono' => $telefono,
                ':fecha_llegada' => $fecha_llegada,
                ':fecha_salida' => $fecha_salida,
            ]);

            // Obtener ID de la reservación
            $reservacion_id = $conn->lastInsertId();

            // Actualizar disponibilidad de la habitación
            $sql = "UPDATE habitaciones SET disponibilidad = disponibilidad - 1 WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $habitacionId]);
        }

        // Procesar pago
        $sql = "INSERT INTO pagos (reservacion_id, nombre_titular, numero_tarjeta, fecha_expiracion, cvv, monto) 
                VALUES (:reservacion_id, :nombre_titular, :numero_tarjeta, :fecha_expiracion, :cvv, :monto)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':reservacion_id' => $reservacion_id,
            ':nombre_titular' => $nombre_titular,
            ':numero_tarjeta' => $numero_tarjeta,
            ':fecha_expiracion' => $fecha_expiracion,
            ':cvv' => $cvv,
            ':monto' => $monto,
        ]);

        // Confirmar transacción
        $conn->commit();

        // Preparar detalles de habitaciones para el correo
		$detallesHabitaciones = [];
		foreach ($habitaciones as $habitacionId) {
   			$datos = $datosHabitaciones[$habitacionId];

    		$detallesHabitaciones[] = [
        		'titulo'   => $datos['titulo'],
        		'precio'   => $datos['precio'],
        		'noches'   => $dias,
        		'subtotal' => $datos['precio'] * $dias
    		];
		}

        // Enviar correo de confirmación
        $asunto = "Confirmación de Reservación - Hotel Refugio del Valle";
        $cuerpoHTML = plantillaConfirmacionReservacion(
            $nombre,
            $reservacion_id,
            $fecha_llegada,
            $fecha_salida,
            $monto,
            $detallesHabitaciones
        );
        
        enviarCorreo($email, $nombre, $asunto, $cuerpoHTML);

        // Redirigir al éxito
        header("Location: /public/reservacion_exitosa.php");
        exit;
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $conn->rollBack();
        die("Error al procesar la reservación y el pago: " . $e->getMessage());
    }
}
?>
