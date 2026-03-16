<?php
include 'includes/db_config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product_name = 'Unknown Product';
$product_price = '0';
$product_img = 'otop/CrispyPineapple.jpg';
$product_desc = 'ไม่มีคำอธิบายสินค้า';
$images = [];

if ($product_id > 0) {
    $sql = "SELECT * FROM otop_products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $product_name = htmlspecialchars($row['name']);
        $product_price = htmlspecialchars($row['price']);
        
        $product_img = !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : $product_img;
        
        if (!empty($row['description'])) {
            $product_desc = htmlspecialchars($row['description']);
        }

        // เก็บรูปลง array ไว้แสดงใน thumbnail
        if (!empty($row['image_url'])) $images[] = htmlspecialchars($row['image_url']);
        if (!empty($row['image_url_2'])) $images[] = htmlspecialchars($row['image_url_2']);
        if (!empty($row['image_url_3'])) $images[] = htmlspecialchars($row['image_url_3']);
        if (!empty($row['image_url_4'])) $images[] = htmlspecialchars($row['image_url_4']);
    }
}

include 'includes/header.php';
?>

<style>
    .order-section {
        padding: 60px 0;
        background: linear-gradient(135deg, #f8fffe 0%, #f0f7f5 100%);
        min-height: calc(100vh - 200px);
    }

    .order-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 77, 64, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 77, 64, 0.05);
    }

    .order-card .row {
        align-items: stretch;
    }

    /* === Image Gallery === */
    .gallery-section {
        padding: 30px;
        background: #fafafa;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .main-image-wrapper {
        width: 100%;
        height: 400px;
        border-radius: 16px;
        overflow: hidden;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .main-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }

    .main-image-wrapper:hover img {
        transform: scale(1.03);
    }

    .thumbnails-row {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        justify-content: center;
    }

    .thumbnails-row img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .thumbnails-row img:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .thumbnails-row img.active {
        border-color: #004d40;
        box-shadow: 0 4px 12px rgba(0, 77, 64, 0.3);
    }

    /* === Product Info === */
    .info-section {
        padding: 35px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .product-title {
        font-family: 'Kanit', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #004d40;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .product-price-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #2e7d32;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        font-family: 'Kanit', sans-serif;
    }

    .product-price-tag i {
        font-size: 1.2rem;
    }

    .desc-label {
        font-weight: 700;
        color: #004d40;
        font-size: 1.1rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .desc-text {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.7;
        white-space: pre-line;
        padding: 12px 16px;
        background: #f9fafb;
        border-radius: 10px;
        border-left: 4px solid #004d40;
        margin-bottom: 20px;
        max-height: 120px;
        overflow-y: auto;
    }

    /* === Divider === */
    .section-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e0e0e0, transparent);
        margin: 15px 0;
    }

    /* === Quantity Control === */
    .qty-label {
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
        font-size: 1rem;
    }

    .qty-control {
        display: inline-flex;
        align-items: center;
        gap: 0;
        background: #f5f5f5;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e0e0e0;
        margin-bottom: 20px;
        width: fit-content;
    }

    .qty-control button {
        width: 48px;
        height: 48px;
        font-size: 1.4rem;
        border: none;
        background: transparent;
        color: #004d40;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: bold;
    }

    .qty-control button:hover {
        background: #004d40;
        color: #fff;
    }

    .qty-control input {
        width: 65px;
        height: 48px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 600;
        border: none;
        background: #fff;
        color: #333;
        border-left: 2px solid #e0e0e0;
        border-right: 2px solid #e0e0e0;
    }

    .qty-control input:focus {
        outline: none;
    }

    /* === Action Buttons === */
    .action-buttons {
        display: flex;
        flex-direction: row;
        gap: 12px;
    }

    .btn-add-cart {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: 700;
        border: 2px solid #004d40;
        background: #fff;
        color: #004d40;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add-cart:hover {
        background: #004d40;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 77, 64, 0.3);
    }

    .btn-buy-now {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: 700;
        border: none;
        background: linear-gradient(135deg, #004d40, #00796b);
        color: #fff;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-buy-now:hover {
        background: linear-gradient(135deg, #00332b, #004d40);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 77, 64, 0.4);
    }

    /* === Back Link === */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #004d40;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 25px;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .back-link:hover {
        color: #00796b;
        transform: translateX(-5px);
    }

    /* === Responsive === */
    @media (max-width: 991px) {
        .gallery-section {
            padding: 20px;
        }
        .info-section {
            padding: 30px 20px;
        }
        .main-image-wrapper {
            height: 350px;
        }
        .product-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 575px) {
        .main-image-wrapper {
            height: 280px;
        }
        .thumbnails-row img {
            width: 60px;
            height: 60px;
        }
        .product-title {
            font-size: 1.5rem;
        }
    }
</style>

<section class="order-section">
    <div class="container">
        <a href="product.php" class="back-link">
            <i class="fas fa-arrow-left"></i> กลับไปหน้าสินค้า
        </a>

        <div class="order-card">
            <div class="row g-0">
                <!-- ฝั่งซ้าย: รูปภาพ -->
                <div class="col-lg-6">
                    <div class="gallery-section">
                        <div class="main-image-wrapper">
                            <img id="main-product-image" src="<?= $product_img ?>" alt="<?= $product_name ?>">
                        </div>
                        <?php if(count($images) > 1): ?>
                        <div class="thumbnails-row">
                            <?php foreach($images as $index => $img): ?>
                                <img src="<?= $img ?>" alt="รูปที่ <?= $index+1 ?>" onclick="changeMainImage('<?= $img ?>', this)" class="<?= $index === 0 ? 'active' : '' ?>">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ฝั่งขวา: ข้อมูลสินค้า -->
                <div class="col-lg-6">
                    <div class="info-section">
                        <h1 class="product-title"><?= $product_name ?></h1>
                        <div>
                            <span class="product-price-tag">
                                <i class="fas fa-tag"></i> ฿<?= $product_price ?>
                            </span>
                        </div>

                        <div class="section-divider"></div>

                        <p class="desc-label"><i class="fas fa-info-circle"></i> คำอธิบายสินค้า</p>
                        <div class="desc-text"><?= $product_desc ?></div>

                        <p class="qty-label"><i class="fas fa-boxes me-1"></i> จำนวน</p>
                        <div class="qty-control">
                            <button onclick="decreaseQuantity()">−</button>
                            <input type="number" id="quantity" value="1" min="1">
                            <button onclick="increaseQuantity()">+</button>
                        </div>

                        <div class="action-buttons">
                            <button class="btn-add-cart" onclick="addToCart()">
                                <i class="fas fa-cart-plus"></i> เพิ่มลงตะกร้า
                            </button>
                            <button class="btn-buy-now" onclick="buyNow()">
                                <i class="fas fa-bolt"></i> ซื้อเลย
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    }

    function changeMainImage(src, element) {
        const mainImage = document.getElementById('main-product-image');
        mainImage.src = src;

        const thumbnails = document.querySelectorAll('.thumbnails-row img');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }

    function addToCart() {
        const productId = <?= $product_id ?>;
        const quantity = parseInt(document.getElementById('quantity').value);
        
        if (productId === 0) {
            Swal.fire({icon: 'error', title: 'ไม่พบสินค้า', text: 'กรุณาลองใหม่อีกครั้ง!'});
            return;
        }

        if (quantity < 1) {
            Swal.fire({icon: 'warning', title: 'โปรดระบุจำนวน', text: 'โปรดเลือกจำนวนที่มากกว่า 0'});
            return;
        }

        fetch('add_to_cart_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'เข้าตะกร้าแล้ว!',
                    text: 'เพิ่มสินค้าสำเร็จ (' + data.cart_count + ' ชิ้น)',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.style.marginTop = '80px';
                    }
                });
                
                document.getElementById('quantity').value = 1;
                updateCartBadge();
            } else {
                Swal.fire({icon: 'error', title: 'เพิ่มสินค้าล้มเหลว', text: data.message || 'เกิดข้อผิดพลาด'});
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้'});
        });
    }

    function buyNow() {
        addToCart();
        setTimeout(() => {
            window.location.href = 'cart.php';
        }, 500);
    }

    function updateCartBadge() {
        fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.textContent = data.count;
                if (data.count > 0) {
                    badge.style.display = 'inline-block';
                }
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>