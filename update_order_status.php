<?php
// ไฟล์: update_order_status.php
// API สำหรับอัปเดตสถานะออเดอร์

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบว่า Admin
if (!isset($_SESSION['admin_username'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์']);
    exit;
}

include 'includes/db_config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['order_id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
    exit;
}

$order_id = intval($data['order_id']);
$new_status = $conn->real_escape_string($data['status']);

// ตรวจสอบสถานะที่อนุญาต
$valid_statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
if (!in_array($new_status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'สถานะไม่ถูกต้อง']);
    exit;
}

// อัปเดตสถานะ
$sql = "UPDATE orders SET status = '$new_status', updated_at = NOW() WHERE id = $order_id";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        'success' => true,
        'message' => 'อัปเดตสถานะสำเร็จ'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'เกิดข้อผิดพลาด: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>
