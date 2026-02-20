<?php
include $_SERVER['DOCUMENT_ROOT'] . '/campo/header.php';
$title = "Recuperación de Contraseña"; // Título dinámico
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer Contraseña - Hotel Refugio del Valle</title>

  <link rel="stylesheet" href="/campo/estilos/estilo-notificacion.css">
</head>

<body>
<main class="contenedor">
    <h2>Restablecer Contraseña</h2>
    <p class="texto-contenedor">
      Ingresa tu correo electrónico y una nueva contraseña para restablecer tu acceso.
    </p>
    <form id="form-restablecer" class="formulario">
      <label for="email" class="label">Correo Electrónico:</label>
      <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" required>

      <label for="nueva-contrasena" class="label">Nueva Contraseña:</label>
      <input type="password" id="nueva-contrasena" name="nueva-contrasena" placeholder="Ingresa tu nueva contraseña" required>

      <label for="confirmar-contrasena" class="label">Confirmar Contraseña:</label>
      <input type="password" id="confirmar-contrasena" name="confirmar-contrasena" placeholder="Confirma tu nueva contraseña" required>

      <button type="submit" class="notificacion-btn">Restablecer Contraseña</button>
    </form>
    <p id="mensaje" class="texto-contenedor mensaje"></p>
    <div class="d-flex">
      <a href="/campo/index.php" class="notificacion-btn">Regresar al Inicio</a>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="/campo/funciones/script_restablecer.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>