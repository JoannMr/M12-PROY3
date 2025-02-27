<?php
session_start();
require_once '../config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Consultamos productos desde la base de datos
$stmt = $conn->query('SELECT * FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce - Tienda Online</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <header>
        <nav class="navbar">
            <h1 class="logo">Velour & Co.</h1>
            <div class="nav-links">
                <a href="carrito.php">
                <div class="cart-icon-wrapper">
                    <i class="fa-solid fa-cart-shopping cart-icon"></i>
                </div>

                </a>
            </div>
        </nav>
    </header>

    <section class="hero">
        <img src="assets/img/hero.webp" alt="Imagen Hero" class="hero-image">
        <div class="hero-content">
            <h1>Bienvenido a Velour & Co.</h1>
            <a href="#catalogo" class="btn">Ver Catálogo</a>
        </div>
    </section>

    <main id="catalogo">
        <h1 class="catalog-title">Productos Disponibles</h1>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>">
                    <div class="product-info">
                        <span class="tag">NOVEDAD</span>
                        <h2><?php echo $product['nombre']; ?></h2>
                        <p><?php echo $product['descripcion']; ?></p>
                        <div class="product-actions">
                            <p class="price">€<?php echo number_format($product['precio'], 2); ?></p><span class="price-note">(Precio sin IVA)</span></p>
                            <form method="post" action="api/add_to_cart.php" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="button" class="btn-add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Velour & Co. Todos los derechos reservados.</p>
    </footer>
    <script src="assets/js/script.js" defer></script>
</body>
</html>
