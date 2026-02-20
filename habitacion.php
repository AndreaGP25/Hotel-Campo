<?php
include $_SERVER['DOCUMENT_ROOT'] . '/campo/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/campo/autenticacion.php';

try {
  $sql = "SELECT titulo, imagen, descripcion, precio, categoria FROM habitaciones WHERE disponibilidad = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error al cargar las habitaciones: " . $e->getMessage();
  $habitaciones = [];
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Refugio del Valle</title>
  <link rel="stylesheet" href="estilos/estilo-habitacion.css">
</head>

<body>
  <main>
    <section class="texto-habitaciones">
      <div class="titulo-habitaciones">Descubre nuestras habitaciones</div>
      <div class="p-habitaciones">En el Hotel Refugio del Valle, contamos con habitaciones de diferentes tamaños y
        estilos para que puedas
        disfrutar de una estancia agradable y cómoda. Todas nuestras habitaciones cuentan con servicios de calidad y
        comodidades para que te sientas como en casa.</div>
    </section>

    <section class="habitaciones">
      <div class="habitacion">
        <img src="/campo/imagenes/habitacion1.jpg" alt="Habitación Standard">
        <h2>Habitación Estándar</h2>
        <p>Ideal para un máximo de 4 personas</p>
        <ul>
          <h3>Características:</h3>
          <li>Aire acondicionado</li>
          <li>Ventilador de techo</li>
          <li>Internet inalámbrico (Wi-Fi)</li>
          <li>Televisión por cable</li>
          <li>Secador de pelo</li>
          <li>Enchufes de 110-120 voltios</li>
          <li>Servicios de aseo</li>
          <li>Cunas bajo petición</li>
        </ul>
      </div>
      <div class="habitacion">
        <img src="/campo/imagenes/habitacion2.jpg" alt="Habitación Suite">
        <h2>Habitación Superior </h2>
        <p>Ideal para un máximo de 4 personas</p>
        <ul>
          <h3>Caracteristicas:</h3>
          <li>Aire acondicionado</li>
          <li>Ventilador de techo</li>
          <li>Internet inalámbrico (Wi-Fi)</li>
          <li>Televisión por cable</li>
          <li>Secador de pelo</li>
          <li>Minibar</li>
          <li>Enchufes de 110-120 voltios</li>
          <li>Enchufes de 220-240 voltios</li>
          <li>Servicios de aseo</li>
          <li>Cunas bajo petición</li>
        </ul>
      </div>
    </section>

    <section class="habitaciones-dinamicas">
      <h2 class="titulo-dinamicas">Habitaciones disponibles:</h2>
      <div class="contenedor-habitaciones">
        <?php if (empty($habitaciones)): ?>
          <p>No hay habitaciones disponibles en este momento. Por favor, vuelve más tarde.</p>
        <?php else: ?>
          <?php foreach ($habitaciones as $habitacion): ?>
            <div class="habitacion">
              <img src="<?= htmlspecialchars($habitacion['imagen']) ?>"
                alt="<?= htmlspecialchars($habitacion['titulo']) ?>">
              <h2><?= htmlspecialchars($habitacion['titulo']) ?></h2>
              <p><?= htmlspecialchars($habitacion['descripcion']) ?></p>
              <p class="precio">$<?= number_format($habitacion['precio'], 2) ?> por noche</p>
              <p class="categoria"><?= htmlspecialchars($habitacion['categoria']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

  </main>


  <?php include 'footer.php'; ?>

  <script src="funciones/script.js"></script>
</body>

</html>