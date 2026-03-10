<?php
// ไฟล์: update_cart.php
// จัดการอัปเดตจำนวนสินค้าในตะกร้า

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/cart_functions.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['product_id']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
    exit;
}

$product_id = intval($data['product_id']);
$quantity = intval($data['quantity']);

if (updateCartQuantity($product_id, $quantity)) {
    echo json_encode([
        'success' => true,
        'message' => 'อัปเดตตะกร้าแล้ว',
        'cart_count' => getCartCount(),
        'total' => getCartTotal()
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตตะกร้าได้']);
}
?>
