<?php
include('includes/db_config.php');

$columns_to_add = [
    "ADD COLUMN `description` TEXT DEFAULT NULL AFTER `price`",
    "ADD COLUMN `image_url_2` VARCHAR(255) DEFAULT NULL AFTER `image_url`",
    "ADD COLUMN `image_url_3` VARCHAR(255) DEFAULT NULL AFTER `image_url_2`",
    "ADD COLUMN `image_url_4` VARCHAR(255) DEFAULT NULL AFTER `image_url_3`"
];

$success = true;

foreach ($columns_to_add as $alter_stmt) {
    $sql = "ALTER TABLE `otop_products` $alter_stmt";
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
    echo "Database schema updated successfully for Order Page Customization!";
} else {
    echo "There were errors updating the database schema.";
}
?>
