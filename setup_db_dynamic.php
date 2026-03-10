<?php
include('includes/db_config.php');

// 1. สร้างตาราง traditions
$sql_traditions = "CREATE TABLE IF NOT EXISTS `traditions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if(mysqli_query($conn, $sql_traditions)) {
    echo "สร้างตาราง traditions สำเร็จ<br>";
} else {
    echo "เกิดข้อผิดพลาดในการสร้างตาราง traditions: " . mysqli_error($conn) . "<br>";
}

// 2. สร้างตาราง otop_products
$sql_products = "CREATE TABLE IF NOT EXISTS `otop_products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` varchar(50) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `tag_color` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if(mysqli_query($conn, $sql_products)) {
    echo "สร้างตาราง otop_products สำเร็จ<br>";
} else {
    echo "เกิดข้อผิดพลาดในการสร้างตาราง otop_products: " . mysqli_error($conn) . "<br>";
}

// 3. ตรวจสอบว่าตารางว่างอยู่หรือไม่ ก่อน Insert ข้อมูลตั้งต้น
// ข้อมูลตั้งต้นของ Traditions
$result_trad = mysqli_query($conn, "SELECT COUNT(*) as count FROM traditions");
$row_trad = mysqli_fetch_assoc($result_trad);

if ($row_trad['count'] == 0) {
    $insert_trad = "INSERT INTO `traditions` (`name`, `description`, `icon`) VALUES
    ('งานตากสินรำลึก', 'งานประจำปีเพื่อระลึกถึงพระมหากรุณาธิคุณของสมเด็จพระเจ้าตากสินมหาราชในการกอบกู้เอกราช มีการออกร้านและมหรสพมากมาย', 'fa-monument'),
    ('งานของดีเมืองจันท์วันผลไม้', 'เทศกาลรวบรวมสุดยอดผลไม้เกรดพรีเมียมจากสวนทั่วจันทบุรี ทั้งทุเรียน มังคุด เงาะ และสินค้าเกษตรคุณภาพสูง', 'fa-apple-whole'),
    ('ประเพณีชักเย่อเกวียนพระบาท', 'ประเพณีท้องถิ่นที่สะท้อนถึงความสามัคคีและวิถีชีวิตของเกษตรกรชาวจันทบุรี มีการแข่งขันที่สนุกสนานและเป็นเอกลักษณ์', 'fa-people-carry-box')";
    
    if(mysqli_query($conn, $insert_trad)) {
         echo "เพิ่มข้อมูลตั้งต้นใน traditions สำเร็จ<br>";
    }
}

// ข้อมูลตั้งต้นของ OTOP Products
$result_prod = mysqli_query($conn, "SELECT COUNT(*) as count FROM otop_products");
$row_prod = mysqli_fetch_assoc($result_prod);

if ($row_prod['count'] == 0) {
    $insert_prod = "INSERT INTO `otop_products` (`name`, `price`, `image_url`, `tag`, `tag_color`) VALUES
    ('สัปปะรดกรอบ', '150', 'otop/CrispyPineapple2.png', 'ขายดี', 'bg-danger'),
    ('น้ำพริกเผาลำไย', '89', 'otop/ChiliSauce.jpg', 'แนะนำ', 'bg-warning text-dark'),
    ('เสื่อจันทบูร', '450', 'otop/Mat.jpg', 'Handmade', 'bg-success')";
    
    if(mysqli_query($conn, $insert_prod)) {
         echo "เพิ่มข้อมูลตั้งต้นใน otop_products สำเร็จ<br>";
    }
}

echo "<br><strong>การดำเนินการเสร็จสิ้น!</strong> คุณสามารถลบไฟล์นี้ทิ้งได้เลย";
?>
