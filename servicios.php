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
    <link rel="stylesheet" href="/campo/estilos/estilo-servicios.css">
</head>

<body>

    <main>
        <div class="background-wrapper">
            <div class="content">

                <h3 class="titulo" >Nuestros servicios</h3>

                <section class="services-container">
                    <!-- Servicio 1 -->
                    <div class="service-card">
                        <img src="imagenes/servicio2.jpg" alt="Service 1" class="service-image">
                        <br>
                        <h2>Servicios de la habitaci&oacute;n</h2>
                        <p>Disfruta de una experiencia única con nuestras habitaciones diseñadas para tu comodidad...
                        </p>
                        <br>
                        <p class="more-text">Ofrecemos camas king-size, ropa de cama de alta calidad, baño privado con
                            amenidades premium, televisión de pantalla plana, y conexión Wi-Fi gratuita. Perfecto para
                            descansar y relajarte en un ambiente sofisticado. </p>
                        <button class="read-more-btn">Leer m&aacute;s...</button>
                    </div>

                    <!-- Servicio 2 -->
                    <div class="service-card">
                        <img src="imagenes/servicio3.jpg" alt="Service 2" class="service-image">
                        <br>
                        <h2>Servicios del hotel</h2>
                        <p>Sumérgete en una experiencia inolvidable con nuestros servicios exclusivos...</p>
                        <br>
                        <p class="more-text">Disfruta de nuestra alberca al aire libre, gimnasio completamente equipado,
                            spa con tratamientos relajantes, y deliciosos platillos en nuestro restaurante gourmet.
                            También contamos con un bar para disfrutar de bebidas artesanales y un salón de eventos
                            ideal para celebraciones y reuniones empresariales. </p>
                        <button class="read-more-btn">Leer m&aacute;s...</button>
                    </div>

                    <!-- Servicio 2 -->
                    <div class="service-card">
                        <img src="imagenes/servicio1.jpg" alt="Service 3" class="service-image">
                        <br>
                        <h2>Servicios para contratar</h2>
                        <p>Haz de tu estancia algo único con nuestros servicios adicionales... </p>
                        <br>
                        <p class="more-text">Ofrecemos transporte privado desde y hacia el aeropuerto, tours guiados por
                            los lugares más emblemáticos de la región, clases de cocina local y servicio de niñera para
                            los más pequeños. Personalizamos tus experiencias para que cada momento sea inolvidable.
                            </p>
                        <button class="read-more-btn">Leer m&aacute;s...</button>
                    </div>
                </section>

                <!-- aqui termina el contenido de la pagina-->
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="funciones/script_servicios.js"></script>

</body>

</html>