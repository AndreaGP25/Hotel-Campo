<?php

include $_SERVER['DOCUMENT_ROOT'] . '/campo/config.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = $_POST['id'] ?? null;
    $titulo = $_POST['titulo'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $categoria = $_POST['categoria'] ?? null;
    $imagen = $_FILES['imagen']['name'] ?? null;

    if (!$id || !$titulo || !$precio || !$descripcion || !$categoria) {
        die("Por favor, completa todos los campos obligatorios.");
    }

    try {
        // Actualizar la información en la base de datos
        if ($imagen) {
            // Subir nueva imagen si se proporciona
            $ruta_imagen = 'actualizaciones/' . basename($imagen);
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
                die("Error al subir la imagen.");
            }

            // Actualizar con nueva imagen
            $sql = "UPDATE habitaciones 
                    SET titulo = :titulo, precio = :precio, imagen = :imagen, descripcion = :descripcion, categoria = :categoria 
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':precio' => $precio,
                ':imagen' => $ruta_imagen,
                ':descripcion' => $descripcion,
                ':categoria' => $categoria,
                ':id' => $id,
            ]);
        } else {
            // Actualizar sin cambiar la imagen
            $sql = "UPDATE habitaciones 
                    SET titulo = :titulo, precio = :precio, descripcion = :descripcion, categoria = :categoria 
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':categoria' => $categoria,
                ':id' => $id,
            ]);
        }

        // Redirigir al listado de habitaciones aqui se puede agregar un mensaje
        header("Location:/campo/admin/indexAdmin.php");
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar los datos: " . $e->getMessage());
    }
}
?>
