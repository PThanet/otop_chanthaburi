<?php
include 'includes/header.php';

// ตรวจสอบตะกร้าไม่ว่างเปล่า
if (empty(getCart())) {
    header('Location: product.php');
    exit;
}

$cart = getCart();
$total = getCartTotal();
?>

<style>
    .checkout-container {
        min-height: 600px;
        margin: 40px auto;
        max-width: 1000px;
    }

    .checkout-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .checkout-header h2 {
        color: #004d40;
        font-weight: bold;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        margin: 0 auto;
        width: 100%;
    }

    .checkout-form {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h4 {
        color: #004d40;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ffc107;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #004d40;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 77, 64, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .payment-methods {
        margin-top: 20px;
    }

    .payment-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: 0.3s;
    }

    .payment-option:hover {
        border-color: #ffc107;
        background: #fffbf0;
    }

    .payment-option input[type="radio"] {
        width: 20px;
        height: 20px;
        margin-right: 15px;
        cursor: pointer;
    }

    .payment-option label {
        margin: 0;
        flex: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .payment-icon {
        font-size: 1.5rem;
        margin-right: 15px;
        color: #004d40;
    }

    .checkout-summary {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .summary-header {
        font-size: 1.3rem;
        font-weight: bold;
        color: #004d40;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ffc107;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.95rem;
        color: #666;
    }

    .summary-item-detail {
        font-size: 0.85rem;
        color: #999;
        margin-left: 10px;
    }

    .summary-divider {
        border-top: 1px solid #eee;
        margin: 15px 0;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
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

    .btn-checkout:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
    }

    .btn-checkout:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-cancel {
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

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .required {
        color: #ff4d4f;
    }

    .cart-items-preview {
        background: #f9f9f9;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        max-height: 250px;
        overflow-y: auto;
    }

    .cart-item-preview {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
    }

    .cart-item-preview:last-child {
        border-bottom: none;
    }

    .cart-item-name {
        flex: 1;
        color: #333;
    }

    .cart-item-qty {
        color: #999;
        margin: 0 10px;
    }

    .cart-item-price {
        font-weight: bold;
        color: #ff6b6b;
        min-width: 80px;
        text-align: right;
    }

    @media (max-width: 992px) {
        .checkout-content {
            grid-template-columns: 1fr;
        }

        .checkout-summary {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .checkout-container {
            margin: 20px 15px;
            max-width: calc(100% - 30px);
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .checkout-header h2 {
            font-size: 2rem;
        }
    }
</style>

<div class="container checkout-container">
    <div class="checkout-header">
        <h2><i class="fas fa-credit-card me-2" style="color: #ffc107;"></i>ชำระเงิน</h2>
        <p class="text-muted">กรอกข้อมูลการส่งและเลือกวิธีการชำระเงิน</p>
    </div>

    <div class="checkout-content">
        <form class="checkout-form" id="checkoutForm" method="POST" action="process_checkout.php">
            <!-- ส่วนข้อมูลลูกค้า -->
            <div class="form-section">
                <h4><i class="fas fa-user me-2"></i>ข้อมูลผู้ซื้อ</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>ชื่อ-นามสกุล <span class="required">*</span></label>
                        <input type="text" name="customer_name" required placeholder="เช่น สมชาย ใจดี">
                    </div>
                    <div class="form-group">
                        <label>เบอร์โทรศัพท์ <span class="required">*</span></label>
                        <input type="tel" name="customer_phone" required placeholder="เช่น 0812345678" pattern="[0-9]{10}">
                    </div>
                </div>

                <div class="form-group">
                    <label>อีเมล</label>
                    <input type="email" name="customer_email" placeholder="example@email.com">
                </div>
            </div>

            <!-- ส่วนที่อยู่ส่ง -->
            <div class="form-section">
                <h4><i class="fas fa-map-marker-alt me-2"></i>ที่อยู่ส่ง</h4>
                
                <div class="form-group">
                    <label>ที่อยู่ <span class="required">*</span></label>
                    <textarea name="shipping_address" required placeholder="เช่น บ้านเลขที่ 123 ซอยกำแพง..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>จังหวัด <span class="required">*</span></label>
                        <input type="text" name="province" required placeholder="เช่น จันทบุรี">
                    </div>
                    <div class="form-group">
                        <label>อำเภอ <span class="required">*</span></label>
                        <input type="text" name="district" required placeholder="เช่น เมืองจันทบุรี">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>ตำบล <span class="required">*</span></label>
                        <input type="text" name="subdistrict" required placeholder="เช่น วัฒนา">
                    </div>
                    <div class="form-group">
                        <label>รหัสไปรษณีย์ <span class="required">*</span></label>
                        <input type="text" name="postal_code" required placeholder="เช่น 22000" pattern="[0-9]{5}">
                    </div>
                </div>
            </div>

            <!-- ส่วนวิธีการชำระเงิน -->
            <div class="form-section">
                <h4><i class="fas fa-wallet me-2"></i>วิธีการชำระเงิน</h4>
                
                <div class="payment-methods">
                    <div class="payment-option">
                        <input type="radio" id="payment_transfer" name="payment_method" value="bank_transfer" checked required>
                        <label for="payment_transfer">
                            <span class="payment-icon"><i class="fas fa-university"></i></span>
                            <div>
                                <div style="font-weight: 600;">โอนเงินจากธนาคาร</div>
                                <div style="font-size: 0.85rem; color: #999;">บัญชีธนาคารกรุงไทย</div>
                            </div>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="payment_qr" name="payment_method" value="qr_code" required>
                        <label for="payment_qr">
                            <span class="payment-icon"><i class="fas fa-qrcode"></i></span>
                            <div>
                                <div style="font-weight: 600;">ชำระผ่าน QR Code</div>
                                <div style="font-size: 0.85rem; color: #999;">PromptPay / QR Code ทั้งหมด</div>
                            </div>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="payment_cod" name="payment_method" value="cash_on_delivery" required>
                        <label for="payment_cod">
                            <span class="payment-icon"><i class="fas fa-money-bill"></i></span>
                            <div>
                                <div style="font-weight: 600;">ชำระเมื่อได้รับสินค้า (COD)</div>
                                <div style="font-size: 0.85rem; color: #999;">ชำระเงินสดให้กับผู้ส่ง</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ส่วนหมายเหตุ -->
            <div class="form-section">
                <h4><i class="fas fa-sticky-note me-2"></i>หมายเหตุเพิ่มเติม</h4>
                <div class="form-group">
                    <label>ข้อความ</label>
                    <textarea name="notes" placeholder="เช่น โปรดห่อแบบประหยัด, มีความประสงค์..."></textarea>
                </div>
            </div>

            <button type="submit" class="btn-checkout">
                <i class="fas fa-check-circle me-2"></i>ยืนยันการสั่งซื้อ
            </button>
            <a href="cart.php" class="btn-cancel">
                <i class="fas fa-arrow-left me-2"></i>ย้อนกลับ
            </a>
        </form>

        <!-- ส่วนสรุปรายการ -->
        <div class="checkout-summary">
            <div class="summary-header">
                <i class="fas fa-shopping-bag me-2"></i>สรุปรายการ
            </div>

            <div class="cart-items-preview">
                <?php foreach ($cart as $item): ?>
                    <div class="cart-item-preview">
                        <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="cart-item-qty">x<?= $item['quantity'] ?></div>
                        <div class="cart-item-price">฿<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-item">
                <span>จำนวนรายการ:</span>
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

            <div class="summary-divider"></div>

            <div class="summary-total">
                <span>รวมทั้งสิ้น:</span>
                <span style="color: #ff6b6b;">฿<?= number_format($total, 2) ?></span>
            </div>

            <div style="background: #fffbf0; border-left: 4px solid #ffc107; padding: 15px; border-radius: 5px; margin-top: 20px; font-size: 0.9rem; color: #666;">
                <i class="fas fa-info-circle me-2" style="color: #ffc107;"></i>
                กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนยืนยันการสั่งซื้อ
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
