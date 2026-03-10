<?php
include 'includes/db_config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product_name = 'Unknown Product';
$product_price = '0';
$product_img = 'otop/CrispyPineapple.jpg';
$product_desc = 'ไม่มีคำอธิบายสินค้า';
$images = [];

if ($product_id > 0) {
    $sql = "SELECT * FROM otop_products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $product_name = htmlspecialchars($row['name']);
        $product_price = htmlspecialchars($row['price']);
        
        $product_img = !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : $product_img;
        
        if (!empty($row['description'])) {
            $product_desc = htmlspecialchars($row['description']);
        }

        // เก็บรูปลง array ไว้แสดงใน thumbnail
        if (!empty($row['image_url'])) $images[] = htmlspecialchars($row['image_url']);
        if (!empty($row['image_url_2'])) $images[] = htmlspecialchars($row['image_url_2']);
        if (!empty($row['image_url_3'])) $images[] = htmlspecialchars($row['image_url_3']);
        if (!empty($row['image_url_4'])) $images[] = htmlspecialchars($row['image_url_4']);
    }
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Page</title>
    <link rel="stylesheet" href="includes/style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            flex: 1;
        }
        footer {
            margin-top: auto;
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
        }
        .order-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
        }
        .product-image {
            width: 500px; /* Ensure consistent width */
            height: auto; /* Maintain aspect ratio */
            border-radius: 10px;
            margin-right: 30px;
        }
        .product-info {
            max-width: 500px;
        }
        .product-info h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .product-info p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .actions {
            display: flex;
            flex-direction: column;
            gap: 20px; /* Increased gap between buttons */
        }
        .actions button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-to-cart {
            background-color: #f8c146;
            color: white;
        }
        .buy-now {
            background-color: #ff4d4f;
            color: white;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px; /* Adjusted gap between + and - buttons */
            margin-bottom: 20px; /* Added spacing below the quantity control */
        }
        .quantity-control button {
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
            border: none;
            background-color: #f8c146;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .quantity-control input {
            width: 60px;
            text-align: center;
            font-size: 1.2rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .image-gallery {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .thumbnails {
            display: flex;
            gap: 10px;
        }
        .thumbnails img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
        }
        .thumbnails img.active {
            border-color: #f8c146;
        }
    </style>

    <script>
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }

        function changeMainImage(src, element) {
            const mainImage = document.getElementById('main-product-image');
            mainImage.src = src;
            mainImage.style.width = '500px'; // Ensure the image width remains the same
            mainImage.style.height = 'auto'; // Maintain aspect ratio

            const thumbnails = document.querySelectorAll('.thumbnails img');
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            element.classList.add('active');
        }
    </script>

    <div class="container">
        <h1>Order Product</h1>
        <div class="order-container">
            <div class="image-gallery">
                <img id="main-product-image" src="<?= $product_img ?>" alt="Product Image" class="product-image">
                <?php if(count($images) > 1): ?>
                <div class="thumbnails">
                    <?php foreach($images as $index => $img): ?>
                        <img src="<?= $img ?>" alt="Thumbnail <?= $index+1 ?>" onclick="changeMainImage('<?= $img ?>', this)" class="<?= $index === 0 ? 'active' : '' ?>">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="product-info">
                <h2><?= $product_name ?></h2>
                <p class="fs-4 fw-bold text-success">ราคา: ฿<?= $product_price ?></p>
                <div class="mb-4">
                    <strong>คำอธิบาย:</strong>
                    <p class="text-muted mt-2" style="font-size: 1rem; line-height: 1.6; white-space: pre-line;"><?= $product_desc ?></p>
                </div>
                <div class="quantity-control">
                    <button onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" min="1">
                    <button onclick="increaseQuantity()">+</button>
                </div>
                <div class="actions">
                    <button class="add-to-cart">Add to Cart</button>
                    <button class="buy-now">Buy Now</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 OTOP Chanthaburi. All rights reserved.</p>
    </footer>

<?php
include 'includes/footer.php';
?>
</body>
</html>