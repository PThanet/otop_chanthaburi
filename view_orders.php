<?php
include 'includes/header.php';
include 'includes/db_config.php';

// แสดงออเดอร์ทั้งหมดหรือของผู้ใช้ที่ login
$user_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;

$orders = [];
$where = '';

// ถ้าผู้ใช้ login ให้แสดงแค่ของตัวเอง
if ($user_id > 0) {
    $where = "WHERE user_id = $user_id";
} else {
    // ถ้าไม่ login ให้แสดงออเดอร์ล่าสุด (ใหม่ 30 วัน)
    $where = "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
}

$sql = "SELECT * FROM orders $where ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($order = mysqli_fetch_assoc($result)) {
        $orders[] = $order;
    }
}
?>

<style>
    .orders-container {
        min-height: 600px;
        margin: 40px auto;
        max-width: 1100px;
    }

    .orders-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .orders-header h2 {
        color: #004d40;
        font-weight: bold;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .orders-list {
        display: grid;
        gap: 20px;
    }

    .order-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
    }

    .order-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .order-card-header {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 30px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
        align-items: start;
    }

    .order-number {
        display: flex;
        flex-direction: column;
    }

    .order-number-label {
        color: #999;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .order-number-value {
        font-size: 1.3rem;
        font-weight: bold;
        color: #004d40;
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }

    .order-status {
        text-align: right;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #cfe2ff;
        color: #084298;
    }

    .status-processing {
        background: #e2e3e5;
        color: #41464b;
    }

    .status-shipped {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-delivered {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-date {
        font-size: 0.85rem;
        color: #999;
        margin-top: 5px;
    }

    .order-body {
        margin-bottom: 15px;
    }

    .order-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 15px;
    }

    .order-info {
        flex: 1;
    }

    .order-info-label {
        color: #999;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .order-info-value {
        color: #333;
        font-weight: 600;
    }

    .order-items {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: 0.95rem;
        border-bottom: 1px solid #eee;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-name {
        flex: 1;
        color: #333;
    }

    .item-qty {
        color: #999;
        margin: 0 15px;
        min-width: 50px;
        text-align: center;
    }

    .item-price {
        color: #ff6b6b;
        font-weight: bold;
        min-width: 100px;
        text-align: right;
    }

    .order-footer {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .order-total {
        font-size: 1.2rem;
        font-weight: bold;
        color: #004d40;
    }

    .order-total-amount {
        color: #ff6b6b;
        font-size: 1.4rem;
    }

    .order-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s;
        font-size: 0.9rem;
    }

    .btn-detail {
        background: #004d40;
        color: white;
    }

    .btn-detail:hover {
        background: #00695c;
    }

    .btn-repeat {
        background: #ffc107;
        color: #333;
    }

    .btn-repeat:hover {
        background: #ffb300;
    }

    .empty-orders {
        text-align: center;
        background: white;
        border-radius: 15px;
        padding: 80px 40px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-orders i {
        font-size: 5rem;
        color: #ccc;
        margin-bottom: 20px;
        display: block;
    }

    .empty-orders h3 {
        color: #004d40;
        margin-bottom: 15px;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .empty-orders p {
        color: #999;
        font-size: 1.05rem;
        margin-bottom: 20px;
    }

    .btn-shop {
        background: linear-gradient(135deg, #004d40, #00695c);
        color: white;
        padding: 12px 40px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-shop:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
    }

    @media (max-width: 768px) {
        .orders-container {
            margin: 20px 15px;
            max-width: calc(100% - 30px);
        }

        .orders-header h2 {
            font-size: 2rem;
        }

        .order-card-header {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .order-status {
            text-align: left;
        }

        .order-row {
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .order-footer {
            grid-template-columns: 1fr;
        }

        .order-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
        }
    }
</style>

<div class="container orders-container">
    <div class="orders-header">
        <h2><i class="fas fa-list me-2" style="color: #004d40;"></i>ประวัติการสั่งซื้อ</h2>
        <?php if ($user_id > 0): ?>
            <p class="text-muted">ดูประวัติการสั่งซื้อของคุณ</p>
        <?php else: ?>
            <p class="text-muted">ดูออเดอร์ล่าสุด 30 วัน</p>
        <?php endif; ?>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <i class="fas fa-inbox"></i>
            <h3>ยังไม่มีออเดอร์</h3>
            <p>คุณยังไม่มีประวัติการสั่งซื้อ</p>
            <a href="product.php" class="btn-shop">
                <i class="fas fa-shopping-basket me-2"></i>ไปเลือกสินค้า
            </a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): 
                // ดึงรายการสินค้า
                $items_sql = "SELECT * FROM order_items WHERE order_id = {$order['id']}";
                $items_result = mysqli_query($conn, $items_sql);
                $order_items = [];
                while ($item = mysqli_fetch_assoc($items_result)) {
                    $order_items[] = $item;
                }
            ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <div>
                            <div class="order-number-label">เลขที่ออเดอร์</div>
                            <div class="order-number-value"><?= htmlspecialchars($order['order_number']) ?></div>
                            <div class="order-date"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?= $order['status'] ?>">
                                <?php 
                                $status_text = [
                                    'pending' => 'รอการยืนยัน',
                                    'confirmed' => 'ยืนยันแล้ว',
                                    'processing' => 'กำลังเตรียม',
                                    'shipped' => 'ส่งแล้ว',
                                    'delivered' => 'ส่งถึงแล้ว',
                                    'cancelled' => 'ยกเลิก'
                                ];
                                echo $status_text[$order['status']] ?? $order['status'];
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-row">
                            <div class="order-info">
                                <div class="order-info-label">ผู้ซื้อ</div>
                                <div class="order-info-value"><?= htmlspecialchars($order['customer_name']) ?></div>
                            </div>
                            <div class="order-info">
                                <div class="order-info-label">เบอร์โทร</div>
                                <div class="order-info-value"><?= htmlspecialchars($order['customer_phone']) ?></div>
                            </div>
                            <div class="order-info">
                                <div class="order-info-label">วิธีชำระเงิน</div>
                                <div class="order-info-value">
                                    <?php
                                    $payment_text = [
                                        'bank_transfer' => 'โอนธนาคาร',
                                        'qr_code' => 'QR Code',
                                        'cash_on_delivery' => 'COD'
                                    ];
                                    echo $payment_text[$order['payment_method']] ?? $order['payment_method'];
                                    ?>
                                </div>
                            </div>
                            <div class="order-info">
                                <div class="order-info-label">จำนวนรายการ</div>
                                <div class="order-info-value"><?= count($order_items) ?> รายการ</div>
                            </div>
                        </div>

                        <div class="order-items">
                            <?php foreach ($order_items as $item): ?>
                                <div class="order-item">
                                    <div class="item-name"><?= htmlspecialchars($item['product_name']) ?></div>
                                    <div class="item-qty">x<?= $item['quantity'] ?></div>
                                    <div class="item-price">฿<?= number_format($item['subtotal'], 2) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="order-footer">
                        <div>
                            <div class="order-total">
                                รวมทั้งสิ้น:
                                <span class="order-total-amount">฿<?= number_format($order['total_amount'], 2) ?></span>
                            </div>
                        </div>
                        <div class="order-actions">
                            <a href="order_confirmation.php?order_id=<?= $order['id'] ?>&order_number=<?= htmlspecialchars($order['order_number']) ?>" class="btn-action btn-detail">
                                <i class="fas fa-eye me-1"></i>ดูรายละเอียด
                            </a>
                            <button class="btn-action btn-repeat" onclick="alert('ฟีเจอร์นี้อยู่ระหว่างพัฒนา')">
                                <i class="fas fa-redo me-1"></i>สั่งซื้ออีก
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
