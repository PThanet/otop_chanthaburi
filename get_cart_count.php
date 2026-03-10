<?php
// ไฟล์: get_cart_count.php
// ไฟล์ helper สำหรับดึงจำนวนสินค้าในตะกร้า

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/cart_functions.php';

echo json_encode([
    'count' => getCartCount(),
    'items' => getCartItemCount(),
    'total' => getCartTotal()
]);
?>
