<?php
include 'config/config.inc.php';
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
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilo-sesion.css">
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
                    
                    <div style="width: 100%; position: relative;">
                        <input type="password" name="password" id="pass1" placeholder="Contraseña" 
                            minlength="8" title="Ingresar 8 caracteres como mínimo" required>
                        <small id="alert-min" style="color: #666; display: block; font-size: 11px; text-align: left; margin-top: -10px; margin-bottom: 10px;">
                            La contraseña debe tener al menos 8 caracteres, una mayúscula, un número, un carácter especial y no contener espacios.
                        </small>
                        <ul id="password-requisitos" style="list-style:none; padding-left: 0; margin-top: 8px; font-size: 11px; color: #666; text-align: left;">
                            <li id="req-length" style="margin-bottom: 4px;">❌ Mínimo 8 caracteres</li>
                            <li id="req-uppercase" style="margin-bottom: 4px;">❌ Al menos una letra mayúscula</li>
                            <li id="req-digit" style="margin-bottom: 4px;">❌ Al menos un número</li>
                            <li id="req-special" style="margin-bottom: 4px;">❌ Al menos un carácter especial</li>
                            <li id="req-nospace" style="margin-bottom: 4px;">❌ No usar espacios</li>
                        </ul>
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
                <img src="/public/images/logo.avif" alt="Logo del Hotel">
            </div>
        </div>
    </main>

    <script src="js/script_inicio.js"></script>
    
    <script>
        const p1 = document.getElementById('pass1');
        const p2 = document.getElementById('pass2');
        const alertMatch = document.getElementById('alert-match');
        const formRegistro = document.getElementById('form-registro');
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqDigit = document.getElementById('req-digit');
        const reqSpecial = document.getElementById('req-special');
        const reqNoSpace = document.getElementById('req-nospace');

        function passwordSegura(password) {
            return password.length >= 8 &&
                /[A-Z]/.test(password) &&
                /\d/.test(password) &&
                /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>\/?]/.test(password) &&
                !/\s/.test(password);
        }

        function actualizarRequisitos() {
            if (!p1) return;
            const value = p1.value;
            const longitud = value.length >= 8;
            const mayuscula = /[A-Z]/.test(value);
            const digito = /\d/.test(value);
            const especial = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>\/?]/.test(value);
            const sinEspacios = !/\s/.test(value);

            reqLength.textContent = (longitud ? '✓' : '❌') + ' Mínimo 8 caracteres';
            reqLength.style.color = longitud ? '#2e7d32' : '#666';
            reqUppercase.textContent = (mayuscula ? '✓' : '❌') + ' Al menos una letra mayúscula';
            reqUppercase.style.color = mayuscula ? '#2e7d32' : '#666';
            reqDigit.textContent = (digito ? '✓' : '❌') + ' Al menos un número';
            reqDigit.style.color = digito ? '#2e7d32' : '#666';
            reqSpecial.textContent = (especial ? '✓' : '❌') + ' Al menos un carácter especial';
            reqSpecial.style.color = especial ? '#2e7d32' : '#666';
            reqNoSpace.textContent = (sinEspacios ? '✓' : '❌') + ' No usar espacios';
            reqNoSpace.style.color = sinEspacios ? '#2e7d32' : '#666';

            if (p1) {
                p1.style.borderColor = passwordSegura(value) ? '#2e7d32' : '#ccc';
            }
        }

        function validarContrasena() {
            if (!p1 || !p2) return;
            actualizarRequisitos();

            if (p2.value === '') {
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

        if (p1) {
            p1.addEventListener('input', validarContrasena);
        }
        if (p2) {
            p2.addEventListener('input', validarContrasena);
        }

        if (formRegistro) {
            formRegistro.addEventListener('submit', function(e) {
                if (!passwordSegura(p1.value)) {
                    e.preventDefault();
                    alert('La contraseña debe cumplir todos los requisitos de seguridad antes de registrarse.');
                    return;
                }
                if (p1.value !== p2.value) {
                    e.preventDefault();
                    alert('Las contraseñas deben coincidir para continuar.');
                }
            });
        }
    </script>
</body>

</html>
