<?php
include 'config.inc.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioAutenticado = false;
$nombre = '';
$email = '';
$telefono = '';

if (isset($_SESSION['id_usuario'])) {
    $usuarioAutenticado = true;
    $nombre = isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : '';
    $email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';
    $telefono = isset($_SESSION['telefono']) ? htmlspecialchars($_SESSION['telefono']) : '';
} else {
    // PONER TODAS LAS PAGINAAAAASSSSSSS
    header('Location: index.php');
    exit();
}


// Habitaciones estándar
$sqlEstandar = "SELECT id, titulo, precio, disponibilidad FROM habitaciones WHERE categoria = 'Estandar' AND disponibilidad >= 1";
$stmtEstandar = $conn->prepare($sqlEstandar);
$stmtEstandar->execute();
$habitacionesEstandar = $stmtEstandar->fetchAll(PDO::FETCH_ASSOC);

// Habitaciones superior
$sqlSuperior = "SELECT id, titulo, precio, disponibilidad FROM habitaciones WHERE categoria = 'Superior' AND disponibilidad >= 1";
$stmtSuperior = $conn->prepare($sqlSuperior);
$stmtSuperior->execute();
$habitacionesSuperior = $stmtSuperior->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservación</title>
    <link rel="stylesheet" href="estilos/normalize.css">
    <link rel="stylesheet" href="estilos/estilo-reservacion.css">
</head>

