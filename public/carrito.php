<?php
session_start();
require_once '../config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Eliminamos producto del carrito
if (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    header('Location: carrito.php');
    exit();
}

// Actualizamos cantidad del producto en el carrito
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: carrito.php');
    exit();
}

// Obtenemos los productos del carrito
$products_in_cart = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calculamos subtotal, IVA (21%) y total
$subtotal = 0;
foreach ($products_in_cart as $product) {
    $subtotal += $product['precio'] * $_SESSION['cart'][$product['id']];
}
$iva = $subtotal * 0.21;
$total = $subtotal + $iva;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de la Compra</title>
    <link rel="stylesheet" href="assets/css/carrito.css">
</head>
<body>
    <main>
        <div class="cart-container">
        <h1 class="cart-title">Carrito de la Compra</h1>

        <?php if (empty($products_in_cart)) : ?>
            <p>El carrito está vacío.</p>
        <?php else : ?>
            <table class="cart-table">
                <tr class="table-header">
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th class = "movil-none">Subtotal</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($products_in_cart as $product) : ?>
                    <tr class="product-row" data-product-id="<?php echo $product['id']; ?>">
                        <td>
                            <div class="product-info">
                                <img src="<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>">
                                <h2><?php echo $product['nombre']; ?></h2>
                            </div>
                        </td>
                        <td>
                            <div class="quantity-wrapper">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $product['id']; ?>, -1)">-</button>
                                <input type="number" name="quantity" data-product-id="<?php echo $product['id']; ?>" value="<?php echo $_SESSION['cart'][$product['id']]; ?>" min="1">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $product['id']; ?>, 1)">+</button>
                            </div>
                        </td>
                        <td>€<?php echo number_format($product['precio'], 2); ?></td>
                        <td>€<?php echo number_format($product['precio'] * $_SESSION['cart'][$product['id']], 2); ?></td>
                        <td>
                            <button type="button" class="btn-remove" data-product-id="<?php echo $product['id']; ?>">Eliminar</button>
                        </td>
                    </tr>


                <?php endforeach; ?>
            </table>

            <div class="cart-summary">
                <p><strong>Subtotal:</strong> €<?php echo number_format($subtotal, 2); ?></p>
                <p><strong>IVA (21%):</strong> €<?php echo number_format($iva, 2); ?></p>
                <p><strong>Total:</strong> €<?php echo number_format($total, 2); ?></p>
                <form method="post" action="checkout.php">
                    <button type="submit" class="btn-proceed-to-payment">
                        <span>Tramitar compra</span>
                    </button>
                </form>

            </div>
        <?php endif; ?>
        </div>
    </main>
    <script src="assets/js/carrito.js" defer></script>
</body>
</html>
