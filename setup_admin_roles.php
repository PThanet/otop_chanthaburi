<?php
include('includes/db_config.php');

$columns_to_add = [
    "ADD COLUMN `role` VARCHAR(50) DEFAULT 'superadmin' AFTER `fullname`"
];

$success = true;

foreach ($columns_to_add as $alter_stmt) {
    $sql = "ALTER TABLE `admins` $alter_stmt";
    if (mysqli_query($conn, $sql)) {
         echo "Successfully executed: $alter_stmt<br>";
    } else {
         $error = mysqli_error($conn);
         if (strpos($error, 'Duplicate column name') !== false) {
             echo "Column already exists, skipped: $alter_stmt<br>";
         } else {
             echo "Error executing: $alter_stmt - $error<br>";
             $success = false;
         }
    }
}

if ($success) {
    echo "Database schema updated successfully for Sub-Admin Roles!";
} else {
    echo "There were errors updating the database schema.";
}
?>