<body>
<div class="reservacion-container">
        <h2 class="reservacion-title">Haz tu reservación</h2>
        <form action="procesar_reservacion.php" method="POST" class="reservacion-form">
            <!-- Nombre completo -->
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" value="<?= $nombre; ?>" <?= $usuarioAutenticado ? 'readonly' : ''; ?> required>
            </div>
            <!-- Email -->
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" value="<?= $email; ?>" <?= $usuarioAutenticado ? 'readonly' : ''; ?> required>
            </div>
            <!-- Teléfono -->
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" value="<?= $telefono; ?>" <?= $usuarioAutenticado ? 'readonly' : ''; ?> required>
            </div>
            <!-- Fecha de llegada -->
            <div class="form-group">
                <label for="fecha_llegada">Fecha de llegada:</label>
                <input type="date" id="fecha_llegada" name="fecha_llegada" required>
                <small id="error_llegada" style="color: red; display: none;">La fecha de llegada no puede ser anterior a
                    hoy.</small>
            </div>
            <!-- Fecha de salida -->
            <div class="form-group">
                <label for="fecha_salida">Fecha de salida:</label>
                <input type="date" id="fecha_salida" name="fecha_salida" required>
                <small id="error_salida" style="color: red; display: none;">La fecha de salida debe ser posterior a la
                    fecha de llegada.</small>
            </div>
            <!-- Cantidad de huéspedes -->
            <div class="form-group">
                <label for="huespedes">Cantidad de huéspedes:</label>
                <input type="number" id="huespedes" name="huespedes" placeholder="M&aacute;ximo 12 huéspedes" min="1" max="12"
                    required>
            </div>
            
            <!-- Número de habitaciones -->
            <div class="form-group">
                <label for="num_habitaciones">Número de habitaciones:</label>
                <select id="num_habitaciones" name="num_habitaciones" onchange="generarOpcionesHabitaciones()" required>
                    <option value="1">1 habitación</option>
                    <option value="2">2 habitaciones</option>
                    <option value="3">3 habitaciones</option>
                </select>
            </div>

            <!-- Habitaciones -->
            <div id="habitaciones-container">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="form-group" id="habitacion_<?= $i ?>" style="display: none;">
                        <h3>Habitación <?= $i ?></h3>
                        <label for="tipo_habitacion_<?= $i ?>">Tipo de Habitación:</label>
                        <select id="tipo_habitacion_<?= $i ?>" name="tipo_habitacion_<?= $i ?>">
							<option value="" data-precio="0">Seleccione una habitación</option>
							<optgroup label="Estandar">
								<?php foreach ($habitacionesEstandar as $habitacion): ?>
									<option value="<?= $habitacion['id'] ?>" data-precio="<?= $habitacion['precio'] ?>">
									Estandar - <?= htmlspecialchars($habitacion['titulo']) ?> ($<?= $habitacion['precio'] ?> por noche)
									</option>
								<?php endforeach; ?>
							</optgroup>
							<optgroup label="Superior">
								<?php foreach ($habitacionesSuperior as $habitacion): ?>
									<option value="<?= $habitacion['id'] ?>" data-precio="<?= $habitacion['precio'] ?>">
									Superior - <?= htmlspecialchars($habitacion['titulo']) ?> ($<?= $habitacion['precio'] ?> por noche)
									</option>
								<?php endforeach; ?>
							</optgroup>
						</select>
                    </div>
                <?php endfor; ?>
            </div>
			
			<!-- Campo oculto para las disponibilidades -->
			<?php foreach ($habitacionesEstandar as $habitacion): ?>
				<input type="hidden" id="disponibilidad_<?= $habitacion['id'] ?>" value="<?= $habitacion['disponibilidad'] ?>">
			<?php endforeach; ?>
			<?php foreach ($habitacionesSuperior as $habitacion): ?>
				<input type="hidden" id="disponibilidad_<?= $habitacion['id'] ?>" value="<?= $habitacion['disponibilidad'] ?>">
			<?php endforeach; ?>

            <!-- Campos de Pago -->
            <h2 class="reservacion-title">Detalles de Pago</h2>
            <div class="form-group">
                <label for="nombre_titular">Nombre del titular de la tarjeta:</label>
                <input type="text" id="nombre_titular" name="nombre_titular" placeholder="Nombre completo" required>
            </div>
            <div class="form-group">
                <label for="numero_tarjeta">Número de tarjeta:</label>
                <input type="text" id="numero_tarjeta" name="numero_tarjeta" placeholder="16 dígitos" maxlength="16"
                    required>
				<small id="error_numeroTarjeta" style="color: red; display: none;">El número de tarjeta debe tener 16 dígitos.</small>	
            </div>
            <div class="form-group">
                <label for="fecha_expiracion">Fecha de expiración:</label>
                <input type="month" id="fecha_expiracion" name="fecha_expiracion" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="Código de seguridad" maxlength="4" required>
				<small id="error_cvv" style="color: red; display: none;">El CVV debe tener entre 3 y 4 dígitos.</small>
			</div>
            <div class="form-group">
                <label for="monto_total">Monto total:</label>
                <input type="text" id="monto_total" name="monto_total" value="0.00" readonly>
            </div>
			
			<!-- Botón de cancelar -->
			<button type="button" id="cancelar-btn">Cancelar</button>
			
            <!-- Botón de enviar -->
            <button type="submit" id="reservacion-btn" class="reservacion-btn">Reservar y Pagar</button>
        </form>
        
    </div>

    <div id="fondo-contenedor">
        <div id="informacion-contenedor">
            <h2 class="titulo-informacion">Información</h2>
            <hr>
            <section class="seccion">
                <h3>Políticas de Check-in y Check-out</h3>
                <p>El horario de check-in comienza a las 2:00 PM y el horario de check-out es hasta las 12:00 PM del día
                    de
                    salida. Solicitudes de check-in temprano o check-out tardío estarán sujetas a disponibilidad y
                    pueden
                    generar cargos adicionales.</p>
            </section>
            <hr>
            <section class="seccion">
                <h3>Términos y Condiciones</h3>
                <ol class="lista-romana">
                    <li>
                        <b>REGISTRO.</b> Todo huésped, tanto él individual como el de grupo deberá de llenar una hoja de
                        registro de manera individual o bien por cada habitación, misma que le será proporcionada a su
                        llegada en la recepción del Hotel.
                    </li>
                    <li>
                        <b>OBLIGACIÓN DE INFORMACIÓN POR PARTE DEL HUESPES.</b> obligación delos huéspedes informar a la
                        administración del Hotel de padecimientos o enfermedades contagiosas, fallecimientos,
                        infracciones o delitos que acontezcan en el establecimiento y sean de su conocimiento, a fin de
                        que el Hotel pueda a su vez, tomar las medidas oportunas y dar cuenta inmediata a la autoridad
                        cuándo proceda. Si algún huésped enfermase, la recepción del hotel deberá llamar a un médico y
                        podrá ser atendido en su cuarto con cargo al huésped; sí la enfermedad fuera contagiosa el
                        huésped será trasladado por su cuenta al lugar adecuado.
                    </li>
                    <li><b>REGISTRO DEL HUÉSPED AL HOTEL.</b>
                        La hora de registro para entrar a la habitación será de las 14:00 horas en adelante, y la hora
                        de salida deberá ser a más tardar a las 12:00 horas, caso contrario se cobrará una noche
                        adicional de estadía. Para el caso de que por razones personales el huésped tenga que registrar
                        su entrada después de las 20:00 horas este deberá de avisar con 24 horas de anticipación a su
                        llegada. Recomendamos que a su llegada revise el inventario de la habitación ya que al momento
                        de su retiro será revisado y en caso de faltantes o daños deberán ser cubiertos por el huésped
                        por otro producto igual o en su defecto se cobrará a su precio.</li>
                    <li><b>UTILIZACIÓN DE LOS SERVICIOS.</b> Los servicios(agua, electricidad, etc.) prestados por el
                        Hotel deberán utilizarse de conformidad con las normas de buena fe, sin que se entienda incluido
                        en el precio el derroche o utilización desproporcionada de los mismos, ayúdanos a mantener las
                        tarifas bajas y accesibles, así como a cuidar el medio ambiente. Se pide a los huéspedes el uso
                        moderado y racional de los muebles de la habitación, cuidando de ellos debidamente; de igual
                        manera todo huésped al salir de la habitación tiene la obligación de dejar cerradas las
                        ventanas, puertas de entrada, llaves de agua y apagar luces.</li>
                    <li>
                        <b>ESTANCIA EN EL HOTEL.</b> Los huéspedes podrán llevar consigo o dejar en la recepción o
                        administración, las llaves de sus habitaciones hasta antes de las 20:00 horas, cada vez que
                        salgan del establecimiento. No podrán alojar en sus habitaciones a personas diferentes delas
                        registradas y en todo caso, darán aviso previo en la administración de cualquier variación en el
                        número o identificación de las personas que originalmente se registraron. Los visitantes de los
                        clientes no podrán quedarse a pasar la noche. Tampoco podrán venir a disfrutar los servicios
                        personas distintas de las que han realizado la contratación, ni se podrá utilizar el Hotel como
                        lugar de realización de transacciones mercantiles. En ningún caso, el número de personas
                        alojadas en cada habitación podrá ser mayor de la capacidad asignada por el Hotel a cada cuarto.
                    </li>
                    <li>
                        <b>ACCESO AL HOTEL</b> El Hotel estará abierto al público de las 8:00 horas a las 21:30 horas,
                        si el
                        huésped desea entrar o salir después de las 21:30 horas, entonces éste deberá de tocar el timbre
                        de la puerta principal. La puerta de acceso al hotel después de las 21:30 horas será abierta y
                        cerrada únicamente por personal del hotel.
                    </li>
                    <li>
                        <b>LIMPIEZA DE LAS HABITACIONES.</b> La limpieza de habitaciones se realizará en horario de
                        mañana,
                        desde las 9:00 horas a las12.00 horas. Los clientes que no pongan a disposición sus habitaciones
                        duranteesas horas no se les realizarán la limpieza de la habitación.Se informa a loshuéspedes
                        tanto individuales como grupales, de que en este establecimiento secambian los blancos de la
                        habitación cada tercera noche de estadía por el mismocliente.
                    </li>
                    <li>
                        <b>MEDIDAS DE SEGURIDAD.</b> El huésped deberáde guardar sus objetos de valor lejos del alcance
                        de
                        ninguna persona. Si tienealgún objeto de valor, el hotel no se hace responsable de pérdida o
                        extravió dejoyas y valores dejados en las habitaciones.
                    </li>
                    <li>
                        <b>SILENCIO Y RESPETO AL RESTO DE CLIENTES Y PERSONAL DEL HOTEL.</b> Desde las 22:00horas en
                        todas las
                        habitaciones debe moderarse el volumen acústico. Respete también el silencio nocturno en los
                        pasillos y en las escaleras. Desde las24:00 horas, se pide a los huéspedes silencio absoluto. Se
                        deberá evitar cualquier actuación molesta, para el resto de los huéspedes del hotel.
                    </li>
                    <li>
                        <b>DESPERFECTOS, SUCIEDAD, PÉRDIDA DE LAS LLAVES.</b> En caso de dañar el inmueble o el
                        mobiliario del
                        hotel, así como de perder las llaves, es el causante quien paga por el daño ocasionado. Pagos de
                        este tipo se realizan inmediatamente y en efectivo (en el caso de grupos el responsable es el
                        organizador y/o representante, por lo que será él quien debe adelantar el importe). Robos o
                        daños intencionados serán denunciados inmediatamente a la policía. Está prohibido ejecutar
                        cualquier acto que ocasione daños o perjuicios al Hotel o a los demás huéspedes, o conductas
                        contrarias al decoro o al comportamiento social.
                    </li>
                    <li>
                        <b>DISPOSICIONES VARIAS.</b> El hotel no autoriza el acceso a las habitaciones ocupadas por los
                        huéspedes de ninguna persona que no haya sido previa y expresamente autorizada por el cliente,
                        se reserva el derecho de no permitir en la habitación visitas de otras personas. Las personas
                        que ostenten la representación del Hotel o presten los servicios inherentes al hospedaje,
                        tendrán libre acceso a los cuartos ocupados por los clientes. En la circunstancia que los
                        huéspedes se ausenten por más de veinticuatro horas sin previo aviso ala administración, se
                        podrá rescindir o suspender el hospedaje, según el caso y proceder a recoger el equipaje.
                    </li>
                    <li>
                        <b>ACCIDENTES O SUCESOS DENTRO DEL HOTEL.</b> El hotel no se hace responsable de ningún tipo de
                        accidente y/o suceso, que el huésped sufra dentro de las instalaciones del hotel tales como
                        caídas, golpes, picaduras de animales, entre otros. Los gastos que este accidente o suceso
                        originen correrán por cuenta del huésped, eximiendo al hotel de cualquier responsabilidad de
                        carácter legal.
                    </li>
                    <li>
                        <b>ESTACIONAMIENTO.</b> El Hotel cuenta con disponibilidad de estacionamiento sin un costo
                        adicional.
                    </li>
                    <li>
                        <b>PROHIBICIONES.</b>
                        <ol>
                            <li>Está prohibido el consumo de drogas tóxicas, estupefacientes o sustancias psicotrópicas,
                                dentro de cualquier área del hotel, de esta infracción se dará cuenta inmediatamente a
                                las autoridades correspondientes.</li>
                            <li>Está prohibido fumar dentro de cualquier área del hotel ya que es un espacio libre de
                                humo en caso de que se encuentre fumando en estas o en la habitaciones será sujeto a una
                                sanción económica por cuestiones de limpieza </li>
                            <li>Queda prohibido alterar el orden o causar molestias a los demás usuarios dentro del
                                establecimiento, usar la corriente eléctrica y los equipos mecánicos instalados en las
                                habitaciones para otros fines que no sean a los que se están destinados.</li>
                        </ol>
                    </li>
                    <li>
                        <b>INCUMPLIMIENTO DE LAS NORMAS DEL HOTEL.</b> En caso de infracción de una o más de las
                        condiciones y/o
                        prohibiciones citadas anteriormente, el Hotel tiene el derecho de rescindir inmediatamente el
                        contrato de alojamiento. El cliente queda igualmente obligado a pagar por todos los días de
                        alojamiento fijados en la reserva. Quedan a salvo los derechos del establecimiento como de los
                        huéspedes para denunciar ante las autoridades competentes los hechos que constituyan algún
                        ilícito o que dieran lugar a responsabilidad por alguna de las partes en sus personas y bienes,
                        siempre y cuando ocurren dentro del hotel.
                    </li>
                </ol>
                <p><b>IMPORTANTE:</b> Se da por entendido que al momento de hacer efectiva la reserva, el cliente y/o
                    huésped
                    conoce y manifiesta expresa y tácitamente que acepta en su totalidad el presente reglamento interno
                    del Hotel Refugio del Valle, estando conforme y de acuerdo con todos los puntos antes mencionados.
                </p>
            </section>
        </div>
    </div>

    <script src="funciones/script_reservas.js"></script>
</body>

</html>