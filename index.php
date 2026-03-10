<?php include('includes/header.php'); ?>

<style>
    /* แก้ไขส่วนพื้นหลังที่ดูจืดชืด ให้ดู Modern ขึ้น */
    .hero-premium {
        position: relative;
        padding: 160px 0;
        /* ใช้ Gradient สีเขียวเข้มของจันทบุรี ทับภาพพื้นหลัง */
        background: linear-gradient(135deg, rgba(0, 77, 64, 0.9) 0%, rgba(0, 38, 33, 0.7) 100%), 
                    url('https://images.unsplash.com/photo-1596402184320-417d7178b2cd?q=80&w=1920');
        background-size: cover;
        background-position: center;
        background-attachment: fixed; /* ทำ Parallax ให้ดูหรู */
        color: white;
        text-align: center;
        overflow: hidden;
    }

    /* ตกแต่งตัวหนังสือให้ดูมีมิติ */
    .hero-premium h1 {
        font-size: 5rem;
        font-weight: 700;
        text-shadow: 0 10px 20px rgba(0,0,0,0.3);
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .hero-premium p {
        font-size: 1.5rem;
        font-weight: 300;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto 40px;
    }

    /* ตกแต่งปุ่มใหม่ให้ดู Modern */
    .btn-custom {
        padding: 15px 45px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
    }

    .btn-gold-pro {
        background: #ffc107;
        color: #002621;
        border: none;
        box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
    }

    .btn-gold-pro:hover {
        transform: translateY(-5px);
        background: #ffca28;
        box-shadow: 0 15px 35px rgba(255, 193, 7, 0.5);
    }

    .btn-outline-pro {
        border: 2px solid white;
        color: white;
        background: transparent;
    }

    .btn-outline-pro:hover {
        background: white;
        color: #004d40;
        transform: translateY(-5px);
    }
</style>

<div class="hero-premium">
    <div class="container animate__animated animate__fadeIn">
        <h1>จันทบุรี เมืองน่าอยู่</h1>
        <p>สัมผัสเสน่ห์ตะวันออก พลอยสวย ผลไม้เด่น รักษ์ประเพณี <br> มหัศจรรย์แห่งวิถีชุมชนที่งดงาม</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="product.php" class="btn btn-custom btn-gold-pro">ช้อปสินค้า OTOP</a>
            <a href="travel.php" class="btn btn-custom btn-gold-pro">สถานที่ท่องเที่ยว</a>
            <a href="tradition.php" class="btn btn-custom btn-gold-pro">ประเพณี</a>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="row text-center mb-5">
        <div class="col-12">
            <h2 class="fw-bold" style="color: #004d40; font-family: 'Kanit';">อัตลักษณ์ที่ภาคภูมิใจ</h2>
            <div class="mx-auto mt-2" style="width: 60px; height: 4px; background: #ffc107; border-radius: 2px;"></div>
        </div>
    </div>

    <div class="row g-4">
        <?php
        $items = [
            ["title" => "งานตากสินรำลึก", "icon" => "fa-monument", "desc" => "ระลึกถึงพระมหากรุณาธิคุณของสมเด็จพระเจ้าตากสินมหาราช"],
            ["title" => "วันผลไม้เมืองจันท์", "icon" => "fa-apple-whole", "desc" => "เทศกาลรวบรวมสุดยอดผลไม้เกรดพรีเมียมจากสวน"],
            ["title" => "ชักกะเย่อเกวียน", "icon" => "fa-people-group", "desc" => "ประเพณีสะท้อนความสามัคคีและวิถีเกษตรกรจันทบุรี"]
        ];
        foreach($items as $i): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4 text-center" style="border-radius: 25px; transition: 0.3s;">
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 80px; height: 80px; background: #f0f7f6; border-radius: 20px; color: #004d40;">
                        <i class="fas <?= $i['icon'] ?> fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="font-family: 'Kanit';"><?= $i['title'] ?></h4>
                    <p class="text-muted small"><?= $i['desc'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>