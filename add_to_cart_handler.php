<?php
// ไฟล์: add_to_cart_handler.php
// จัดการเพิ่มสินค้าเข้าตะกร้า

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db_config.php';
include_once 'includes/cart_functions.php';

// รับข้อมูลจาก AJAX POST
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['product_id']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
    exit;
}

$product_id = intval($data['product_id']);
$quantity = intval($data['quantity']);

// ตรวจสอบจำนวนต้องมากกว่า 0
if ($quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'จำนวนต้องมากกว่า 0']);
    exit;
}

// คลิงเพื่อดึงข้อมูลสินค้า
$sql = "SELECT id, name, price, image_url FROM otop_products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
    
    // เพิ่มล่อตะกร้า
    addToCart($product_id, $quantity, $product);
    
    // ส่งกลับความสำเร็จ พร้อมข้อมูลตะกร้า
    echo json_encode([
        'success' => true,
        'message' => 'เพิ่มสินค้าเข้าตะกร้าแล้ว',
        'cart_count' => getCartCount(),
        'item_count' => getCartItemCount()
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่พบสินค้านี้']);
}

mysqli_close($conn);
?>
