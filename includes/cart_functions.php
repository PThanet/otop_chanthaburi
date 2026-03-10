<?php
// ไฟล์: includes/cart_functions.php
// ฟังก์ชันจัดการตะกร้าสินค้า

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// สร้าง session สำหรับตะกร้า
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/**
 * เพิ่มสินค้าเข้าตะกร้า
 * @param int $product_id - ID สินค้า
 * @param int $quantity - จำนวนสินค้า
 * @param array $product_data - ข้อมูลสินค้า (name, price, image_url)
 */
function addToCart($product_id, $quantity, $product_data) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // ก่อนเช็คว่าสินค้านี้มีในตะกร้าแล้วหรือไม่
    if (isset($_SESSION['cart'][$product_id])) {
        // ถ้ามีแล้ว เพิ่มจำนวน
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // ถ้ายังไม่มี เพิ่มสินค้าใหม่
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'name' => $product_data['name'],
            'price' => $product_data['price'],
            'quantity' => $quantity,
            'image_url' => $product_data['image_url']
        ];
    }
    
    return true;
}

/**
 * ลบสินค้าจากตะกร้า
 * @param int $product_id - ID สินค้า
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    return false;
}

/**
 * อัปเดตจำนวนสินค้า
 * @param int $product_id - ID สินค้า
 * @param int $quantity - จำนวนใหม่
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            return true;
        } else {
            removeFromCart($product_id);
            return true;
        }
    }
    return false;
}

/**
 * ยางชำนวนสินค้าในตะกร้า
 */
function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

/**
 * ได้ข้อมูลตะกร้าทั้งหมด
 */
function getCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

/**
 * คำนวณราคารวมทั้งหมด
 */
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

/**
 * ล้างตะกร้า
 */
function clearCart() {
    $_SESSION['cart'] = [];
    return true;
}

/**
 * จำนวนประเภทสินค้า (ไม่นับจำนวน)
 */
function getCartItemCount() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return count($_SESSION['cart']);
}
?>
