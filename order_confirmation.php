<?php
include 'includes/header.php';
include 'includes/db_config.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order_number = isset($_GET['order_number']) ? htmlspecialchars($_GET['order_number']) : '';

// ดึงข้อมูลออเดอร์
$order = null;
$order_items = [];

if ($order_id > 0) {
    $sql = "SELECT * FROM orders WHERE id = $order_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);
        
        // ดึงรายการสินค้า
        $items_sql = "SELECT * FROM order_items WHERE order_id = $order_id";
        $items_result = mysqli_query($conn, $items_sql);
        while ($item = mysqli_fetch_assoc($items_result)) {
            $order_items[] = $item;
        }
    }
}

// ถ้าไม่พบออเดอร์ ให้ไปที่หน้า cart
if (!$order) {
    header('Location: cart.php');
    exit;
}
?>

<style>
    .confirmation-container {
        min-height: 600px;
        margin: 40px auto;
        max-width: 1000px;
    }

    .confirmation-success {
        background: linear-gradient(135deg, #34a853, #2d8e47);
        color: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(52, 168, 83, 0.2);
    }

    .confirmation-success i {
        font-size: 4rem;
        margin-bottom: 20px;
        display: block;
    }

    .confirmation-success h2 {
        font-size: 2.2rem;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .order-number {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px 30px;
        border-radius: 10px;
        font-size: 1.1rem;
        margin-top: 15px;
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
    }

    .confirmation-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        margin-top: 30px;
    }

    .confirmation-details {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section h4 {
        color: #004d40;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ffc107;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #666;
        font-weight: 600;
    }

    .detail-value {
        color: #333;
        text-align: right;
    }

    .order-items {
        margin-bottom: 20px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background: #f9f9f9;
        border-radius: 5px;
        margin-bottom: 8px;
    }

    .order-item-name {
        flex: 1;
        font-weight: 600;
        color: #333;
    }

    .order-item-qty {
        color: #999;
        margin: 0 15px;
    }

    .order-item-price {
        color: #ff6b6b;
        font-weight: bold;
        min-width: 100px;
        text-align: right;
    }

    .payment-info {
        background: #fffbf0;
        border-left: 4px solid #ffc107;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .payment-method-title {
        font-weight: bold;
        color: #004d40;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .bank-account {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
    }

    .bank-name {
        font-weight: bold;
        color: #004d40;
        margin-bottom: 8px;
    }

    .bank-detail {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 5px;
        font-family: 'Courier New', monospace;
    }

    .copy-btn {
        background: #ffc107;
        color: #333;
        border: none;
        padding: 5px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: bold;
        transition: 0.3s;
        margin-top: 8px;
        width: 100%;
    }

    .copy-btn:hover {
        background: #ffb300;
    }

    .qr-code {
        text-align: center;
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }

    .qr-code img {
        max-width: 200px;
        margin-bottom: 10px;
    }

    .summary-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #004d40;
        margin-bottom: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        font-size: 1.3rem;
        font-weight: bold;
        color: #004d40;
        border-top: 2px solid #ffc107;
        margin-top: 15px;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: bold;
        text-transform: uppercase;
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

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-primary-action {
        flex: 1;
        padding: 12px;
        background: linear-gradient(135deg, #004d40, #00695c);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .btn-primary-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
    }

    .btn-secondary-action {
        flex: 1;
        padding: 12px;
        background: #e0e0e0;
        color: #004d40;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .btn-secondary-action:hover {
        background: #d0d0d0;
    }

    @media (max-width: 992px) {
        .confirmation-content {
            grid-template-columns: 1fr;
        }

        .summary-section {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .confirmation-container {
            margin: 20px 15px;
            max-width: calc(100% - 30px);
        }

        .confirmation-success {
            padding: 30px 20px;
        }

        .confirmation-success h2 {
            font-size: 1.8rem;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="container confirmation-container">
    <!-- ส่วนสำเร็จ -->
    <div class="confirmation-success">
        <i class="fas fa-check-circle"></i>
        <h2>สั่งซื้อสำเร็จ!</h2>
        <p>ขอบคุณที่สั่งซื้อสินค้าจากเรา เราจะดำเนินการจัดส่งไปให้คุณเร็วที่สุด</p>
        <div class="order-number">
            <i class="fas fa-receipt me-2"></i><?= $order['order_number'] ?>
        </div>
    </div>

    <div class="confirmation-content">
        <div class="confirmation-details">
            <!-- ข้อมูลออเดอร์ -->
            <div class="detail-section">
                <h4><i class="fas fa-info-circle me-2"></i>ข้อมูลออเดอร์</h4>
                <div class="detail-row">
                    <span class="detail-label">เลขที่ออเดอร์:</span>
                    <span class="detail-value" style="font-family: 'Courier New'; letter-spacing: 1px;"><?= $order['order_number'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">วันที่สั่ง:</span>
                    <span class="detail-value"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">สถานะ:</span>
                    <span class="detail-value">
                        <?php 
                        $status_text = [
                            'pending' => 'รอการยืนยัน',
                            'confirmed' => 'ยืนยันแล้ว',
                            'processing' => 'กำลังเตรียม',
                            'shipped' => 'ส่งแล้ว',
                            'delivered' => 'ส่งถึงแล้ว',
                            'cancelled' => 'ยกเลิก'
                        ];
                        $status_icons = [
                            'pending' => 'fa-hourglass-half',
                            'confirmed' => 'fa-check-circle',
                            'processing' => 'fa-box',
                            'shipped' => 'fa-truck',
                            'delivered' => 'fa-home',
                            'cancelled' => 'fa-times-circle'
                        ];
                        $current_status = $order['status'] ?? 'pending';
                        ?>
                        <span class="status-badge status-<?= htmlspecialchars($current_status) ?>">
                            <i class="fas <?= $status_icons[$current_status] ?? 'fa-info-circle' ?> me-1"></i><?= $status_text[$current_status] ?? htmlspecialchars($current_status) ?>
                        </span>
                    </span>
                </div>
            </div>

            <!-- ข้อมูลผู้ซื้อ -->
            <div class="detail-section">
                <h4><i class="fas fa-user me-2"></i>ข้อมูลผู้ซื้อ</h4>
                <div class="detail-row">
                    <span class="detail-label">ชื่อ-นามสกุล:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">เบอร์โทร:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['customer_phone']) ?></span>
                </div>
                <?php if (!empty($order['customer_email'])): ?>
                    <div class="detail-row">
                        <span class="detail-label">อีเมล:</span>
                        <span class="detail-value"><?= htmlspecialchars($order['customer_email']) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ที่อยู่ส่ง -->
            <div class="detail-section">
                <h4><i class="fas fa-map-marker-alt me-2"></i>ที่อยู่ส่ง</h4>
                <div style="color: #333; line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
                </div>
            </div>

            <!-- รายการสินค้า -->
            <div class="detail-section">
                <h4><i class="fas fa-box me-2"></i>รายการสินค้า</h4>
                <div class="order-items">
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <span class="order-item-name"><?= htmlspecialchars($item['product_name']) ?></span>
                            <span class="order-item-qty">x<?= $item['quantity'] ?></span>
                            <span class="order-item-price">฿<?= number_format($item['subtotal'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ข้อมูลการชำระเงิน -->
            <div class="detail-section">
                <h4><i class="fas fa-wallet me-2"></i>วิธีการชำระเงิน</h4>
                
                <?php if ($order['payment_method'] === 'bank_transfer'): ?>
                    <div class="payment-info">
                        <div class="payment-method-title">
                            <i class="fas fa-university me-2"></i>โอนเงินจากธนาคาร
                        </div>
                        <div class="bank-account">
                            <div class="bank-name">ธนาคารกรุงไทย</div>
                            <div class="bank-detail">ชื่อบัญชี: นาย ธเนตร พูลสุข</div>
                            <div class="bank-detail">เลขที่บัญชี: <strong>722-0-80324-9</strong></div>
                            <div class="bank-detail">ประเภท: ออมทรัพย์</div>
                            <button class="copy-btn" onclick="copyToClipboard('123-456-7890')">
                                <i class="fas fa-copy me-1"></i>คัดลอกเลขบัญชี
                            </button>
                        </div>
                    </div>

                <?php elseif ($order['payment_method'] === 'qr_code'): ?>
                    <div class="payment-info">
                        <div class="payment-method-title">
                            <i class="fas fa-qrcode me-2"></i>ชำระผ่าน QR Code
                        </div>
                        <div class="qr-code">
                            <p style="margin-bottom: 15px; color: #666;">สแกน QR Code เพื่อชำระเงิน</p>
                            <img src="otop/qrcode.jpg" alt="QR Code">
                            <p style="color: #999; font-size: 0.9rem;">PromptPay: 0625498216</p>
                        </div>
                    </div>

                <?php elseif ($order['payment_method'] === 'cash_on_delivery'): ?>
                    <div class="payment-info">
                        <div class="payment-method-title">
                            <i class="fas fa-money-bill me-2"></i>ชำระเมื่อได้รับสินค้า
                        </div>
                        <p style="color: #666; margin: 0;">
                            กรุณาเตรียมเงินสดให้พร้อม เวลาที่ผู้ส่งมาถึง
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="action-buttons">
                <?php if (isset($_SESSION['admin_username'])): ?>
                    <a href="admin_orders.php" class="btn-secondary-action" style="flex: unset; width: 100%;">
                        <i class="fas fa-arrow-left me-2"></i>ย้อนกลับไปหน้าจัดการออเดอร์
                    </a>
                <?php else: ?>
                    <a href="product.php" class="btn-secondary-action">
                        <i class="fas fa-shopping-basket me-2"></i>ซื้อสินค้าต่อ
                    </a>
                    <a href="view_orders.php" class="btn-primary-action">
                        <i class="fas fa-list me-2"></i>ดูออเดอร์ของฉัน
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ส่วนสรุป -->
        <div class="summary-section">
            <div class="summary-title">
                <i class="fas fa-file-invoice me-2"></i>สรุปรายการ
            </div>

            <div class="summary-item">
                <span>จำนวนรายการ:</span>
                <span><?= count($order_items) ?> รายการ</span>
            </div>

            <div class="summary-item">
                <span>จำนวนชิ้น:</span>
                <span>
                    <?php 
                    $total_qty = 0;
                    foreach ($order_items as $item) {
                        $total_qty += $item['quantity'];
                    }
                    echo $total_qty;
                    ?>
                    ชิ้น
                </span>
            </div>

            <div class="summary-item">
                <span>ค่าขนส่ง:</span>
                <span class="text-success">ฟรี</span>
            </div>

            <div class="summary-total">
                <span>รวมชำระทั้งสิ้น:</span>
                <span style="color: #ff6b6b;">฿<?= number_format($order['total_amount'], 2) ?></span>
            </div>

            <div style="background: #e8f5e9; border-left: 4px solid #34a853; padding: 15px; border-radius: 5px; margin-top: 20px; font-size: 0.9rem; color: #2e7d32;">
                <i class="fas fa-check-circle me-2"></i>
                <strong>ขั้นตอนต่อไป:</strong>
                <ol style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>ชำระเงินตามวิธีการที่เลือก</li>
                    <li>เราจะตรวจสอบการชำระเงินของคุณ</li>
                    <li>ยืนยันการสั่งซื้อและเตรียมส่ง</li>
                    <li>ส่งสินค้าให้คุณตามที่อยู่ที่ระบุ</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    const temp = document.createElement('textarea');
    temp.value = text;
    document.body.appendChild(temp);
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);
    Swal.fire({
        title: 'คัดลอกเลขบัญชีสำเร็จ!',
        icon: 'success',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}
</script>

<?php include 'includes/footer.php'; ?>
