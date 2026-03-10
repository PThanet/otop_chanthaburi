<?php
// ไฟล์: includes/db_config.php
$conn = mysqli_connect("localhost", "root", "1234", "otop_chanthaburi");

if (!$conn) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>