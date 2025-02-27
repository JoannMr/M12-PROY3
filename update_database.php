<?php
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Insertamos el nuevo producto con la imagen del bolso lujoso
try {
    $sql = "INSERT INTO products (nombre, descripcion, precio, imagen, stock) VALUES 
            ('Bolso Lujoso', 'Bolso de alta calidad con diseño exclusivo', 159.99, 'assets/img/productos/bolso1.webp', 5)";
    
    $conn->exec($sql);
    echo "Producto añadido correctamente a la base de datos.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
