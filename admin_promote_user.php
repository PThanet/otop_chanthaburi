<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['admin_username'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สิทธิ์การเข้าถึงถูกปฏิเสธ! เฉพาะผู้ดูแลระบบเท่านั้น', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'login_admin.php'; }); }, 100);</script>";
    exit();
}

include('includes/header.php');
include('includes/db_config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ข้อมูลไม่ถูกต้อง', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#users-table'; }); }, 100);</script>";
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
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ผู้ใช้นี้มีชื่ออยู่ในระบบแอดมินแล้ว หรือ Username ซ้ำ', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#users-table'; }); }, 100);</script>";
        } else {
            // รับค่า Role จาก Form (default: superadmin)
            $role = isset($_POST['admin_role']) ? $_POST['admin_role'] : 'superadmin';
            
            // โอนย้ายข้อมูลไปตาราง admins
            $insert_sql = "INSERT INTO admins (username, password, fullname, role) VALUES (?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, "ssss", $username, $password, $fullname, $role);

            if (mysqli_stmt_execute($insert_stmt)) {
                // ถ้า insert สำเร็จ ให้ลบจากตาราง users
                $delete_sql = "DELETE FROM users WHERE id = ?";
                $delete_stmt = mysqli_prepare($conn, $delete_sql);
                mysqli_stmt_bind_param($delete_stmt, "i", $id);
                mysqli_stmt_execute($delete_stmt);

                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'แต่งตั้งเป็นผู้ดูแลระบบเรียบร้อยแล้ว', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#users-table'; }); }, 100);</script>";
                exit();
            } else {
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เกิดข้อผิดพลาดในการแต่งตั้ง', icon: 'error', confirmButtonText: 'ตกลง'}); }, 100);</script>";
            }
        }
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ไม่พบข้อมูลผู้ใช้งาน', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#users-table'; }); }, 100);</script>";
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
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ไม่พบข้อมูลผู้ใช้งาน', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php#users-table'; }); }, 100);</script>";
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
                    
                    <form id="promoteForm" action="admin_promote_user.php?id=<?= $id ?>" method="POST">
                        <input type="hidden" name="confirm_promote" value="1">
                        <div class="mb-4 text-start">
                            <label for="admin_role" class="form-label fw-bold">เลือกตำแหน่งแอดมิน</label>
                            <select class="form-select form-select-lg" name="admin_role" id="admin_role" required>
                                <option value="superadmin">ผู้ดูแลระบบสูงสุด (Super Admin)</option>
                                <option value="admin_travel">แอดมินสถานที่ท่องเที่ยว (Travel Admin)</option>
                                <option value="admin_tradition">แอดมินงานประเพณี (Tradition Admin)</option>
                                <option value="admin_product">แอดมินสินค้า OTOP (Product Admin)</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="admin_dashboard.php#users-table" class="btn btn-secondary px-4"><i class="fas fa-arrow-left me-2"></i>ยกเลิก</a>
                            <button type="button" onclick="confirmPromotion(event)" class="btn btn-success px-4"><i class="fas fa-check-circle me-2"></i>ยืนยันการแต่งตั้ง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmPromotion(event) {
    event.preventDefault();
    var roleSelect = document.getElementById('admin_role');
    var roleText = roleSelect.options[roleSelect.selectedIndex].text;
    
    Swal.fire({
        title: 'ยืนยันการแต่งตั้ง?',
        html: "คุณต้องการแต่งตั้ง <strong>'<?= htmlspecialchars($user['fullname']); ?>'</strong><br>เป็น <strong>" + roleText + "</strong> ใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ยืนยัน!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('promoteForm').submit();
        }
    });
}
</script>

<?php include('includes/footer.php'); ?>