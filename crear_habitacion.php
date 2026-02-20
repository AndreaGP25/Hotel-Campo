<?php

include 'config.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar datos del formulario
    $titulo = $_POST['titulo'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $imagen = $_FILES['imagen']['name'] ?? null;
    $categoria = $_POST['categoria'] ?? 'Estandar';


    if ($titulo && $precio && $descripcion && $imagen) {

        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            die("Error al subir la imagen. Código de error: " . $_FILES['imagen']['error']);
        }

        $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . '/campo/actualizaciones/' . basename($imagen);


        if (file_exists($ruta_imagen)) {
            die("El archivo ya existe. Por favor, cambie el nombre de la imagen.");
        }


        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
            die("Error al subir la imagen.");
        }

        $sql = "INSERT INTO habitaciones (titulo, precio, imagen, descripcion, categoria) 
                VALUES (:titulo, :precio, :imagen, :descripcion, :categoria)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':precio' => $precio,
            ':imagen' => '/campo/actualizaciones/' . basename($imagen),  // Ruta relativa en la base de datos
            ':descripcion' => $descripcion,
            ':categoria' => $categoria
        ]);


        header("Location: /campo/admin/indexAdmin.php");
        exit;

    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>