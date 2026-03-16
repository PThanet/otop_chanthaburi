<?php
include('includes/db_config.php');

// บันทึกแต่ละประเพณีต้องมีช่องสำหรับวันที่จัดงาน
// เพิ่มคอลัมน์สำหรับเก็บข้อมูลวันที่จัดงาน
$columns_to_add = [
    "ADD COLUMN `event_date` VARCHAR(50) DEFAULT NULL AFTER `description`",
    "ADD COLUMN `event_details` TEXT DEFAULT NULL AFTER `event_date`",
    "ADD COLUMN `event_location` VARCHAR(255) DEFAULT NULL AFTER `event_details`"
];

$success = true;

foreach ($columns_to_add as $alter_stmt) {
    $sql = "ALTER TABLE `traditions` $alter_stmt";
    if (mysqli_query($conn, $sql)) {
         echo "✓ Successfully executed: $alter_stmt<br>";
    } else {
         $error = mysqli_error($conn);
         if (strpos($error, 'Duplicate column name') !== false) {
             echo "✓ Column already exists, skipped: " . substr($alter_stmt, strpos($alter_stmt, 'ADD COLUMN') + 11, 20) . "<br>";
         } else {
             echo "✗ Error executing: $alter_stmt - $error<br>";
             $success = false;
         }
    }
}

if ($success) {
    echo "<br><strong style='color: green;'>✓ Database schema updated successfully for Tradition Event Dates!</strong>";
} else {
    echo "<br><strong style='color: red;'>✗ There were errors updating the database schema.</strong>";
}
?>
