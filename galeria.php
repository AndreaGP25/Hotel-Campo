<?php
include $_SERVER['DOCUMENT_ROOT'] . '/public/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/public/autenticacion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Refugio del Valle</title>
    <link rel="stylesheet" href="/public/css/galeria.css">
</head>

<body>
    <main class="contenedor">
        <h2 class="mayusculas ">Galería de fotos</h2>
        <div class="grid">
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel1.avif" alt="imagen 1"
                    onclick="mostrarImagen(this, 0)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel2.avif" alt="imagen 2"
                    onclick="mostrarImagen(this, 1)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel3.avif" alt="imagen 3"
                    onclick="mostrarImagen(this, 2)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel5.avif" alt="imagen 4"
                    onclick="mostrarImagen(this, 4)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel6.avif" alt="imagen 5"
                    onclick="mostrarImagen(this, 5)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel7.avif" alt="imagen 6"
                    onclick="mostrarImagen(this, 6)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel8.avif" alt="imagen 7"
                    onclick="mostrarImagen(this, 7)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel9.avif" alt="imagen 8"
                    onclick="mostrarImagen(this, 8)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel10.avif" alt="imagen 9"
                    onclick="mostrarImagen(this, 9)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel11.avif" alt="imagen 10"
                    onclick="mostrarImagen(this, 10)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel12.avif" alt="imagen 11"
                    onclick="mostrarImagen(this, 11)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel14.avif" alt="imagen 12"
                    onclick="mostrarImagen(this, 13)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel15.avif" alt="imagen 13"
                    onclick="mostrarImagen(this, 14)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="images/galeria-fotos/hotel16.avif" alt="imagen 14"
                    onclick="mostrarImagen(this, 15)">
            </div>
            <div class="grafico grafico--camisas"
                onclick="mostrarImagenGrafico('images/galeria-fotos/hotel4.avif', 3)"></div>
            <div class="grafico grafico--node" onclick="mostrarImagenGrafico('images/galeria-fotos/hotel13.avif', 13)">
            </div>

            <div id="lightbox" class="lightbox" onclick="cerrarLightbox(event)">
                <button id="prevBtn" class="nav-btn" onclick="cambiarImagen(-1)">&#10094;</button>
                <img id="imagenGrande" class="lightbox__imagen" src="" alt="Imagen en grande">
                <button id="nextBtn" class="nav-btn" onclick="cambiarImagen(1)">&#10095;</button>
            </div>
        </div>
    
    </main>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>

</body>

</html>