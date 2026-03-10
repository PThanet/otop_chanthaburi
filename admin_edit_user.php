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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script>alert('ข้อมูลไม่ถูกต้อง'); window.location='admin_dashboard.php#users-table';</script>";
    exit();
}

// เช็คการ Submit Form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);

    // ตรวจสอบว่า Username ซ้ำกับคนอื่นหรือไม่
    $check_sql = "SELECT id FROM users WHERE username = ? AND id != ?";
    $stmt_check = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt_check, "si", $username, $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "<script>alert('Username นี้มีการใช้งานในระบบแล้ว กรุณาใช้ชื่ออื่น');</script>";
    } else {
        $update_sql = "UPDATE users SET username=?, fullname=? WHERE id=?";
        $stmt_update = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt_update, "ssi", $username, $fullname, $id);

        if (mysqli_stmt_execute($stmt_update)) {
            echo "<script>alert('อัปเดตข้อมูลผู้ใช้งานเรียบร้อยแล้ว!'); window.location='admin_dashboard.php#users-table';</script>";
            exit();
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');</script>";
        }
    }
}

// ดึงข้อมูลผู้ใช้งานที่ต้องการแก้ไข
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
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>แก้ไขข้อมูลผู้ใช้งาน</h4>
                </div>
                <div class="card-body p-4 bg-light">
                    <form action="admin_edit_user.php?id=<?= $id ?>" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">ชื่อผู้ใช้งาน (Username)</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="<?= htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="fullname" class="form-label fw-bold">ชื่อ-นามสกุล (Fullname)</label>
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                value="<?= htmlspecialchars($user['fullname']); ?>" required>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="admin_dashboard.php#users-table" class="btn btn-secondary"><i
                                    class="fas fa-arrow-left me-2"></i>กลับ</a>
                            <button type="submit" name="update_user" class="btn btn-primary"><i
                                    class="fas fa-save me-2"></i>บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>