<?php 
include('includes/header.php'); 
include('includes/db_config.php'); // เพิ่มการเชื่อมต่อฐานข้อมูล
?>

<style>
    .travel-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: #fff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    
    .travel-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .travel-img-wrapper {
        position: relative;
        height: 280px;
        overflow: hidden;
    }
    
    .travel-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .travel-card:hover .travel-img-wrapper img {
        transform: scale(1.1);
    }
    
    .travel-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 25px;
        color: #fff;
        opacity: 0.9;
        transition: opacity 0.3s ease;
    }
    
    .location-tag {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 193, 7, 0.9);
        color: #000;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 0.8rem;
        backdrop-filter: blur(5px);
    }
</style>

<div class="container my-5 py-4">
    <div class="text-center mb-5 animate__animated animate__fadeIn">
        <h2 class="fw-bold text-primary-color display-5">แหล่งท่องเที่ยวห้ามพลาด</h2>
        <p class="lead text-muted">สัมผัสเสน่ห์ธรรมชาติอันงดงามและประวัติศาสตร์ที่น่าหลงใหลของจังหวัดจันทบุรี</p>
        <div class="mx-auto bg-warning" style="height: 4px; width: 80px; border-radius: 2px;"></div>
    </div>

    <div class="row g-4">
        <?php
        // ดึงข้อมูลสถานที่ท่องเที่ยวจากฐานข้อมูล
        $sql = "SELECT * FROM travel_places ORDER BY id ASC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($place = mysqli_fetch_assoc($result)): ?>
                <div class="col-lg-6">
                    <div class="travel-card shadow">
                        <div class="travel-img-wrapper">
                            <img src="<?= htmlspecialchars($place['image_url']) ?>" alt="<?= htmlspecialchars($place['name']) ?>">
                            <div class="location-tag"><?= htmlspecialchars($place['tag']) ?></div>
                            <div class="travel-overlay">
                                <h3 class="fw-bold mb-2"><?= htmlspecialchars($place['name']) ?></h3>
                                <p class="mb-0 fw-light" style="font-size: 0.95rem; line-height: 1.6;">
                                    <?= htmlspecialchars($place['description']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; 
        } else {
            echo "<div class='col-12 text-center text-muted'>ยังไม่มีข้อมูลสถานที่ท่องเที่ยว</div>";
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>