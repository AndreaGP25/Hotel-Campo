<?php
include 'config.inc.php';
session_start();
$limpieza = $conn->prepare("DELETE FROM usuarios WHERE verificado = 0");
$limpieza->execute();

//Obtenert "action" de la URL para determinar formulario a mostrar
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

$mensaje_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión | Hotel Refugio del Valle</title>
    <link rel="stylesheet" href="estilos/normalize.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilos/estilo-sesion.css">
</head>

<body>
    <main>
        <div class="contenedor_todo">
            <div class="caja_trasera">
                <div class="caja_trasera_acceso">
                    <h3>¿Ya estás registrado?</h3>
                    <p>Inicia sesión y accede</p>
                    <button id="btn_iniciar-sesion">Iniciar sesión</button>
                </div>

                <div class="caja_trasera_registro">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para iniciar sesión</p>
                    <button id="btn_registrarse">Regístrarse</button>
                </div>
            </div>

            <div class="contenedor_acceso_registro">
                
                <form method="POST" action="procesar_login.php" class="formulario_acceso"
                    style="<?= $action === 'login' ? 'display: block;' : 'display: none;' ?>">
                    <h2>Iniciar sesión</h2>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    
                    <button type="submit">Entrar</button>
                    <button type="button" class="boton-cancelar" onclick="window.location.href='index.php'">Cancelar</button>
                    <button type="button" class="boton-restablecer" onclick="window.location.href='recuperar_contrasena.php'">Restablecer contraseña</button>
                    
                    <?php if ($action === 'login' && $mensaje_error): ?>
                        <div class="alerta-error" style="color: #ff4d4d; margin-top: 10px; font-size: 14px;">
                            <?= $mensaje_error; ?>
                        </div>
                    <?php endif; ?>
                </form>

                <form method="POST" action="procesar_registro.php" class="formulario_registro" id="form-registro"
                    style="<?= $action === 'register' ? 'display: block;' : 'display: none;' ?>">
                    <h2>Registrarse</h2>
                    
                    <input type="text" name="nombre" placeholder="Nombre Completo" maxlength="100"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" required>
                    
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    
                    <div style="width: 100%;">
                        <input type="password" name="password" id="pass1" placeholder="Contraseña (mín. 8 caracteres)" 
                            minlength="8" title="Ingresar 8 caracteres como mínimo" required>
                        <small id="alert-min" style="color: #666; display: block; font-size: 11px; text-align: left; margin-top: -10px; margin-bottom: 10px;">
                            Ingresar 8 caracteres como mínimo
                        </small>
                    </div>

                    <div style="width: 100%;">
                        <input type="password" name="confirm_password" id="pass2" placeholder="Confirmar contraseña" required>
                        <small id="alert-match" style="color: red; display: none; font-size: 11px; text-align: left; margin-top: -10px; margin-bottom: 10px;">
                            Las contraseñas no coinciden
                        </small>
                    </div>
                    
                    <input type="tel" name="telefono" placeholder="Número telefónico (10 dígitos)" 
                            maxlength="10" minlength="10" pattern="[0-9]{10}" title="Debe contener exactamente 10 dígitos numéricos"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    
                    <button id="boton-registro" type="submit">Registrarse</button>
                    <button type="button" class="boton-cancelar" onclick="window.location.href='index.php'">Cancelar</button>

                    <?php if ($action === 'register' && $mensaje_error): ?>
                        <div class="alerta-error" style="color: #ff4d4d; margin-top: 10px; font-size: 14px;">
                            <?= $mensaje_error; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="logo_centrado">
                <img src="imagenes/logo.png" alt="Logo del Hotel">
            </div>
        </div>
    </main>

    <script src="funciones/script_inicio.js"></script>
    
    <script>
        //Script de validación en tiempo real para contraseñas
        const p1 = document.getElementById('pass1');
        const p2 = document.getElementById('pass2');
        const alertMatch = document.getElementById('alert-match');
        const btnReg = document.getElementById('boton-registro');

        function validar() {
            if (p2.value === "") {
                alertMatch.style.display = 'none';
                p2.style.borderColor = '#ccc';
            } else if (p1.value !== p2.value) {
                alertMatch.style.display = 'block';
                p2.style.borderColor = 'red';
            } else {
                alertMatch.style.display = 'none';
                p2.style.borderColor = 'green';
            }
        }

        p1.addEventListener('input', validar);
        p2.addEventListener('input', validar);

        document.getElementById('form-registro').addEventListener('submit', function(e) {
            if (p1.value !== p2.value) {
                e.preventDefault();
                alert("Las contraseñas deben coincidir para continuar.");
            }
        });
    </script>
</body>

</html>
