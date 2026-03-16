<?php 
include 'includes/header.php';

$cart = getCart();
$total = getCartTotal();
?>

<style>
    .cart-section {
        padding: 50px 0 60px;
        background: linear-gradient(135deg, #f8fffe 0%, #f0f7f5 100%);
        min-height: calc(100vh - 200px);
    }

    /* === Header === */
    .cart-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 35px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .cart-page-header .title-area h2 {
        font-family: 'Kanit', sans-serif;
        color: #004d40;
        font-weight: 700;
        font-size: 2rem;
        margin: 0;
    }

    .cart-page-header .title-area p {
        color: #999;
        margin: 5px 0 0;
        font-size: 0.95rem;
    }

    .btn-history-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: #fff;
        color: #004d40;
        border: 2px solid #004d40;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-history-link:hover {
        background: #004d40;
        color: #fff;
        box-shadow: 0 6px 18px rgba(0, 77, 64, 0.25);
        transform: translateY(-2px);
    }

    /* === Layout === */
    .cart-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 28px;
        align-items: start;
    }

    /* === Cart Items Card === */
    .cart-items-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        border: 1px solid rgba(0, 77, 64, 0.05);
    }

    .cart-items-header {
        padding: 18px 28px;
        background: linear-gradient(135deg, #004d40, #00695c);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto;
        gap: 20px;
        padding: 22px 28px;
        border-bottom: 1px solid #f0f0f0;
        align-items: center;
        transition: background 0.2s ease;
    }

    .cart-item:hover {
        background: #fafffe;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .item-info h4 {
        font-family: 'Kanit', sans-serif;
        color: #004d40;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0 0 6px;
    }

    .item-unit-price {
        color: #2e7d32;
        font-weight: 600;
        font-size: 1rem;
        margin: 0 0 12px;
    }

    .qty-row {
        display: inline-flex;
        align-items: center;
        background: #f5f5f5;
        border-radius: 10px;
        overflow: hidden;
        border: 1.5px solid #e0e0e0;
    }

    .qty-row button {
        width: 34px;
        height: 34px;
        border: none;
        background: transparent;
        color: #004d40;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-row button:hover {
        background: #004d40;
        color: #fff;
    }

    .qty-row input {
        width: 44px;
        height: 34px;
        text-align: center;
        border: none;
        background: #fff;
        font-weight: 600;
        font-size: 0.95rem;
        border-left: 1.5px solid #e0e0e0;
        border-right: 1.5px solid #e0e0e0;
    }

    .qty-row input:focus {
        outline: none;
    }

    .item-subtotal {
        text-align: right;
        font-family: 'Kanit', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #004d40;
        white-space: nowrap;
    }

    .btn-del {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: #fff0f0;
        color: #e53935;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-del:hover {
        background: #e53935;
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(229, 57, 53, 0.3);
    }

    /* === Summary Card === */
    .summary-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        position: sticky;
        top: 100px;
        border: 1px solid rgba(0, 77, 64, 0.05);
    }

    .summary-header {
        padding: 18px 25px;
        background: linear-gradient(135deg, #004d40, #00695c);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .summary-body {
        padding: 25px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        font-size: 0.95rem;
        color: #666;
    }

    .summary-row.shipping .val {
        color: #2e7d32;
        font-weight: 600;
        background: #e8f5e9;
        padding: 3px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 0 0;
        margin-top: 12px;
        border-top: 2px dashed #e0e0e0;
        font-family: 'Kanit', sans-serif;
    }

    .summary-total .label {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
    }

    .summary-total .amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #004d40;
    }

    .btn-pay {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #004d40, #00796b);
        color: #fff;
        border: none;
        border-radius: 14px;
        font-size: 1.1rem;
        font-weight: 700;
        margin-top: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-pay:hover {
        background: linear-gradient(135deg, #00332b, #004d40);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 77, 64, 0.35);
    }

    .btn-keep-shopping {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background: transparent;
        color: #004d40;
        border: 2px solid #e0e0e0;
        border-radius: 14px;
        font-weight: 600;
        margin-top: 10px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-keep-shopping:hover {
        border-color: #004d40;
        background: #f0fdf4;
        color: #004d40;
    }

    /* === Empty Cart === */
    .empty-cart-card {
        text-align: center;
        padding: 80px 40px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        max-width: 550px;
        margin: 0 auto;
        border: 1px solid rgba(0, 77, 64, 0.05);
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        font-size: 2.5rem;
        color: #004d40;
    }

    .empty-cart-card h3 {
        font-family: 'Kanit', sans-serif;
        color: #004d40;
        font-weight: 700;
        font-size: 1.6rem;
        margin-bottom: 10px;
    }

    .empty-cart-card p {
        color: #999;
        font-size: 1rem;
        margin-bottom: 30px;
    }

    .btn-go-shop {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 35px;
        background: linear-gradient(135deg, #004d40, #00796b);
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 1.05rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-go-shop:hover {
        background: linear-gradient(135deg, #00332b, #004d40);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
        color: #fff;
    }

    /* === Responsive === */
    @media (max-width: 992px) {
        .cart-layout {
            grid-template-columns: 1fr;
        }
        .summary-card {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .cart-section {
            padding: 30px 0;
        }
        .cart-page-header .title-area h2 {
            font-size: 1.6rem;
        }
        .cart-item {
            grid-template-columns: 80px 1fr;
            gap: 14px;
            padding: 16px 20px;
        }
        .item-img {
            width: 80px;
            height: 80px;
        }
        .item-subtotal,
        .btn-del {
            grid-column: 2;
        }
        .item-subtotal {
            text-align: left;
        }
        .empty-cart-card {
            padding: 50px 25px;
        }
    }
</style>

<section class="cart-section">
    <div class="container">
        <!-- Header -->
        <div class="cart-page-header">
            <div class="title-area">
                <h2><i class="fas fa-shopping-cart me-2" style="color: #ffc107;"></i>ตะกร้าสินค้า</h2>
                <p>ตรวจสอบรายการและชำระเงินสินค้าของคุณ</p>
            </div>
            <?php if (isset($_SESSION['user_id']) || isset($_SESSION['admin_username'])): ?>
                <a href="view_orders.php" class="btn-history-link">
                    <i class="fas fa-history"></i> ประวัติการสั่งซื้อ
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($cart)): ?>
            <!-- ตะกร้าว่าง -->
            <div class="empty-cart-card">
                <div class="empty-icon">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <h3>ตะกร้าสินค้าว่างเปล่า</h3>
                <p>คุณยังไม่ได้เพิ่มสินค้าใด ๆ เข้าตะกร้า</p>
                <a href="product.php" class="btn-go-shop">
                    <i class="fas fa-store"></i> ไปเลือกสินค้า
                </a>
            </div>
        <?php else: ?>
            <!-- มีสินค้าในตะกร้า -->
            <div class="cart-layout">
                <!-- รายการสินค้า -->
                <div class="cart-items-card">
                    <div class="cart-items-header">
                        <i class="fas fa-box-open"></i> รายการสินค้าในตะกร้า (<?= getCartItemCount() ?> รายการ)
                    </div>
                    <?php foreach ($cart as $item): ?>
                        <div class="cart-item">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-img">
                            
                            <div class="item-info">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <div class="item-unit-price">฿<?= number_format($item['price'], 2) ?></div>
                                <div class="qty-row">
                                    <button onclick="updateQuantity(<?= $item['id'] ?>, -1)">−</button>
                                    <input type="number" value="<?= $item['quantity'] ?>" min="1" id="qty-<?= $item['id'] ?>" onchange="updateQuantity(<?= $item['id'] ?>, 0)">
                                    <button onclick="updateQuantity(<?= $item['id'] ?>, 1)">+</button>
                                </div>
                            </div>

                            <div class="item-subtotal">฿<?= number_format($item['price'] * $item['quantity'], 2) ?></div>

                            <button class="btn-del" onclick="removeItem(<?= $item['id'] ?>)" title="ลบสินค้า">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- สรุปยอด -->
                <div class="summary-card">
                    <div class="summary-header">
                        <i class="fas fa-receipt"></i> สรุปรายการสั่งซื้อ
                    </div>
                    <div class="summary-body">
                        <div class="summary-row">
                            <span>จำนวนรายการสินค้า</span>
                            <span><?= getCartItemCount() ?> รายการ</span>
                        </div>
                        <div class="summary-row">
                            <span>จำนวนชิ้น</span>
                            <span><?= getCartCount() ?> ชิ้น</span>
                        </div>
                        <div class="summary-row shipping">
                            <span>ค่าขนส่ง</span>
                            <span class="val"><i class="fas fa-truck me-1"></i>ฟรี</span>
                        </div>

                        <div class="summary-total">
                            <span class="label">รวมทั้งสิ้น</span>
                            <span class="amount">฿<?= number_format($total, 2) ?></span>
                        </div>

                        <button class="btn-pay" onclick="window.location.href='checkout.php'">
                            <i class="fas fa-lock"></i> ชำระเงิน
                        </button>
                        <a href="product.php" class="btn-keep-shopping">
                            <i class="fas fa-arrow-left"></i> ซื้อสินค้าต่อ
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function updateQuantity(productId, change) {
    const input = document.getElementById('qty-' + productId);
    let newQuantity = change === 0 ? parseInt(input.value) : parseInt(input.value) + change;
    
    if (newQuantity < 1) return;
    
    input.value = newQuantity;
    
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
    Swal.fire({
        title: 'ยืนยันการลบสินค้า?',
        text: "คุณต้องการลบสินค้านี้ออกจากตะกร้าใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    Swal.fire({
                        title: 'ลบสำเร็จ!',
                        text: 'สินค้าถูกลบออกจากตะกร้าแล้ว',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
