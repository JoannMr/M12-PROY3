<?php
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Creamos la tabla de productos
$conn->exec("
    CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        descripcion TEXT,
        precio REAL NOT NULL,
        imagen TEXT,
        stock INTEGER DEFAULT 0
    );
");

// Insertamos algunos productos de ejemplo
$conn->exec("
    INSERT INTO products (nombre, descripcion, precio, imagen, stock) VALUES
    ('Bolso Verona', 'Bolso de lujo en piel italiana con detalles en dorado y forro de satén, perfecto para eventos exclusivos.', 1250.00, 'assets/img/productos/bolso_verona.webp', 7),
    ('Cartera Milan', 'Cartera de diseñador confeccionada en cuero premium con un diseño elegante y atemporal.', 1750.00, 'assets/img/productos/cartera_milan.webp', 5),
    ('Bolso Royale', 'Bolso exclusivo con bordado artesanal y cierres metálicos, ideal para ocasiones especiales.', 2100.00, 'assets/img/productos/bolso_royale.webp', 3);
");

echo "Base de datos configurada correctamente.";
