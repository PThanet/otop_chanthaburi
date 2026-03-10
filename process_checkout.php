<?php
// ไฟล์: process_checkout.php
// API สำหรับบันทึกออเดอร์ลงฐานข้อมูล

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db_config.php';
include_once 'includes/cart_functions.php';

// ตรวจสอบวิธี POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

// ตรวจสอบตะกร้าไม่ว่างเปล่า
if (empty(getCart())) {
    header('Location: product.php');
    exit;
}

// รับข้อมูลจากฟอร์ม
$customer_name = $conn->real_escape_string(trim($_POST['customer_name'] ?? ''));
$customer_phone = $conn->real_escape_string(trim($_POST['customer_phone'] ?? ''));
$customer_email = $conn->real_escape_string(trim($_POST['customer_email'] ?? ''));
$shipping_address = $conn->real_escape_string(trim($_POST['shipping_address'] ?? ''));
$province = $conn->real_escape_string(trim($_POST['province'] ?? ''));
$district = $conn->real_escape_string(trim($_POST['district'] ?? ''));
$subdistrict = $conn->real_escape_string(trim($_POST['subdistrict'] ?? ''));
$postal_code = $conn->real_escape_string(trim($_POST['postal_code'] ?? ''));
$payment_method = $conn->real_escape_string(trim($_POST['payment_method'] ?? 'bank_transfer'));
$notes = $conn->real_escape_string(trim($_POST['notes'] ?? ''));

// ตรวจสอบข้อมูลที่จำเป็น
$errors = [];

if (empty($customer_name)) {
    $errors[] = 'กรุณากรอกชื่อ-นามสกุล';
}

if (empty($customer_phone) || !preg_match('/^[0-9]{10}$/', $customer_phone)) {
    $errors[] = 'กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)';
}

if (empty($shipping_address) || empty($province) || empty($district) || empty($subdistrict) || empty($postal_code)) {
    $errors[] = 'กรุณากรอกข้อมูลที่อยู่ให้ครบถ้วน';
}

// หากมีข้อผิดพลาด ให้กลับไป
if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    $_SESSION['checkout_data'] = $_POST;
    header('Location: checkout.php');
    exit;
}

// สร้างรหัสออเดอร์
$order_number = $conn->real_escape_string('ORD-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -6)));

// ดึงข้อมูลตะกร้า
$cart = getCart();
$total_amount = getCartTotal();

// บันทึกออเดอร์ (ใช้ Prepared Statements)
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : NULL;
$shipping_cost = 0;
$status = 'pending';

$full_address = "$shipping_address $subdistrict $district $province $postal_code";

$stmt = $conn->prepare("INSERT INTO orders (
    user_id, order_number, total_amount, shipping_cost, status, 
    payment_method, customer_name, customer_email, customer_phone, 
    shipping_address, notes, created_at
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
)");

$stmt->bind_param(
    "isddsssssss",
    $user_id,
    $order_number,
    $total_amount,
    $shipping_cost,
    $status,
    $payment_method,
    $customer_name,
    $customer_email,
    $customer_phone,
    $full_address,
    $notes
);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;
        
    // บันทึกรายการสินค้า
    foreach ($cart as $item) {
        $product_id = intval($item['id']);
        $product_name = $item['name'];
        $product_price = floatval($item['price']);
        $quantity = intval($item['quantity']);
        $subtotal = $product_price * $quantity;
        
        $item_stmt = $conn->prepare("INSERT INTO order_items (
            order_id, product_id, product_name, product_price, quantity, subtotal
        ) VALUES (
            ?, ?, ?, ?, ?, ?
        )");
        
        $item_stmt->bind_param("issdid", $order_id, $product_id, $product_name, $product_price, $quantity, $subtotal);
        $item_stmt->execute();
        $item_stmt->close();
    }
    
    $stmt->close();
    
    // ล้างตะกร้า
    clearCart();
    
    // เก็บข้อมูลออเดอร์ไว้ใน session
    $_SESSION['last_order_id'] = $order_id;
    $_SESSION['last_order_number'] = $order_number;
    
    // ไปที่หน้าconfirmation
    header('Location: order_confirmation.php?order_id=' . $order_id . '&order_number=' . urlencode($order_number));
    exit;
    
} else {
    $_SESSION['checkout_error'] = 'เกิดข้อผิดพลาดในการบันทึกออเดอร์: ' . $stmt->error;
    $stmt->close();
    header('Location: checkout.php');
    exit;
}

mysqli_close($conn);
?>
