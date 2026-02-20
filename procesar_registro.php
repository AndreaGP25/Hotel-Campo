<?php
include 'config.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim(htmlspecialchars($_POST['nombre']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars($_POST['telefono']); 
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $rol = 'prospecto'; // Rol por defecto
   

        // Validar que el correo electrónico tenga un formato correcto
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "El correo electrónico no tiene un formato válido.";
            exit();
        }

    // Verifica que coincidan las contraseñas
    if ($password !== $confirm_password) {
        echo "
        <div id='modal' class='modal'>
            <div class='modal-content'>
                <span class='close'>&times;</span>
                <h2>Las contraseñas no coinciden</h2>
                <p>Por favor, inténtalo de nuevo.</p>
            </div>
        </div>
        <script>
            var modal = document.getElementById('modal');
            var span = document.getElementsByClassName('close')[0];
            modal.style.display = 'block';
            span.onclick = function() {
                modal.style.display = 'none';
                window.history.back();
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                    window.history.back();
                }
            }
        </script>
        <style>
            .modal {
                display: none; 
                position: fixed; 
                z-index: 1; 
                left: 0;
                top: 0;
                width: 100%; 
                height: 100%; 
                overflow: auto; 
                background-color: rgb(0,0,0); 
                background-color: rgba(0,0,0,0.4); 
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%; 
                max-width: 400px;
                text-align: center;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
        ";
        exit();
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Verifica que el correo no sea repetido en la BD
    $verificar_email = $conn->prepare("SELECT email FROM usuarios WHERE email = :email");
    $verificar_email->bindParam(':email', $email, PDO::PARAM_STR);
    $verificar_email->execute();
    
    if ($verificar_email->rowCount() > 0) {
        echo "
        <div id='modal' class='modal'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <span class='close'>&times;</span>
                    <h2>Correo ya registrado</h2>
                </div>
                <div class='modal-body'>
                    <p>Intenta con un correo diferente.</p>
                </div>
                <div class='modal-footer'>
                    <button id='btnClose' class='btn-close'>Cerrar</button>
                </div>
            </div>
        </div>
        <script>
            var modal = document.getElementById('modal');
            var span = document.getElementsByClassName('close')[0];
            var btnClose = document.getElementById('btnClose');
            modal.style.display = 'block';
    
            span.onclick = function() {
                modal.style.display = 'none';
                window.history.back();
            }
    
            btnClose.onclick = function() {
                modal.style.display = 'none';
                window.history.back();
            }
    
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                    window.history.back();
                }
            }
        </script>
        <style>
            /* Modal styles */
            .modal {
                display: none; 
                position: fixed; 
                z-index: 1; 
                left: 0;
                top: 0;
                width: 100%; 
                height: 100%; 
                background-color: rgba(0,0,0,0.5); 
                overflow: auto; 
            }
    
            .modal-content {
                background-color: #fff;
                margin: 15% auto;
                padding: 30px;
                border-radius: 8px;
                width: 70%; 
                max-width: 500px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                text-align: center;
            }
    
            .modal-header {
                font-family: 'Arial', sans-serif;
                font-size: 24px;
                color: #333;
                margin-bottom: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
    
            .modal-body {
                font-size: 16px;
                color: #555;
                margin-bottom: 20px;
            }
    
            .modal-footer {
                margin-top: 20px;
            }
    
            .close {
                color: #aaa;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }
    
            .close:hover,
            .close:focus {
                color: #333;
                text-decoration: none;
                cursor: pointer;
            }
    
            .btn-close {
                padding: 10px 20px;
                background-color: #995c01e0;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
            }
    
            .btn-close:hover {
                background-color: #0056b3;
            }
        </style>
        ";
        exit();
    }

    try {
       
        if (!$conn) {
            throw new Exception("Error en la conexión con la base de datos.");
        }

        // Insertar datos en la tabla usuarios
        $sql = "INSERT INTO usuarios (nombre, email, telefono, password, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $email, $telefono, $password_hashed, $rol]);

       
        echo "<h1>Usuario registrado exitosamente.</h1>";
        echo "<p>Gracias por registrarte, ahora puedes <a href='sesiones.php?action=login'>iniciar sesión</a>.</p>";
    } catch (PDOException $e) {
       
        echo "<h1>Error en el registro.</h1>";
        echo "<p>Por favor, intenta de nuevo. Detalles del error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
