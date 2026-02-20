<?php
include $_SERVER['DOCUMENT_ROOT'] . '/campo/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/campo/autenticacion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Refugio del Valle</title>
  <link rel="stylesheet" href="estilos/estilo-faqs.css">
</head>

<body>

  <main>
    <section class="faq-wrapper">
      <section id="faq" class="faq-section">
        <div class="faq-container">
          <h2>Preguntas Frecuentes</h2>
          <div id="accordionFAQ" class="accordion"></div>
        </div>
      </section>


      <div id="faq-contacto" class="faq-contacto-section">
        <div class="faq-contacto-container">
          <h2 class="contacto-titulo mayusculas">Atenci&oacute;n al cliente</h2>
          <p class="contacto-texto">hotelrefugiovalle@gmail.com</p>
          <p class="contacto-texto">+52 999 127 2412</p>
        </div>
      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script src="/campo/funciones/faq.js"></script>
</body>

</html>