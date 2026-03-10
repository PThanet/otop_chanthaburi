<?php
// ไฟล์: setup_sample_data.php
// สร้างข้อมูลตัวอย่างสำหรับระบบ

include 'includes/db_config.php';

// ข้อมูลสินค้าตัวอย่าง
$sample_products = [
    [
        'name' => 'ลูกอม OTOP จันทบุรี',
        'price' => 150,
        'description' => 'ลูกอมสูตรดั้งเดิมจากจันทบุรี ใช้วัตถุดิบคุณภาพดี หวานอมเล็กน้อย ห่อสวย',
        'image_url' => 'uploads/otop/candy.jpg',
        'tag' => 'ขายดี',
        'tag_color' => 'badge-success'
    ],
    [
        'name' => 'น้ำพริกแบบแห้ง',
        'price' => 200,
        'description' => 'น้ำพริกแบบแห้ง รสชาติจัดจ้าน ทำจากพริกตากแห้งที่คัดสรร',
        'image_url' => 'uploads/otop/chili.jpg',
        'tag' => 'ใหม่',
        'tag_color' => 'badge-info'
    ],
    [
        'name' => 'เม็ดมะม่วง',
        'price' => 180,
        'description' => 'เม็ดมะม่วงเคี่ยว สุก หวานธรรมชาติ ไม่มีสารเติมแต่ง',
        'image_url' => 'uploads/otop/mango.jpg',
        'tag' => 'ขายดี',
        'tag_color' => 'badge-warning'
    ],
    [
        'name' => 'ขนมไทยสายไหม',
        'price' => 250,
        'description' => 'ขนมไทยสายไหม สูตรดั้งเดิม ทำจากไข่ไก่สด และน้ำตาลอ้อยทั้งต้น',
        'image_url' => 'uploads/otop/thread.jpg',
        'tag' => 'เผนอho',
        'tag_color' => 'badge-danger'
    ],
    [
        'name' => 'เครื่องสักขาด OTOP',
        'price' => 320,
        'description' => 'เครื่องสักขาด ผลงานช่างฝีมือปราณบุรี งานแกะสลักสวยงาม',
        'image_url' => 'uploads/otop/handicraft.jpg',
        'tag' => 'ศิลปะ',
        'tag_color' => 'badge-secondary'
    ]
];

// ตรวจสอบและเพิ่มสินค้า
foreach ($sample_products as $product) {
    $name = $conn->real_escape_string($product['name']);
    $price = $product['price'];
    $description = $conn->real_escape_string($product['description']);
    $image_url = $conn->real_escape_string($product['image_url']);
    $tag = $conn->real_escape_string($product['tag']);
    $tag_color = $conn->real_escape_string($product['tag_color']);
    
    // ตรวจสอบว่าสินค้านี้มีอยู่แล้วหรือไม่
    $check_sql = "SELECT id FROM otop_products WHERE name = '$name' LIMIT 1";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) == 0) {
        // เพิ่มสินค้าใหม่
        $insert_sql = "INSERT INTO otop_products (
            name, price, description, image_url, tag, tag_color
        ) VALUES (
            '$name', $price, '$description', '$image_url', '$tag', '$tag_color'
        )";
        
        if (mysqli_query($conn, $insert_sql)) {
            echo "✓ เพิ่มสินค้า: $name<br>";
        } else {
            echo "✗ เพิ่มสินค้าล้มเหลว: $name - " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "~ สินค้ามีอยู่แล้ว: $name<br>";
    }
}

echo "<br>";
echo "✓ เรียบร้อบ! ตอนนี้ระบบชำระเงินพร้อมใช้งาน<br>";
echo "<a href='product.php'>ไปหน้าสินค้า &raquo;</a>";
?>
