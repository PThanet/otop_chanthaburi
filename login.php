<?php

session_start();
include('includes/header.php');

include('includes/db_config.php');

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ยินดีต้อนรับ!', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'index.php'; }); }, 100);</script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เข้าสู่ระบบไม่สำเร็จ', text: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', icon: 'error', confirmButtonColor: '#004d40'}); }, 100);</script>";
    }
}
?>

<style>
    .login-section {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        background: linear-gradient(135deg, #f8fffe 0%, #e8f5e9 50%, #f0f7f5 100%);
        position: relative;
        overflow: hidden;
    }

    .login-section::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(0, 77, 64, 0.06) 0%, transparent 70%);
        top: -100px;
        right: -100px;
        border-radius: 50%;
    }

    .login-section::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 193, 7, 0.08) 0%, transparent 70%);
        bottom: -80px;
        left: -80px;
        border-radius: 50%;
    }

    .login-card {
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

    .login-card-header {
        background: linear-gradient(135deg, #004d40, #00796b);
        padding: 35px 30px 30px;
        text-align: center;
        position: relative;
    }

    .login-card-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        right: 0;
        height: 40px;
        background: #fff;
        border-radius: 50% 50% 0 0;
    }

    .login-avatar {
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

    .login-avatar i {
        font-size: 2.2rem;
        color: #fff;
    }

    .login-card-header h2 {
        color: #fff;
        font-family: 'Kanit', sans-serif;
        font-weight: 700;
        font-size: 1.6rem;
        margin: 0;
    }

    .login-card-header p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        margin: 5px 0 0;
    }

    .login-card-body {
        padding: 35px 35px 30px;
    }

    .form-floating-custom {
        position: relative;
        margin-bottom: 22px;
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

    .btn-login {
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

    .btn-login:hover {
        background: linear-gradient(135deg, #00332b, #004d40);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 77, 64, 0.3);
    }

    .login-footer {
        text-align: center;
        padding: 0 35px 30px;
    }

    .login-divider {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
        color: #ccc;
        font-size: 0.85rem;
    }

    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(to right, transparent, #e0e0e0, transparent);
    }

    .login-footer p {
        color: #888;
        font-size: 0.95rem;
        margin: 0;
    }

    .login-footer a {
        color: #004d40;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .login-footer a:hover {
        color: #00796b;
        text-decoration: underline;
    }

    .admin-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #999;
        font-size: 0.85rem;
        text-decoration: none;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .admin-link:hover {
        color: #004d40;
    }
</style>

<section class="login-section">
    <div class="login-card">
        <div class="login-card-header">
            <div class="login-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2>เข้าสู่ระบบ</h2>
            <p>ยินดีต้อนรับกลับมา!</p>
        </div>

        <div class="login-card-body">
            <form method="POST">
                <div class="form-floating-custom">
                    <label for="username"><i class="fas fa-user"></i> ชื่อผู้ใช้งาน</label>
                    <input type="text" name="username" id="username" class="form-control"
                        placeholder="กรอกชื่อผู้ใช้งาน" required>
                </div>

                <div class="form-floating-custom">
                    <label for="password"><i class="fas fa-lock"></i> รหัสผ่าน</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="กรอกรหัสผ่าน" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ
                </button>
            </form>
        </div>

        <div class="login-footer">
            <div class="login-divider">หรือ</div>
            <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>

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