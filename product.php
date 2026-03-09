<?php include('includes/header.php'); ?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-5"><i class="fas fa-shopping-basket text-success"></i> สินค้า OTOP แนะนำ</h2>
    <div class="row g-4">
        <?php
        $products = [
            ["name" => "สัปปะรดกรอบ", "price" => "150", "img" => "otop/CrispyPineapple2.png", "tag" => "ขายดี"],
            ["name" => "น้ำพริกเผาลำไย", "price" => "89", "img" => "otop/ChiliSauce.jpg", "tag" => "แนะนำ"],
            ["name" => "เสื่อจันทบูร", "price" => "450", "img" => "otop/Mat.jpg", "tag" => "Handmade"]
        ];

        foreach($products as $p) { ?>
            <div class="col-md-4">
                <div class="card card-hover h-100 shadow-sm">
                    <img src="<?= $p['img'] ?>" class="card-img-top" style="height:250px; object-fit:cover;">
                    <div class="card-body p-4 text-center">
                        <span class="badge bg-danger mb-2"><?= $p['tag'] ?></span>
                        <h4 class="fw-bold"><?= $p['name'] ?></h4>
                        <p class="text-success fs-4 fw-bold mb-3">฿<?= $p['price'] ?></p>
                        <a href="order.php?name=<?= urlencode($p['name']) ?>&price=<?= $p['price'] ?>&img=<?= urlencode($p['img']) ?>" class="btn btn-primary w-100 rounded-pill">สั่งซื้อ</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
