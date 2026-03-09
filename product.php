<?php include('includes/header.php'); ?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-5"><i class="fas fa-shopping-basket text-success"></i> สินค้า OTOP แนะนำ</h2>
    <div class="row g-4">
        <?php
        $products = [
            ["name" => "สัปปะรดกรอบ", "price" => "150", "img" => "https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=500", "tag" => "ขายดี"],
            ["name" => "น้ำพริกเผาลำไย", "price" => "89", "img" => "https://images.unsplash.com/photo-1588165171080-c89acfa5ee83?w=500", "tag" => "แนะนำ"],
            ["name" => "เสื่อจันทบูร", "price" => "450", "img" => "https://images.unsplash.com/photo-1590595906931-81f04f0ccebb?w=500", "tag" => "Handmade"]
        ];

        foreach($products as $p) { ?>
            <div class="col-md-4">
                <div class="card card-hover h-100 shadow-sm">
                    <img src="<?= $p['img'] ?>" class="card-img-top" style="height:250px; object-fit:cover;">
                    <div class="card-body p-4 text-center">
                        <span class="badge bg-danger mb-2"><?= $p['tag'] ?></span>
                        <h4 class="fw-bold"><?= $p['name'] ?></h4>
                        <p class="text-success fs-4 fw-bold mb-3">฿<?= $p['price'] ?></p>
                        <button class="btn btn-primary w-100 rounded-pill">รายละเอียดสินค้า</button>
                    </div>
                </div>
            </div>
        <?php } // ปิดปีกกาตรงนี้เพื่อแก้ Error Line 21 ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>