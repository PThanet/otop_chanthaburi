<?php

include('includes/header.php');

include('includes/db_config.php');

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);

    // ตรวจสอบว่า username ซ้ำหรือไม่
    $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    $check_admin = mysqli_query($conn, "SELECT id FROM admins WHERE username = '$username'");

    if (mysqli_num_rows($check_user) > 0 || mysqli_num_rows($check_admin) > 0) {
        echo "<div class='alert alert-warning text-center mt-3 shadow-sm'>ชื่อผู้ใช้งานนี้ถูกใช้งานไปแล้ว กรุณาเลือกชื่ออื่น</div>";
    } else {
        $sql = "INSERT INTO users (username, password, fullname) VALUES ('$username', '$password', '$fullname')";

        if (mysqli_query($conn, $sql)) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สมัครสมาชิกสำเร็จ!', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'login.php'; }); }, 100);</script>";
        }
        else {
            echo "<div class='alert alert-danger text-center mt-3 shadow-sm'>เกิดข้อผิดพลาดในการสมัครสมาชิก: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<div class="container my-5 py-3" style="max-width: 500px;">
    <div class="card shadow-lg border-0 p-4 card-hover-scale">
        <h2 class="text-center mb-4 text-primary-color fw-bold">สมัครสมาชิก</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="fullname" class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" id="fullname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100 fw-bold rounded-pill btn-hover-shadow">
                <i class="fas fa-user-plus me-2"></i>สมัครสมาชิก
            </button>
        </form>
        <p class="mt-4 text-center">มีบัญชีแล้ว? <a href="login.php" class="text-decoration-none fw-bold">เข้าสู่ระบบ</a></p>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>

<?php include('includes/footer.php'); ?>