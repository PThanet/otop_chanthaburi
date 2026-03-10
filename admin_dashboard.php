<?php
// เริ่มต้น Session ก่อน
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบสิทธิ์การเข้าถึง: ถ้าไม่มี Session ของ admin_username ให้เด้งกลับไปหน้าล็อกอินแอดมิน
if (!isset($_SESSION['admin_username'])) {
    echo "<script>alert('สิทธิ์การเข้าถึงถูกปฏิเสธ! เฉพาะผู้ดูแลระบบเท่านั้น'); window.location='login_admin.php';</script>";
    exit();
}

include('includes/header.php');
include('includes/db_config.php');
?>

<div class="bg-dark text-white text-center py-4 mb-5 shadow-sm" style="border-bottom: 4px solid #dc3545;">
    <div class="container">
        <h1 class="fw-bold mb-0"><i class="fas fa-users-cog me-3"></i>ระบบจัดการข้อมูล (Admin Dashboard)</h1>
        <p class="mb-0 mt-2 text-white-50">ยินดีต้อนรับคุณ <?= htmlspecialchars($_SESSION['admin_fullname']); ?>
            (ผู้ดูแลระบบ)</p>
    </div>
</div>

<div class="container my-5">

    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-light" style="border-radius: 12px;">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center p-4">
                    <div class="mb-3 mb-md-0">
                        <h4 class="mb-1 fw-bold text-dark"><i class="fas fa-tools me-2"></i>จัดการเนื้อหาเว็บไซต์</h4>
                        <p class="text-muted mb-0 small">เมนูลัดสำหรับเพิ่ม ลบ หรือแก้ไขข้อมูลต่างๆ บนหน้าเว็บ</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="admin_travel.php" class="btn btn-success btn-lg fw-bold rounded-pill shadow-sm px-4">
                            <i class="fas fa-map-marked-alt me-2"></i>จัดการสถานที่ท่องเที่ยว
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0 mb-5" style="border-top: 4px solid #dc3545 !important; border-radius: 12px;">
        ...
    </div>
    <div class="card shadow-sm border-0 mb-5" style="border-top: 4px solid #dc3545 !important; border-radius: 12px;">
        <div class="card-header bg-white py-3">
            <h3 class="fw-bold text-danger mb-0"><i class="fas fa-user-shield me-2"></i>รายชื่อผู้ดูแลระบบ (Admins)
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-danger">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="30%">ชื่อผู้ใช้งาน (Username)</th>
                            <th width="40%">ตำแหน่ง (Position)</th>
                            <th width="20%">วันที่เป็นผู้ดูแล</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ดึงข้อมูลจากตาราง admins
                        $sql_admins = "SELECT * FROM admins ORDER BY id ASC";
                        $result_admins = mysqli_query($conn, $sql_admins);

                        if (mysqli_num_rows($result_admins) > 0) {
                            while ($row = mysqli_fetch_assoc($result_admins)) {
                                $created = isset($row['created_at']) ? $row['created_at'] : '-';
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td class='fw-bold'>{$row['username']}</td>";
                                echo "<td>{$row['fullname']}</td>";
                                echo "<td>{$created}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-muted py-3'>ไม่พบข้อมูลผู้ดูแลระบบ</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #0d6efd !important; border-radius: 12px;">
        <div class="card-header bg-white py-3">
            <h3 class="fw-bold text-primary mb-0"><i class="fas fa-users me-2"></i>รายชื่อผู้ใช้งานทั่วไป (Users)
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">ชื่อผู้ใช้งาน (Username)</th>
                            <th width="30%">ชื่อ-นามสกุล (Fullname)</th>
                            <th width="20%">วันที่สมัคร</th>
                            <th width="25%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ดึงข้อมูลจากตาราง users
                        $sql_users = "SELECT * FROM users ORDER BY id ASC";
                        $result_users = mysqli_query($conn, $sql_users);

                        if (mysqli_num_rows($result_users) > 0) {
                            while ($row = mysqli_fetch_assoc($result_users)) {
                                $created = isset($row['created_at']) ? $row['created_at'] : '-';
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td class='fw-bold text-primary'>{$row['username']}</td>";
                                echo "<td>{$row['fullname']}</td>";
                                echo "<td>{$created}</td>";
                                echo "<td>";
                                echo "<a href='admin_edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm mx-1'><i class='fas fa-edit'></i> แก้ไข</a>";
                                echo "<a href='admin_promote_user.php?id={$row['id']}' class='btn btn-success btn-sm mx-1' onclick=\"return confirm('คุณแน่ใจหรือไม่ว่าต้องการแต่งตั้ง [{$row['username']}] เป็นผู้ดูแลระบบ?');\"><i class='fas fa-level-up-alt'></i> เลื่อนขั้น</a>";
                                echo "<a href='admin_delete_user.php?id={$row['id']}' class='btn btn-danger btn-sm mx-1' onclick=\"return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้ [{$row['username']}] นี้ออกจากระบบถาวร?');\"><i class='fas fa-trash-alt'></i> ลบ</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-muted py-3'>ไม่พบข้อมูลผู้ใช้งาน</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>