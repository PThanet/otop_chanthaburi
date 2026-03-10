<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['admin_username'])) {
    echo "<script>alert('สิทธิ์การเข้าถึงถูกปฏิเสธ! เฉพาะผู้ดูแลระบบเท่านั้น'); window.location='login_admin.php';</script>";
    exit();
}

include('includes/header.php');
include('includes/db_config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script>alert('ข้อมูลไม่ถูกต้อง'); window.location='admin_dashboard.php#users-table';</script>";
    exit();
}

// เช็คการ Submit Form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // ลบข้อมูลผู้ใช้จากตาราง users
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $id);

    if (mysqli_stmt_execute($delete_stmt)) {
        echo "<script>alert('ลบผู้ใช้งานเรียบร้อยแล้ว'); window.location='admin_dashboard.php#users-table';</script>";
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบผู้ใช้งาน');</script>";
    }
}

// ดึงข้อมูลผู้ใช้งานที่ต้องการลบ
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้งาน'); window.location='admin_dashboard.php#users-table';</script>";
    exit();
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-danger text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-times me-2"></i>ยืนยันการลบผู้ใช้งาน</h4>
                </div>
                <div class="card-body p-4 bg-light text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">คุณแน่ใจหรือไม่ที่จะลบบัญชีผู้ใช้นี้ออกจากระบบถาวร?</h5>
                    <div class="p-3 bg-white rounded shadow-sm border mb-4 text-start">
                        <p class="mb-1"><strong>ชื่อผู้ใช้งาน (Username):</strong> <span class="text-primary"><?= htmlspecialchars($user['username']); ?></span></p>
                        <p class="mb-0"><strong>ชื่อ-นามสกุล (Fullname):</strong> <?= htmlspecialchars($user['fullname']); ?></p>
                    </div>
                    
                    <form action="admin_delete_user.php?id=<?= $id ?>" method="POST">
                        <div class="d-flex justify-content-between mt-4">
                            <a href="admin_dashboard.php#users-table" class="btn btn-secondary px-4"><i class="fas fa-arrow-left me-2"></i>กลับ</a>
                            <button type="submit" name="confirm_delete" class="btn btn-danger px-4"><i class="fas fa-trash-alt me-2"></i>ยืนยันการลบทิ้ง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>