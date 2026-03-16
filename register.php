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
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ชื่อผู้ใช้ซ้ำ', text: 'ชื่อผู้ใช้งานนี้ถูกใช้งานไปแล้ว กรุณาเลือกชื่ออื่น', icon: 'warning', confirmButtonColor: '#004d40'}); }, 100);</script>";
    } else {
        $sql = "INSERT INTO users (username, password, fullname) VALUES ('$username', '$password', '$fullname')";

        if (mysqli_query($conn, $sql)) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สมัครสมาชิกสำเร็จ!', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'login.php'; }); }, 100);</script>";
        }
        else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถสมัครสมาชิกได้ กรุณาลองใหม่', icon: 'error', confirmButtonColor: '#004d40'}); }, 100);</script>";
        }
    }
}
?>

<style>
    .register-section {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        background: linear-gradient(135deg, #f8fffe 0%, #e8f5e9 50%, #f0f7f5 100%);
        position: relative;
        overflow: hidden;
    }

    .register-section::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(0,77,64,0.06) 0%, transparent 70%);
        top: -100px;
        left: -100px;
        border-radius: 50%;
    }

    .register-section::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,193,7,0.08) 0%, transparent 70%);
        bottom: -80px;
        right: -80px;
        border-radius: 50%;
    }

    .register-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 77, 64, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 440px;
        position: relative;
        z-index: 1;
        border: 1px solid rgba(0, 77, 64, 0.05);
    }

    .register-card-header {
        background: linear-gradient(135deg, #004d40, #00796b);
        padding: 35px 30px 30px;
        text-align: center;
        position: relative;
    }

    .register-card-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        right: 0;
        height: 40px;
        background: #fff;
        border-radius: 50% 50% 0 0;
    }

    .register-avatar {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        backdrop-filter: blur(10px);
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .register-avatar i {
        font-size: 2.2rem;
        color: #fff;
    }

    .register-card-header h2 {
        color: #fff;
        font-family: 'Kanit', sans-serif;
        font-weight: 700;
        font-size: 1.6rem;
        margin: 0;
    }

    .register-card-header p {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        margin: 5px 0 0;
    }

    .register-card-body {
        padding: 35px 35px 30px;
    }

    .form-floating-custom {
        position: relative;
        margin-bottom: 20px;
    }

    .form-floating-custom label {
        font-weight: 600;
        color: #004d40;
        font-size: 0.9rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-floating-custom label i {
        font-size: 0.85rem;
        color: #00796b;
    }

    .form-floating-custom .form-control {
        border: 2px solid #e8e8e8;
        border-radius: 14px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .form-floating-custom .form-control:focus {
        border-color: #004d40;
        box-shadow: 0 0 0 4px rgba(0, 77, 64, 0.08);
        background: #fff;
    }

    .form-floating-custom .input-group {
        border-radius: 14px;
        overflow: hidden;
    }

    .form-floating-custom .input-group .form-control {
        border-right: none;
        border-radius: 14px 0 0 14px;
    }

    .form-floating-custom .input-group .input-group-text {
        background: #fafafa;
        border: 2px solid #e8e8e8;
        border-left: none;
        border-radius: 0 14px 14px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #999;
    }

    .form-floating-custom .input-group:focus-within .form-control,
    .form-floating-custom .input-group:focus-within .input-group-text {
        border-color: #004d40;
    }

    .form-floating-custom .input-group:focus-within .form-control {
        box-shadow: none;
        background: #fff;
    }

    .form-floating-custom .input-group:focus-within .input-group-text {
        background: #fff;
        color: #004d40;
    }

    .form-floating-custom .input-group:focus-within {
        box-shadow: 0 0 0 4px rgba(0, 77, 64, 0.08);
        border-radius: 14px;
    }

    .btn-register {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #004d40, #00796b);
        color: #fff;
        border: none;
        border-radius: 14px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 5px;
    }

    .btn-register:hover {
        background: linear-gradient(135deg, #00332b, #004d40);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
    }

    .register-footer {
        text-align: center;
        padding: 0 35px 30px;
    }

    .register-divider {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
        color: #ccc;
        font-size: 0.85rem;
    }

    .register-divider::before,
    .register-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(to right, transparent, #e0e0e0, transparent);
    }

    .register-footer p {
        color: #888;
        font-size: 0.95rem;
        margin: 0;
    }

    .register-footer a {
        color: #004d40;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .register-footer a:hover {
        color: #00796b;
        text-decoration: underline;
    }
</style>

<section class="register-section">
    <div class="register-card">
        <div class="register-card-header">
            <div class="register-avatar">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>สมัครสมาชิก</h2>
            <p>สร้างบัญชีเพื่อเริ่มต้นช้อปปิ้ง</p>
        </div>

        <div class="register-card-body">
            <form method="POST">
                <div class="form-floating-custom">
                    <label for="fullname"><i class="fas fa-id-card"></i> ชื่อ-นามสกุล</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="กรอกชื่อ-นามสกุล" required>
                </div>

                <div class="form-floating-custom">
                    <label for="username"><i class="fas fa-user"></i> ชื่อผู้ใช้งาน (Username)</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="กรอกชื่อผู้ใช้งาน" required>
                </div>

                <div class="form-floating-custom">
                    <label for="password"><i class="fas fa-lock"></i> รหัสผ่าน</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="กรอกรหัสผ่าน" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" name="register" class="btn-register">
                    <i class="fas fa-user-plus"></i> สมัครสมาชิก
                </button>
            </form>
        </div>

        <div class="register-footer">
            <div class="register-divider">หรือ</div>
            <p>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </div>
    </div>
</section>

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