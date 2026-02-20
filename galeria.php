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
    <link rel="stylesheet" href="/campo/estilos/galeria.css">
</head>

<body>
    <main class="contenedor">
        <h2 class="mayusculas ">Galería de fotos</h2>
        <div class="grid">
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel1.jpg" alt="imagen 1"
                    onclick="mostrarImagen(this, 0)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel2.jpg" alt="imagen 2"
                    onclick="mostrarImagen(this, 1)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel3.jpg" alt="imagen 3"
                    onclick="mostrarImagen(this, 2)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel5.jpg" alt="imagen 4"
                    onclick="mostrarImagen(this, 4)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel6.jpg" alt="imagen 5"
                    onclick="mostrarImagen(this, 5)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel7.jpg" alt="imagen 6"
                    onclick="mostrarImagen(this, 6)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel8.jpg" alt="imagen 7"
                    onclick="mostrarImagen(this, 7)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel9.jpg" alt="imagen 8"
                    onclick="mostrarImagen(this, 8)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel10.jpg" alt="imagen 9"
                    onclick="mostrarImagen(this, 9)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel11.jpg" alt="imagen 10"
                    onclick="mostrarImagen(this, 10)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel12.jpg" alt="imagen 11"
                    onclick="mostrarImagen(this, 11)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel14.jpg" alt="imagen 12"
                    onclick="mostrarImagen(this, 13)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel15.jpg" alt="imagen 13"
                    onclick="mostrarImagen(this, 14)">
            </div>
            <div class="producto">
                <img class="producto__imagen" src="imagenes/galeria-fotos/hotel16.jpg" alt="imagen 14"
                    onclick="mostrarImagen(this, 15)">
            </div>
            <div class="grafico grafico--camisas"
                onclick="mostrarImagenGrafico('imagenes/galeria-fotos/hotel4.jpg', 3)"></div>
            <div class="grafico grafico--node" onclick="mostrarImagenGrafico('imagenes/galeria-fotos/hotel13.jpg', 13)">
            </div>

            <div id="lightbox" class="lightbox" onclick="cerrarLightbox(event)">
                <button id="prevBtn" class="nav-btn" onclick="cambiarImagen(-1)">&#10094;</button>
                <img id="imagenGrande" class="lightbox__imagen" src="" alt="Imagen en grande">
                <button id="nextBtn" class="nav-btn" onclick="cambiarImagen(1)">&#10095;</button>
            </div>
        </div>
    
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <script src="funciones/script.js"></script>

</body>

</html>