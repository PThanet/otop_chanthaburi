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

    // ลบข้อมูลผู้ใช้จากตาราง users
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $id);

    if (mysqli_stmt_execute($delete_stmt)) {
        echo "<script>alert('ลบผู้ใช้งานเรียบร้อยแล้ว'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบผู้ใช้งาน'); window.location='admin_dashboard.php';</script>";
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>