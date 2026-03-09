<?php 
session_start();
include('includes/header.php'); 
include('includes/db_config.php');

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        echo "<script>alert('ยินดีต้อนรับ!'); window.location='index.php';</script>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</div>";
    }
}
?>

<div class="container my-5 py-3" style="max-width: 500px;">
    <div class="card shadow-lg border-0 p-4 card-hover-scale">
        <h2 class="text-center mb-4 text-primary-color fw-bold">เข้าสู่ระบบ</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 fw-bold rounded-pill btn-hover-shadow">
                <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบ
            </button>
        </form>
        <p class="mt-4 text-center">ยังไม่มีบัญชี? <a href="register.php" class="text-decoration-none fw-bold">สมัครที่นี่</a></p>
    </div>
</div>

<?php include('includes/footer.php'); ?>