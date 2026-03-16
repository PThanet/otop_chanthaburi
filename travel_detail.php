<?php 
include('includes/header.php');
include('includes/db_config.php');

// ตรวจสอบว่ามี ID ของสถานที่ที่ส่งมา
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-warning text-center my-5' role='alert'>";
    echo "<i class='fas fa-exclamation-triangle me-2'></i>ไม่พบข้อมูลสถานที่ท่องเที่ยว";
    echo "</div>";
    include('includes/footer.php');
    exit;
}

$travel_id = intval($_GET['id']);

// ดึงข้อมูลสถานที่
$sql = "SELECT * FROM travel_places WHERE id = $travel_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "<div class='alert alert-danger text-center my-5' role='alert'>";
    echo "<i class='fas fa-exclamation-circle me-2'></i>ไม่พบข้อมูลสถานที่ที่ต้องการ";
    echo "</div>";
    include('includes/footer.php');
    exit;
}

$travel = mysqli_fetch_assoc($result);

// เก็บรูปภาพทั้งหมด
$images = [];
if (!empty($travel['image_url'])) $images[] = $travel['image_url'];
if (!empty($travel['image_url_2'])) $images[] = $travel['image_url_2'];
if (!empty($travel['image_url_3'])) $images[] = $travel['image_url_3'];
if (!empty($travel['image_url_4'])) $images[] = $travel['image_url_4'];
?>

<style>
    .detail-header {
        background: linear-gradient(135deg, #008B8B 0%, #20B2AA 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
        background: linear-gradient(135deg, #008B8B 0%, #20B2AA 100%);
        color: white;
        padding: 1.5rem;
        font-weight: bold;
        font-size: 1.25rem;
    }

    .detail-card-body {
        padding: 2rem;
    }

    .back-button a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        padding: 0.5rem 1.5rem;
        background: rgba(255,255,255,0.2);
        border-radius: 50px;
        transition: all 0.3s ease;
        display: inline-block;
        margin-bottom: 2rem;
    }

    .back-button a:hover {
        background: rgba(255,255,255,0.3);
        transform: translateX(-5px);
    }

    /* Main Image Gallery */
    .main-gallery {
        position: relative;
        background: #000;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        display: block;
    }

    .gallery-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }

    .gallery-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .gallery-dot.active {
        background: white;
        transform: scale(1.2);
    }

    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.3s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-nav:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    .gallery-nav.prev {
        left: 20px;
    }

    .gallery-nav.next {
        right: 20px;
    }

    /* Thumbnail Gallery */
    .thumbnail-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 1rem;
    }

    .thumbnail {
        width: 100%;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        object-fit: cover;
    }

    .thumbnail:hover {
        border-color: #20B2AA;
        transform: scale(1.05);
    }

    .thumbnail.active {
        border-color: #008B8B;
        box-shadow: 0 5px 15px rgba(0, 139, 139, 0.3);
    }

    .description-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 2rem;
    }

    .tag-badge {
        display: inline-block;
        background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%);
        color: #333;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: bold;
        margin-bottom: 1rem;
        box-shadow: 0 3px 10px rgba(255, 195, 0, 0.3);
    }

    .info-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 5px solid #008B8B;
        margin-top: 1.5rem;
    }

    .image-count {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        backdrop-filter: blur(5px);
    }
</style>

