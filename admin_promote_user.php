<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['admin_username'])) {
    echo "<script>alert('สิทธิ์การเข้าถึงถูกปฏิเสธ! เฉพาะผู้ดูแลระบบเท่านั้น'); window.location='login_admin.php';</script>";
    exit();
}

include('includes/db_config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

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

        // ตรวจสอบว่ามี username นี้ในตาราง admins หรือยัง
        $check_sql = "SELECT id FROM admins WHERE username = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo "<script>alert('ผู้ใช้นี้มีชื่ออยู่ในระบบแอดมินแล้ว หรือ Username ซ้ำ'); window.location='admin_dashboard.php';</script>";
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

                echo "<script>alert('แต่งตั้งเป็นผู้ดูแลระบบเรียบร้อยแล้ว'); window.location='admin_dashboard.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการแต่งตั้ง'); window.location='admin_dashboard.php';</script>";
            }
        }
    } else {
        echo "<script>alert('ไม่พบผู้ใช้งานที่ต้องการ'); window.location='admin_dashboard.php';</script>";
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>