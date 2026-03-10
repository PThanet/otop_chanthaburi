<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เช็คสิทธิ์ Admin: เฉพาะ superadmin เท่านั้นที่เข้ามาหน้านี้ได้
if (!isset($_SESSION['admin_username']) || !isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'superadmin') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สิทธิ์การเข้าถึงถูกปฏิเสธ! เฉพาะผู้ดูแลระบบสูงสุด (Super Admin) เท่านั้น', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php'; }); }, 100);</script>";
    exit();
}

include('includes/header.php');
include('includes/db_config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ข้อมูลไม่ถูกต้อง', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#admins-table'; }); }, 100);</script>";
    exit();
}

// 1. ตรวจสอบว่าแอดมินเป้าหมายมีอยู่จริงและไม่ใช่ superadmin
$sql_check = "SELECT * FROM admins WHERE id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$result = mysqli_stmt_get_result($stmt_check);
$target_admin = mysqli_fetch_assoc($result);

if (!$target_admin) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ไม่พบข้อมูลแอดมิน', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#admins-table'; }); }, 100);</script>";
    exit();
}

// ห้ามลบ/ลดขั้น superadmin (รวมถึงตัวเอง)
if ($target_admin['role'] === 'superadmin') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ไม่สามารถลดขั้นผู้ดูแลระบบสูงสุด (Super Admin) ได้', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#admins-table'; }); }, 100);</script>";
    exit();
}

// 2. จัดการเมื่อกดยืนยันการลดขั้น
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_demote'])) {
    
    $username = $target_admin['username'];
    $password = $target_admin['password'];
    $fullname = $target_admin['fullname'];

    // 2.1 เพิ่มกลับไปในตาราง users
    $insert_sql = "INSERT INTO users (username, password, fullname) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "sss", $username, $password, $fullname);

    if (mysqli_stmt_execute($insert_stmt)) {
        // 2.2 ลบออกจากตาราง admins
        $delete_sql = "DELETE FROM admins WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        mysqli_stmt_execute($delete_stmt);

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ลดขั้นกลับไปเป็นผู้ใช้งานทั่วไปสำเร็จ', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#users-table'; }); }, 100);</script>";
        exit();
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เกิดข้อผิดพลาดในการย้ายข้อมูลกลับไปเป็น user', icon: 'error', confirmButtonText: 'ตกลง'}); }, 100);</script>";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-warning text-dark py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-level-down-alt me-2"></i>ยืนยันการลดขั้นแอดมิน</h4>
                </div>
                <div class="card-body p-4 bg-light text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-times text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">คุณต้องการลดขั้นแอดมินคนนี้กลับไปเป็น "ผู้ใช้งานทั่วไป" ใช่หรือไม่?</h5>
                    <div class="p-3 bg-white rounded shadow-sm border mb-4 text-start">
                        <p class="mb-1"><strong>ชื่อผู้ใช้งาน (Username):</strong> <span class="text-primary"><?= htmlspecialchars($target_admin['username']); ?></span></p>
                        <p class="mb-1"><strong>ชื่อ-นามสกุล (Fullname):</strong> <?= htmlspecialchars($target_admin['fullname']); ?></p>
                        <p class="mb-0"><strong>ตำแหน่งปัจจุบัน:</strong> <span class="badge bg-secondary"><?= htmlspecialchars($target_admin['role']); ?></span></p>
                    </div>
                    
                    <form action="admin_demote_user.php?id=<?= $id ?>" method="POST">
                        <div class="d-flex justify-content-between mt-4">
                            <a href="admin_dashboard.php#admins-table" class="btn btn-secondary px-4"><i class="fas fa-arrow-left me-2"></i>ยกเลิก</a>
                            <button type="submit" name="confirm_demote" class="btn btn-warning px-4 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>ยืนยันการลดขั้น</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