<div class="detail-header">
    <div class="container">
        <div class="back-button">
            <a href="travel.php"><i class="fas fa-arrow-left me-2"></i>กลับไปหน้าท่องเที่ยว</a>
        </div>
        <h1 class="display-4 fw-bold mb-0">
            <i class="fas fa-map-marker-alt me-3"></i><?= htmlspecialchars($travel['name']) ?>
        </h1>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Main Gallery -->
            <div class="main-gallery" id="mainGallery">
                <img id="mainImage" src="<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($travel['name']) ?>" class="main-image">
                <div class="image-count">
                    <i class="fas fa-images me-2"></i><span id="imageCounter">1</span> / <?= count($images) ?>
                </div>
                <?php if (count($images) > 1): ?>
                    <button class="gallery-nav prev" onclick="changeImage(-1)"><i class="fas fa-chevron-left"></i></button>
                    <button class="gallery-nav next" onclick="changeImage(1)"><i class="fas fa-chevron-right"></i></button>
                    <div class="gallery-controls" id="galleryDots"></div>
                <?php endif; ?>
            </div>

            <!-- Thumbnails -->
            <?php if (count($images) > 1): ?>
                <div class="thumbnail-gallery" id="thumbnailGallery">
                    <?php foreach ($images as $index => $image): ?>
                        <img src="<?= htmlspecialchars($image) ?>" alt="Thumbnail <?= $index + 1 ?>" class="thumbnail <?= $index === 0 ? 'active' : '' ?>" onclick="selectImage(<?= $index ?>)">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Description -->
            <div class="detail-card" style="margin-top: 2rem;">
                <div class="detail-card-header">
                    <i class="fas fa-info-circle me-2"></i>รายละเอียด
                </div>
                <div class="detail-card-body">
                    <p class="description-text">
                        <?= nl2br(htmlspecialchars($travel['description'])) ?>
                    </p>
                    <div class="tag-badge">
                        <i class="fas fa-tag me-2"></i><?= htmlspecialchars($travel['tag']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Info -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="fas fa-compass me-2"></i>ข้อมูลสำคัญ
                </div>
                <div class="detail-card-body">
                    <div class="info-section">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-images me-2" style="color: #008B8B;"></i>จำนวนรูปภาพ
                        </h6>
                        <p class="mb-0">
                            <span class="badge bg-primary"><?= count($images) ?> รูป</span>
                        </p>
                    </div>

                    <div class="info-section">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-folder-open me-2" style="color: #008B8B;"></i>หมวดหมู่
                        </h6>
                        <p class="mb-0">
                            <span class="badge bg-success"><?= htmlspecialchars($travel['tag']) ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Related Sections -->
            <div class="detail-card">
                <div class="detail-card-header" style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);">
                    <i class="fas fa-link me-2"></i>หัวข้ออื่น ๆ
                </div>
                <div class="detail-card-body text-center">
                    <a href="tradition.php" class="btn btn-outline-primary mb-2 w-100">
                        <i class="fas fa-torii-gate me-2"></i>ประเพณี
                    </a>
                    <a href="product.php" class="btn btn-outline-success mb-2 w-100">
                        <i class="fas fa-shopping-bag me-2"></i>สินค้า OTOP
                    </a>
                    <a href="travel.php" class="btn btn-outline-info w-100">
                        <i class="fas fa-map-marker-alt me-2"></i>สถานที่อื่น ๆ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="height: 3rem;"></div>

<script>
    let currentImageIndex = 0;
    const totalImages = <?= count($images) ?>;
    const images = <?= json_encode($images) ?>;

    function initGallery() {
        // สร้าง dots สำหรับ gallery
        const dotsContainer = document.getElementById('galleryDots');
        if (dotsContainer) {
            for (let i = 0; i < totalImages; i++) {
                const dot = document.createElement('div');
                dot.className = 'gallery-dot' + (i === 0 ? ' active' : '');
                dot.onclick = () => selectImage(i);
                dotsContainer.appendChild(dot);
            }
        }
    }

    function selectImage(index) {
        currentImageIndex = index;
        updateGallery();
    }

    function changeImage(direction) {
        currentImageIndex = (currentImageIndex + direction + totalImages) % totalImages;
        updateGallery();
    }

    function updateGallery() {
        // อัปเดต main image
        document.getElementById('mainImage').src = images[currentImageIndex];
        document.getElementById('imageCounter').textContent = currentImageIndex + 1;

        // อัปเดต thumbnails
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach((thumb, index) => {
            thumb.classList.toggle('active', index === currentImageIndex);
        });

        // อัปเดต dots
        const dots = document.querySelectorAll('.gallery-dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentImageIndex);
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (totalImages > 1) {
            if (e.key === 'ArrowLeft') changeImage(-1);
            if (e.key === 'ArrowRight') changeImage(1);
        }
    });

    // Initialize on page load
    window.addEventListener('load', initGallery);
</script>

<?php include('includes/footer.php'); ?>
