<?php
// ไฟล์: remove_from_cart.php
// จัดการลบสินค้าจากตะกร้า

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/cart_functions.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
    exit;
}

$product_id = intval($data['product_id']);

if (removeFromCart($product_id)) {
    echo json_encode([
        'success' => true,
        'message' => 'ลบสินค้าแล้ว',
        'cart_count' => getCartCount(),
        'item_count' => getCartItemCount()
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบสินค้าได้']);
}
?>
