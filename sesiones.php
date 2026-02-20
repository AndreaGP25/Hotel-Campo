<?php
include 'config.inc.php';
session_start();
// Obtenemos el parámetro 'action' de la URL para determinar qué formulario mostrar
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion</title>
    <link rel="stylesheet" href="estilos/normalize.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="estilos/estilo-sesion.css">

</head>


<body>
    <main>
        <div class="contenedor_todo">

            <div class="caja_trasera">

                <div class="caja_trasera_acceso">
                    <h3>¿Ya estas registrado?</h3>
                    <p>Inicia sesi&oacute;n y accede </p>
                    <button id="btn_iniciar-sesion">Iniciar sesi&oacute;n</button>
                </div>

                <div class="caja_trasera_registro">
                    <h3>¿A&uacute;n no tienes una cuenta?</h3>
                    <p>Reg&iacute;strarse para iniciar sesi&oacute;n</p>
                    <button id="btn_registrarse">Reg&iacute;strarse</button>
                </div>
            </div>

            <!-- Formulario de acceso y registro -->
            <div class="contenedor_acceso_registro">
                <!-- Formulario de inicio de sesión -->
                <form method="POST" action="procesar_login.php" class="formulario_acceso"
                    style="<?= $action === 'login' ? 'display: block;' : 'display: none;' ?>">
                    <h2>Iniciar sesión</h2>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Entrar</button>
                    <button type="button" class="boton-cancelar" onclick="window.location.href='/campo/index.php'">Cancelar</button>
                    <button type="button" class="boton-restablecer" onclick="window.location.href='/campo/recuperar_contrasena.php'">Restablecer contraseña</button>
                    <div id="mensaje-login" style="color: red;">
                        <?= isset($mensaje) ? htmlspecialchars($mensaje) : ""; ?>
                    </div>
                </form>

                <!-- Formulario de registro -->
                <form method="POST" action="procesar_registro.php" class="formulario_registro"
                    style="<?= $action === 'register' ? 'display: block;' : 'display: none;' ?>">
                    <h2>Registrarse</h2>
                    <input type="text" name="nombre" placeholder="Nombre Completo" maxlength="100"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" required>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                    <input type="tel" name="telefono" placeholder="N&uacute;mero telef&oacute;nico" maxlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    <button id="boton-registro" type="submit">Registrarse</button>
                    <button type="button" class="boton-cancelar" onclick="window.location.href='/campo/index.php'">Cancelar</button>
                </form>
            </div>

            <div class="logo_centrado">
                <img src="imagenes/logo.png" alt="Logo del Hotel">
            </div>
        </div>
    </main>
    <script src="funciones/script_inicio.js"></script>
</body>

</html>