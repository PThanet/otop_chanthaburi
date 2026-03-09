<?php
include 'includes/header.php';

$product_name = isset($_GET['name']) ? $_GET['name'] : 'Unknown Product';
$product_price = isset($_GET['price']) ? $_GET['price'] : '0';
$product_img = isset($_GET['img']) ? $_GET['img'] : 'otop/CrispyPineapple.jpg';
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
            max-width: 500px;
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
                <div class="thumbnails">
                    <img src="otop/CrispyPineapple2.png" alt="Thumbnail 1" onclick="changeMainImage('otop/CrispyPineapple2.png', this)" class="active">
                    <img src="otop/CrispyPineapple3.png" alt="Thumbnail 2" onclick="changeMainImage('otop/CrispyPineapple3.png', this)">
                    <img src="otop/CrispyPineapple4.png" alt="Thumbnail 3" onclick="changeMainImage('otop/CrispyPineapple4.png', this)">
                    <img src="otop/CrispyPineapple.png" alt="Thumbnail 4" onclick="changeMainImage('otop/CrispyPineapple.png', this)">
                </div>
            </div>
            <div class="product-info">
                <h2><?= $product_name ?></h2>
                <p>Price: ฿<?= $product_price ?></p>
                <p>Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel nisi id odio tincidunt tincidunt.</p>
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