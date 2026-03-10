<?php
include('includes/db_config.php');

// เปลี่ยนชื่อ Column icon เป็น image_url ในตาราง traditions
$sql_alter = "ALTER TABLE `traditions` CHANGE `icon` `image_url` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL";
if (mysqli_query($conn, $sql_alter)) {
    echo "Database altered successfully.<br>";
} else {
    echo "Error altering database: " . mysqli_error($conn) . "<br>";
}

// สร้างโฟลเดอร์ uploads
$folders = ['uploads', 'uploads/travel', 'uploads/traditions', 'uploads/otop'];
foreach ($folders as $folder) {
    if (!file_exists($folder)) {
        if (mkdir($folder, 0777, true)) {
            echo "Created directory: $folder<br>";
        } else {
            echo "Failed to create directory: $folder<br>";
        }
    } else {
        echo "Directory already exists: $folder<br>";
    }
}
?>
