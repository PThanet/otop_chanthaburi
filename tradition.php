<?php include('includes/header.php'); ?>

<style>
    .tradition-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .tradition-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .tradition-icon-wrapper {
        height: 200px;
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .tradition-icon-wrapper i {
        font-size: 5rem;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="container my-5 py-4">
    <div class="text-center mb-5 animate__animated animate__fadeIn">
        <h2 class="fw-bold text-success display-5"><i class="fas fa-torii-gate me-3"></i>ประเพณีที่สำคัญ</h2>
        <p class="lead text-muted">ร่วมสืบสานและเรียนรู้วัฒนธรรมประเพณีอันดีงามของชาวจันทบุรี</p>
        <div class="mx-auto bg-warning" style="height: 4px; width: 80px; border-radius: 2px;"></div>
    </div>

    <div class="row g-4 justify-content-center">
        <?php
        include('includes/db_config.php');
        $sql = "SELECT * FROM traditions ORDER BY id ASC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="tradition-card text-center">
                        <div class="tradition-icon-wrapper">
                            <i class="fas <?= htmlspecialchars($item['icon']) ?>"></i>
                        </div>
                        <div class="p-4">
                            <h4 class="fw-bold mb-3">
                                <?= htmlspecialchars($item['name']) ?>
                            </h4>
                            <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                                <?= htmlspecialchars($item['description']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile;
        } else {
            echo "<div class='col-12 text-center text-muted'>ยังไม่มีข้อมูลงานประเพณี</div>";
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>