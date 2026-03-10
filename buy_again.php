<?php
session_start();
require_once 'includes/db_config.php';
require_once 'includes/cart_functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id > 0) {
    // 1. Verify that the order belongs to the user
    $check_sql = "SELECT id FROM orders WHERE id = $order_id AND user_id = $user_id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // 2. Fetch order items joined with products table to get current image_url
        $items_sql = "
            SELECT oi.product_id, oi.quantity, p.name, p.price, p.image_url 
            FROM order_items oi
            JOIN otop_products p ON oi.product_id = p.id
            WHERE oi.order_id = $order_id
        ";
        
        $items_result = mysqli_query($conn, $items_sql);
        
        if ($items_result) {
            while ($item = mysqli_fetch_assoc($items_result)) {
                $product_data = [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'image_url' => $item['image_url']
                ];
                
                // Add to cart
                addToCart($item['product_id'], $item['quantity'], $product_data);
            }
        }
    }
}

// Redirect to cart
header("Location: cart.php");
exit();
?>
