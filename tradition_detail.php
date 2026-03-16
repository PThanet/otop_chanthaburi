<?php 
include('includes/header.php');
include('includes/db_config.php');

// ตรวจสอบว่ามี ID ของประเพณีที่ส่งมา
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-warning text-center my-5' role='alert'>";
    echo "<i class='fas fa-exclamation-triangle me-2'></i>ไม่พบข้อมูลประเพณี";
    echo "</div>";
    include('includes/footer.php');
    exit;
}

$tradition_id = intval($_GET['id']);

// ดึงข้อมูลประเพณี
$sql = "SELECT * FROM traditions WHERE id = $tradition_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "<div class='alert alert-danger text-center my-5' role='alert'>";
    echo "<i class='fas fa-exclamation-circle me-2'></i>ไม่พบข้อมูลประเพณีที่ต้องการ";
    echo "</div>";
    include('includes/footer.php');
    exit;
}

$tradition = mysqli_fetch_assoc($result);
?>

<style>
    .detail-header {
        background: linear-gradient(135deg, #1a7059 0%, #2a9a6f 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .tradition-detail-image {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        margin-bottom: 2rem;
        overflow: hidden;
        max-height: 500px;
        object-fit: cover;
    }

    .detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }

    .detail-card-header {
        background: linear-gradient(135deg, #1a7059 0%, #2a9a6f 100%);
        color: white;
        padding: 1.5rem;
        font-weight: bold;
        font-size: 1.25rem;
    }

    .detail-card-body {
        padding: 2rem;
    }

    .event-date-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: #333;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: bold;
        margin-bottom: 1rem;
        box-shadow: 0 3px 10px rgba(255, 195, 0, 0.3);
    }

    .location-badge {
        display: inline-block;
        background: linear-gradient(135deg, #0dcaf0 0%, #0aa8d4 100%);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: bold;
        margin-left: 0.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 3px 10px rgba(13, 202, 240, 0.3);
    }

    .description-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 2rem;
    }

    .event-details-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 5px solid #1a7059;
    }

    .back-button {
        display: inline-block;
        margin-bottom: 2rem;
    }

    .back-button a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        padding: 0.5rem 1.5rem;
        background: rgba(255,255,255,0.2);
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .back-button a:hover {
        background: rgba(255,255,255,0.3);
        transform: translateX(-5px);
    }
</style>

<div class="detail-header">
    <div class="container">
        <div class="back-button">
            <a href="tradition.php"><i class="fas fa-arrow-left me-2"></i>กลับไปหน้าประเพณี</a>
        </div>
        <h1 class="display-4 fw-bold mb-0">
            <i class="fas fa-torii-gate me-3"></i><?= htmlspecialchars($tradition['name']) ?>
        </h1>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <div class="col-lg-7">
            <?php if (!empty($tradition['image_url'])): ?>
                <img src="<?= htmlspecialchars($tradition['image_url']) ?>" 
                     alt="<?= htmlspecialchars($tradition['name']) ?>" 
                     class="tradition-detail-image w-100">
            <?php endif; ?>

            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="fas fa-align-left me-2"></i>รายละเอียด
                </div>
                <div class="detail-card-body">
                    <p class="description-text">
                        <?= nl2br(htmlspecialchars($tradition['description'])) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="fas fa-calendar-alt me-2"></i>วันที่จัดงาน
                </div>
                <div class="detail-card-body">
                    <?php if (!empty($tradition['event_date'])): ?>
                        <div class="event-date-badge">
                            <i class="fas fa-calendar me-2"></i><?= htmlspecialchars($tradition['event_date']) ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted"><i class="fas fa-info-circle me-2"></i>ยังไม่มีข้อมูลวันที่จัดงาน</p>
                    <?php endif; ?>
                    
                    <?php if (!empty($tradition['event_location'])): ?>
                        <div class="location-badge">
                            <i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($tradition['event_location']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tradition['event_details'])): ?>
                        <div class="event-details-section mt-3">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-info-circle me-2" style="color: #1a7059;"></i>ข้อมูลเพิ่มเติม
                            </h6>
                            <p class="mb-0">
                                <?= nl2br(htmlspecialchars($tradition['event_details'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Related Sections -->
            <div class="detail-card">
                <div class="detail-card-header" style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);">
                    <i class="fas fa-link me-2"></i>หัวข้ออื่น ๆ
                </div>
                <div class="detail-card-body text-center">
                    <a href="travel.php" class="btn btn-outline-primary mb-2 w-100">
                        <i class="fas fa-map me-2"></i>สถานที่ท่องเที่ยว
                    </a>
                    <a href="product.php" class="btn btn-outline-success mb-2 w-100">
                        <i class="fas fa-shopping-bag me-2"></i>สินค้า OTOP
                    </a>
                    <a href="tradition.php" class="btn btn-outline-warning w-100">
                        <i class="fas fa-torii-gate me-2"></i>ประเพณีอื่น ๆ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="height: 3rem;"></div>

<?php include('includes/footer.php'); ?>
