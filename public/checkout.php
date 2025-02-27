<?php
session_start();
require_once '../config/Database.php';
require_once '../vendor/autoload.php';

// Configuramos la clave secreta de Stripe
\Stripe\Stripe::setApiKey('sk_test_51Qvc2hDA1fYrv2vZM1pXQhYNqClkDo0LwKm2Iymf8WhQlme534FbycLGWWx0AKs6ACHmkMpqFTLxc6sciZnre18y00ELAztFGE');

// Calculamos el total del carrito
$db = new Database();
$conn = $db->getConnection();
$products_in_cart = [];
$subtotal = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products_in_cart as $product) {
        $subtotal += $product['precio'] * $_SESSION['cart'][$product['id']];
    }
}

$iva = $subtotal * 0.21;
$total = $subtotal + $iva;

// Creamos una sesión de Stripe Checkout
try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Compra en Mi Tienda',
                ],
                'unit_amount' => intval($total * 100),
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/ecommerce/public/gracias.php', // Cambiar con tu URL si hace falta
        'cancel_url' => 'http://localhost/ecommerce/public/carrito.php',  // Cambiar con tu URL si hace falta
    ]);

    header("Location: " . $session->url);
    exit();

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
