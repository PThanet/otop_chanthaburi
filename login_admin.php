<?php
session_start();
include('includes/header.php');
include('includes/db_config.php');

if (isset($_POST['login_admin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // สำหรับ Admin อาจจะต้องตรวจสอบสิทธิ์เพิ่มเติม เช่น role = 'admin' ในฐานข้อมูล
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // หากมีตาราง admin แยกหรือมีการเช็ค role ให้เพิ่มตรงนี้
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_fullname'] = $user['fullname'];
        
        // ตรวจสอบและดึงข้อมูล role, ปกติ admin จะได้ superadmin
        $_SESSION['admin_role'] = isset($user['role']) ? $user['role'] : 'superadmin';

        echo "<script>alert('ยินดีต้อนรับผู้ดูแลระบบ!'); window.location='admin_dashboard.php';</script>";
    }
    else {
        echo "<div class='alert alert-danger text-center mt-3 shadow-sm'>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง หรือคุณไม่มีสิทธิ์เข้าถึง</div>";
    }
}
?>

<!-- หัวข้อเพิ่มด้านบนที่ต่างกัน (Additional header for Admin) -->
<div class="bg-dark text-white text-center py-4 mb-4 shadow-sm" style="border-bottom: 4px solid #dc3545;">
    <div class="container">
        <h1 class="fw-bold mb-0"><i class="fas fa-user-shield me-3"></i>ระบบจัดการผู้ดูแลระบบ (Admin Panel)</h1>
        <p class="mb-0 mt-2 text-white-50">กรุณาเข้าสู่ระบบเพื่อจัดการข้อมูลเว็บไซต์พื้นที่เฉพาะเจ้าหน้าที่เท่านั้น</p>
    </div>
</div>

<div class="container my-5 py-3" style="max-width: 500px;">
    <div class="card shadow-lg border-0 p-4 card-hover-scale"
        style="border-top: 5px solid #dc3545 !important; border-radius: 12px;">
        <h2 class="text-center mb-4 text-dark fw-bold">เข้าสู่ระบบ Admin</h2>

        <!-- Professional looking alert/info -->
        <div class="alert alert-secondary text-center py-2 mb-4 border-0 bg-light rounded-pill"
            style="font-size: 0.9rem;">
            <i class="fas fa-lock me-1 text-danger"></i> พื้นที่ปลอดภัยสำหรับผู้ดูแลระบบ
        </div>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label fw-bold text-muted small text-uppercase">ชื่อผู้ใช้งาน</label>
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" id="username" class="form-control border-start-0 ps-0"
                        placeholder="กรอกชื่อผู้ใช้งาน..." required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-bold text-muted small text-uppercase">รหัสผ่าน</label>
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-key text-muted"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0 border-end-0 ps-0"
                        placeholder="กรอกรหัสผ่าน..." required>
                    <span class="input-group-text bg-white border-start-0" id="togglePassword" style="cursor: pointer;">
                        <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                    </span>
                </div>
            </div>
            <button type="submit" name="login_admin"
                class="btn btn-danger w-100 fw-bold rounded-pill btn-hover-shadow py-3 text-uppercase mt-2"
                style="letter-spacing: 1px;">
                <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบจัดการ
            </button>
        </form>

        <hr class="my-4 text-muted">

        <div class="text-center">
            <a href="index.php"
                class="text-muted text-decoration-none btn btn-light rounded-pill px-4 py-2 border shadow-sm btn-hover-shadow">
                <i class="fas fa-arrow-left me-2"></i> กลับสู่หน้าเว็บปกติ
            </a>
        </div>
    </div>
</div>

<style>
    /* CSS styles for admin login */
    .card-hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 1.5rem 3rem rgba(0, 0, 0, .15) !important;
    }

    .btn-hover-shadow {
        transition: all 0.3s ease;
    }

    .btn-hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .input-group-text {
        border-right: none;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #dee2e6;
        background-color: #fff !important;
    }

    /* Enhance input field focus state */
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        border-radius: 0.5rem;
    }

    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
        border-color: #dc3545;
    }
</style>

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