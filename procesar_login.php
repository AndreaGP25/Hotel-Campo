<?php
include 'config.inc.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    try {

        if (!$conn) {
            throw new Exception("Error en la conexión con la base de datos.");
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Correo electrónico no válido.");
        }


        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {

            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['telefono'] = $usuario['telefono']; 
            $_SESSION['rol'] = $usuario['rol'];


            $token = bin2hex(random_bytes(32)); 
            $ip = $_SERVER['REMOTE_ADDR']; 
            $fecha_inicio = date('Y-m-d H:i:s');

            $sql = "INSERT INTO sesiones (id_usuario, token, fecha_inicio, ip) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$usuario['id_usuario'], $token, $fecha_inicio, $ip]);

            $_SESSION['token'] = $token;

            if ($usuario['rol'] === 'admin') {
                header('Location: /campo/admin/indexAdmin.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            echo "
            <div id='modal' class='modal'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <span class='close'>&times;</span>
                        <h2>Correo o Contraseña Incorrectos</h2>
                    </div>
                    <div class='modal-body'>
                        <p>Por favor, revisa tus credenciales e intenta nuevamente.</p>
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
        
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>