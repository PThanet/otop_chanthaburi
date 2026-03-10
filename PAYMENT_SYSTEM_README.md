# ระบบชำระเงิน OTOP Chanthaburi

## 📋 การเตรียมระบบ

### ขั้นที่ 1: สร้างตารางฐานข้อมูล

วิธีที่ 1: ใช้ phpMyAdmin
1. เปิด phpMyAdmin
2. เลือกฐานข้อมูล `otop_chanthaburi`
3. ไป Tab "SQL"
4. คัดลอก SQL จากไฟล์ `create_tables.sql`
5. กด Execute

วิธีที่ 2: ใช้ Command Line
```bash
mysql -u root -p1234 otop_chanthaburi < create_tables.sql
```

### ขั้นที่ 2: สร้างข้อมูลตัวอย่าง

**ทำให้เสร็จก่อน:** ต้องสร้างตารางก่อนแล้ว

วิธีที่ 1: ผ่าน Browser
1. เปิด URL: `http://localhost/otop_chanthaburi/setup_sample_data.php`
2. ระบบจะสร้างสินค้าตัวอย่าง 5 รายการ

วิธีที่ 2: ใช้ phpMyAdmin
```sql
INSERT INTO otop_products (name, price, description, image_url, tag, tag_color, stock) 
VALUES 
('ลูกอม OTOP จันทบุรี', 150, 'ลูกอมสูตรดั้งเดิม...', 'uploads/otop/candy.jpg', 'ขายดี', 'badge-success', 100),
('น้ำพริกแบบแห้ง', 200, 'น้ำพริกรสชาติจัดจ้าน...', 'uploads/otop/chili.jpg', 'ใหม่', 'badge-info', 100),
('เม็ดมะม่วง', 180, 'เม็ดมะม่วงเคี่ยว...', 'uploads/otop/mango.jpg', 'ขายดี', 'badge-warning', 100);
```

## 🔍 ตารางที่สร้าง

### 1. `otop_products` - สินค้า
```sql
CREATE TABLE otop_products (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `description` LONGTEXT,
  `image_url` VARCHAR(255),
  `tag` VARCHAR(50),
  `tag_color` VARCHAR(50),
  `stock` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### 2. `orders` - ออเดอร์
```sql
CREATE TABLE orders (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `shipping_cost` DECIMAL(10,2) DEFAULT 0,
  `status` VARCHAR(50) DEFAULT 'pending',
  `payment_method` VARCHAR(50),
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(100),
  `customer_phone` VARCHAR(20) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
)
```

### 3. `order_items` - รายการสินค้า
```sql
CREATE TABLE order_items (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `product_price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `order_id` (`order_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `otop_products` (`id`) ON DELETE RESTRICT
)
```

### 4. `shipping_addresses` - ที่อยู่ส่ง (ตัวเลือก)
```sql
CREATE TABLE shipping_addresses (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `province` VARCHAR(50) NOT NULL,
  `district` VARCHAR(50) NOT NULL,
  `subdistrict` VARCHAR(50) NOT NULL,
  `postal_code` VARCHAR(10) NOT NULL,
  `address` TEXT NOT NULL,
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
)
```

## 📄 ไฟล์ที่สร้าง

| ไฟล์ | ความหมาย |
|------|---------|
| `checkout.php` | หน้ากรอกข้อมูลการสั่งซื้อ |
| `process_checkout.php` | API บันทึกออเดอร์ลงฐานข้อมูล |
| `order_confirmation.php` | หน้าแสดงผลการสั่งซื้อสำเร็จ |
| `view_orders.php` | หน้าดูประวัติออเดอร์ |
| `setup_sample_data.php` | อัปเดตเพิ่มสินค้าตัวอย่าง |
| `create_tables.sql` | SQL สร้างตารางทั้งหมด |

## 🎯 ขั้นตอนการใช้งาน

### ผู้ใช้ (Customer)
1. ดูสินค้า → `product.php`
2. กดปุ่ม "Add to Cart" ใน `order.php`
3. ไปตะกร้า → `cart.php`
4. กดปุ่ม "ชำระเงิน" → `checkout.php`
5. กรอกข้อมูลที่อยู่และเลือกวิธีชำระเงิน
6. กด "ยืนยันการสั่งซื้อ" → บันทึกลงฐานข้อมูล
7. ได้รับหน้า confirmation → `order_confirmation.php`

### Admin (จัดการออเดอร์)
- ต้องสร้างหน้า Admin หลังจากนี้ เพื่อ:
  - ตรวจสอบการชำระเงิน
  - อัปเดตสถานะออเดอร์
  - จัดการการส่ง
  - ดูรายงาน

## 🔐 ข้อมูลบัญชีธนาคาร (ตัวอย่าง)

หลังจากชำระเงิน ระบบจะแสดง:

**โอนเงินจากธนาคาร:**
- ธนาคาร: กรุงไทย
- ชื่อบัญชี: บริษัท OTOP จันทบุรี
- เลขที่บัญชี: 123-456-7890

**หรือ QR Code:**
- PromptPay: 0812345678

**หรือ COD (Cash on Delivery)**
- ชำระเงินสดให้ผู้ส่ง

## ✅ ทดสอบระบบ

1. เปิด http://localhost/otop_chanthaburi/product.php
2. ดูสินค้าและเพิ่มลงตะกร้า
3. กดปุ่ม "ชำระเงิน"
4. กรอกข้อมูลและ submit
5. ดูออเดอร์ที่ http://localhost/otop_chanthaburi/view_orders.php

---

**สร้างโดย:** GitHub Copilot
**วันที่:** 11 มีนาคม 2566
