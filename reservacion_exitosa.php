<?php
include $_SERVER['DOCUMENT_ROOT'] . '/campo/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/campo/autenticacion.php';
$reservaId = $_GET['id'] ?? 'Desconocido'; // Captura dinámica del ID de la reserva
$title = "Confirmación de Reserva"; // Título dinámico
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Refugio del Valle</title>

  <link rel="stylesheet" href="/campo/estilos/estilo-notificacion.css">
</head>

<body>

  <main class="contenedor">  
    <h2>¡Reserva realizada con &eacute;xito!</h2>
      <p class="texto-contenedor">
        Tu reservaci&oacute;n ha sido confirmada.
      </p>
      <p class="texto-contenedor">
        Puedes revisar los detalles en tu historial o regresar a la p&aacute;gina de inicio.
      </p>
      <div class="d-flex">
        <a href="index.php" class="notificacion-btn">Regresar al Inicio</a>
        <a href="/campo/historial_reservaciones.php" class="notificacion-btn">Ver Carrito</a> <!-- Enlace muerto temporalmente -->
      </div>
  </main>
 
  <?php include 'footer.php'; ?>
  

  <script src="funciones/faq.js"></script> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>