<?php
session_start();
session_destroy();
header("Location: index.php"); // ส่งกลับไปหน้าแรก
exit(); // ควรมี exit() เสมอหลังจาก header()
?>