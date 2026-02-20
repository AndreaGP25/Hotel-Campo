<?php
include $_SERVER['DOCUMENT_ROOT'] . '/campo/config.inc.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$usuarioAutenticado = false;
$rolUsuario = null;

if (isset($_SESSION['id_usuario'])) {
  $usuarioAutenticado = true;
  $nombreUsuario = $_SESSION['nombre'];
  $rolUsuario = $_SESSION['rol'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Refugio del Valle</title>
  <link rel="shortcut icon" href="/campo/imagenes/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/campo/estilos/normalize.css">
  <link rel="stylesheet" href="/campo/estilos/index.css">
</head>

<body>
  <header class="navbar navbar-expand-md">
    <div class="container-fluid">
      <a class="margen" href="/campo/index.php">
        <h1>Refugio del valle</h1>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="opciones collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" href="/campo/index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/campo/hotel.php">Hotel</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/campo/habitacion.php">Habitaciones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/campo/reservacion.php">Reservar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/campo/historial_reservaciones.php">Historial</a>
          </li>
        </ul>

        <form class="col-lg-auto mb-3 mb-lg-0 me-lg-2 position-relative" role="search">
          <input type="search" id="busqueda" class="form-control" placeholder="Buscar..." aria-label="Search">
          <ul id="resultados" class="dropdown-menu"></ul>
        </form>


        <div class="d-flex" role="search">
          <?php if ($usuarioAutenticado): ?>
            <?php if ($rolUsuario === 'admin'): ?>
              <!-- Botón Editar para administradores -->
              <button id="btn-editar" class="botones mayusculas"
                onclick="window.location.href='/campo/admin/indexAdmin.php'">
                Editar
              </button>
            <?php else: ?>
              <!-- Saludo para otros usuarios -->
              <span id="saludo">Hola, <?= htmlspecialchars($nombreUsuario); ?></span>
            <?php endif; ?>
            <button id="btn-iniciar" class="botones mayusculas" onclick="window.location.href='/campo/logout.php'">
              Cerrar Sesión
            </button>
          <?php else: ?>
            <!-- Botón para usuarios no autenticados -->
            <button id="btn-iniciar" class="botones mayusculas"
              onclick="window.location.href='/campo/sesiones.php?action=login'">Iniciar Sesión</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script src="funciones/script_buscar.js"></script>

</body>
</html>