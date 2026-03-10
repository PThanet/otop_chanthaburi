<?php include('includes/header.php'); ?>

<style>
    /* Styling สำหรับ Card สินค้าให้มีลูกเล่นเหมือนหน้าอื่น */
    .product-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    }

    .product-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .product-img-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 20px 20px 0 0;
    }

    .product-img-wrapper img {
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-img-wrapper img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 50px;
    }

    .btn-order {
        transition: all 0.3s ease;
        font-weight: bold;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-order:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
    }
</style>

<div class="container my-5 py-4">
    <div class="text-center mb-5 animate__animated animate__fadeInDown">
        <h2 class="fw-bold display-5" style="color: #004d40;"><i class="fas fa-shopping-basket me-2 text-success"></i>
            สินค้า OTOP แนะนำ</h2>
        <p class="lead text-muted">สุดยอดผลิตภัณฑ์คุณภาพจากภูมิปัญญาชาวจันทบุรี ส่งตรงถึงมือคุณ</p>
        <div class="mx-auto mt-3" style="width: 80px; height: 4px; background: #ffc107; border-radius: 2px;"></div>
    </div>

    <div class="row g-4 justify-content-center">
        <?php
        include('includes/db_config.php');
        $sql = "SELECT * FROM otop_products ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        // กำหนดระยะเวลา delay ให้แต่ละอันขึ้นไม่พร้อมกัน
        $delay = 0;
        
        if (mysqli_num_rows($result) > 0) {
            while ($p = mysqli_fetch_assoc($result)) { 
                $delay += 200;
        ?>
            <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: <?= $delay ?>ms;">
                <div class="card product-card h-100">
                    <div class="product-img-wrapper">
                        <span class="badge product-badge <?= htmlspecialchars($p['tag_color']) ?>"><?= htmlspecialchars($p['tag']) ?></span>
                        <img src="<?= htmlspecialchars($p['image_url']) ?>" class="card-img-top w-100" style="height:280px; object-fit:cover;" alt="<?= htmlspecialchars($p['name']) ?>">
                    </div>
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-2" style="color: #004d40;"><?= htmlspecialchars($p['name']) ?></h4>
                            <p class="text-success fs-3 fw-bold mb-4">฿<?= htmlspecialchars($p['price']) ?></p>
                        </div>
                        <a href="order.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-order w-100 py-2">
                            <i class="fas fa-cart-plus me-2"></i> สั่งซื้อสินค้า
                        </a>
                    </div>
                </div>
            </div>
        <?php 
            } 
        } else {
            echo "<div class='col-12 text-center text-muted'>ยังไม่มีข้อมูลสินค้า OTOP</div>";
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>