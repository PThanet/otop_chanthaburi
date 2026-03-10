<?php 
include 'includes/header.php';

$cart = getCart();
$total = getCartTotal();
?>

<style>
    .cart-container {
        min-height: 500px;
        margin: 40px auto;
        max-width: 1200px;
    }

    .cart-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .cart-header h2 {
        color: #004d40;
        font-weight: bold;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .cart-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        margin: 0 auto;
        width: 100%;
    }

    .cart-items {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .cart-item {
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: 20px;
        padding: 20px;
        border-bottom: 1px solid #eee;
        align-items: center;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-details h4 {
        color: #004d40;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .item-details p {
        color: #666;
        margin: 0;
        font-size: 0.9rem;
    }

    .item-price {
        font-size: 1.3rem;
        color: #ff6b6b;
        font-weight: bold;
        margin: 10px 0;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-controls button {
        width: 35px;
        height: 35px;
        border: none;
        background: #ffc107;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }

    .quantity-controls button:hover {
        background: #ffb300;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .item-actions {
        text-align: right;
    }

    .btn-remove {
        background: #ff4d4f;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        font-size: 0.9rem;
    }

    .btn-remove:hover {
        background: #ff7875;
    }

    .cart-summary {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .summary-item.total {
        border-top: 2px solid #eee;
        padding-top: 15px;
        font-size: 1.3rem;
        font-weight: bold;
        color: #004d40;
    }

    .btn-checkout {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #004d40, #00695c);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: bold;
        margin-top: 20px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
    }

    .btn-continue {
        width: 100%;
        padding: 12px;
        background: #e0e0e0;
        color: #004d40;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        margin-top: 10px;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .btn-continue:hover {
        background: #d0d0d0;
    }

    .empty-cart {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin: 0 auto;
        max-width: 600px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-cart i {
        font-size: 5rem;
        color: #ccc;
        margin-bottom: 20px;
    }

    .empty-cart h3 {
        color: #004d40;
        margin-bottom: 15px;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .empty-cart p {
        color: #999;
        font-size: 1.05rem;
        margin: 0;
    }

    .empty-cart .btn {
        margin-top: 25px;
        transition: 0.3s;
    }

    .empty-cart .btn-primary {
        background: linear-gradient(135deg, #004d40, #00695c) !important;
        border: none;
        padding: 12px 40px;
        font-size: 1.1rem;
    }

    .empty-cart .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
    }

    @media (max-width: 992px) {
        .cart-content {
            grid-template-columns: 1fr;
        }

        .cart-summary {
            position: static;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .cart-container {
            margin: 20px 15px;
            max-width: calc(100% - 30px);
        }

        .cart-item {
            grid-template-columns: 100px 1fr;
            gap: 15px;
            padding: 15px;
        }

        .item-actions {
            grid-column: 1 / -1;
            text-align: left;
        }

        .empty-cart {
            padding: 60px 20px;
            max-width: 100%;
        }

        .cart-header h2 {
            font-size: 2rem;
        }

        .item-image {
            width: 100px;
            height: 100px;
        }
    }
</style>

<div class="container cart-container">
    <div class="cart-header">
        <h2><i class="fas fa-shopping-cart me-2" style="color: #ff6b6b;"></i>ตะกร้าสินค้า</h2>
        <p class="text-muted">ตรวจสอบและชำระเงินสินค้าของคุณ</p>
    </div>

    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <i class="fas fa-inbox"></i>
            <h3>ตะกร้าสินค้าว่างเปล่า</h3>
            <p class="text-muted mb-4">คุณยังไม่ได้เพิ่มสินค้าใด ๆ เข้าตะกร้า</p>
            <a href="product.php" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-basket me-2"></i>ไปเลือกสินค้า
            </a>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <div class="cart-items">
                <?php foreach ($cart as $item): ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-image">
                        
                        <div class="item-details">
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <div class="item-price">฿<?= number_format($item['price'], 2) ?></div>
                            <div class="quantity-controls">
                                <button onclick="updateQuantity(<?= $item['id'] ?>, -1)">-</button>
                                <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" id="qty-<?= $item['id'] ?>" onchange="updateQuantity(<?= $item['id'] ?>, 0)">
                                <button onclick="updateQuantity(<?= $item['id'] ?>, 1)">+</button>
                            </div>
                        </div>

                        <div class="item-actions">
                            <div style="font-size: 1.2rem; color: #004d40; font-weight: bold; margin-bottom: 15px;">
                                ฿<?= number_format($item['price'] * $item['quantity'], 2) ?>
                            </div>
                            <button class="btn-remove" onclick="removeItem(<?= $item['id'] ?>)">
                                <i class="fas fa-trash me-1"></i>ลบ
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h5 style="color: #004d40; margin-bottom: 20px; font-weight: bold;">สรุปรายการสั่งซื้อ</h5>
                
                <div class="summary-item">
                    <span>จำนวนรายการสินค้า:</span>
                    <span><?= getCartItemCount() ?> รายการ</span>
                </div>

                <div class="summary-item">
                    <span>จำนวนชิ้น:</span>
                    <span><?= getCartCount() ?> ชิ้น</span>
                </div>

                <div class="summary-item">
                    <span>ค่าขนส่ง:</span>
                    <span class="text-success">ฟรี</span>
                </div>

                <div class="summary-item total">
                    <span>รวมทั้งสิ้น:</span>
                    <span>฿<?= number_format($total, 2) ?></span>
                </div>

                <button class="btn-checkout" onclick="window.location.href='checkout.php'">
                    <i class="fas fa-credit-card me-2"></i>ชำระเงิน
                </button>

                <a href="product.php" class="btn-continue">
                    <i class="fas fa-arrow-left me-2"></i>ซื้อสินค้าต่อ
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(productId, change) {
    const input = document.getElementById('qty-' + productId);
    let newQuantity = change === 0 ? parseInt(input.value) : parseInt(input.value) + change;
    
    if (newQuantity < 1) return;
    
    input.value = newQuantity;
    
    // ส่ง request ไปที่ update handler
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeItem(productId) {
    if (confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าใช่หรือไม่?')) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>
