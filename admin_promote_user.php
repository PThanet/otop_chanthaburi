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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_promote'])) {
    // ดึงข้อมูลผู้ใช้งานก่อน (เราต้องการ username, password, fullname มาใส่ tables admins)
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $username = $user['username'];
        $password = $user['password'];
        $fullname = $user['fullname'];
        
        $check_sql = "SELECT id FROM admins WHERE username = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo "<script>alert('ผู้ใช้นี้มีชื่ออยู่ในระบบแอดมินแล้ว หรือ Username ซ้ำ'); window.location='admin_dashboard.php#users-table';</script>";
        } else {
            // โอนย้ายข้อมูลไปตาราง admins
            $insert_sql = "INSERT INTO admins (username, password, fullname) VALUES (?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, "sss", $username, $password, $fullname);

            if (mysqli_stmt_execute($insert_stmt)) {
                // ถ้า insert สำเร็จ ให้ลบจากตาราง users
                $delete_sql = "DELETE FROM users WHERE id = ?";
                $delete_stmt = mysqli_prepare($conn, $delete_sql);
                mysqli_stmt_bind_param($delete_stmt, "i", $id);
                mysqli_stmt_execute($delete_stmt);

                echo "<script>alert('แต่งตั้งเป็นผู้ดูแลระบบเรียบร้อยแล้ว'); window.location='admin_dashboard.php#users-table';</script>";
                exit();
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการแต่งตั้ง');</script>";
            }
        }
    } else {
        echo "<script>alert('ไม่พบข้อมูลผู้ใช้งาน'); window.location='admin_dashboard.php#users-table';</script>";
        exit();
    }
}

// ดึงข้อมูลผู้ใช้งานเพื่อแสดง
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
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-level-up-alt me-2"></i>ยืนยันการแต่งตั้งผู้ดูแลระบบ</h4>
                </div>
                <div class="card-body p-4 bg-light text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-shield text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">คุณต้องการเลื่อนขั้นผู้ใช้คนนี้เป็น "ผู้ดูแลระบบ (Admin)" ใช่หรือไม่?</h5>
                    <div class="p-3 bg-white rounded shadow-sm border mb-4 text-start">
                        <p class="mb-1"><strong>ชื่อผู้ใช้งาน (Username):</strong> <span class="text-primary"><?= htmlspecialchars($user['username']); ?></span></p>
                        <p class="mb-0"><strong>ชื่อ-นามสกุล (Fullname):</strong> <?= htmlspecialchars($user['fullname']); ?></p>
                    </div>
                    
                    <form action="admin_promote_user.php?id=<?= $id ?>" method="POST">
                        <div class="d-flex justify-content-between mt-4">
                            <a href="admin_dashboard.php#users-table" class="btn btn-secondary px-4"><i class="fas fa-arrow-left me-2"></i>กลับ</a>
                            <button type="submit" name="confirm_promote" class="btn btn-success px-4"><i class="fas fa-check-circle me-2"></i>ยืนยันการแต่งตั้ง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>