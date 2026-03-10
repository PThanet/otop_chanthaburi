<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['admin_username'])) {
    echo "<script>alert('เฉพาะผู้ดูแลระบบเท่านั้น!'); window.location='login_admin.php';</script>";
    exit();
}

$current_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'superadmin';
if ($current_role !== 'superadmin' && $current_role !== 'admin_product') {
    echo "<script>alert('สิทธิ์การเข้าถึงถูกปฏิเสธ! คุณไม่มีสิทธิ์จัดการส่วนนี้'); window.location='admin_dashboard.php';</script>";
    exit();
}

include('includes/db_config.php');

// --- จัดการการลบข้อมูล (Delete) ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql_delete = "DELETE FROM otop_products WHERE id = $id";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('ลบข้อมูลสินค้า OTOP สำเร็จ!'); window.location='admin_product.php';</script>";
    }
}

// --- ฟังก์ชันช่วยเหลือสำหรับการอัปโหลดรูปภาพ ---
function upload_image($file_input_name, $existing_image = '') {
    $img = $existing_image;
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
        $file_extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '_' . $file_input_name . '.' . $file_extension;
        $target_file = "uploads/otop/" . $new_filename;
        if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
            $img = $target_file;
        }
    }
    return $img;
}

// --- จัดการการเพิ่มข้อมูล (Add) ---
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $tag = mysqli_real_escape_string($conn, $_POST['tag']);
    $tag_color = mysqli_real_escape_string($conn, $_POST['tag_color']);

    $image_url = upload_image('image_file');
    $image_url_2 = upload_image('image_file_2');
    $image_url_3 = upload_image('image_file_3');
    $image_url_4 = upload_image('image_file_4');

    $sql_insert = "INSERT INTO otop_products (name, price, description, image_url, image_url_2, image_url_3, image_url_4, tag, tag_color) 
                   VALUES ('$name', '$price', '$desc', '$image_url', '$image_url_2', '$image_url_3', '$image_url_4', '$tag', '$tag_color')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('เพิ่มข้อมูลสินค้า OTOP สำเร็จ!'); window.location='admin_product.php';</script>";
    }
}

// --- จัดการการแก้ไขข้อมูล (Update) ---
if (isset($_POST['update_product'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $tag = mysqli_real_escape_string($conn, $_POST['tag']);
    $tag_color = mysqli_real_escape_string($conn, $_POST['tag_color']);
    
    $existing_image = mysqli_real_escape_string($conn, $_POST['existing_image']);
    $existing_image_2 = mysqli_real_escape_string($conn, $_POST['existing_image_2']);
    $existing_image_3 = mysqli_real_escape_string($conn, $_POST['existing_image_3']);
    $existing_image_4 = mysqli_real_escape_string($conn, $_POST['existing_image_4']);

    $image_url = upload_image('image_file', $existing_image);
    $image_url_2 = upload_image('image_file_2', $existing_image_2);
    $image_url_3 = upload_image('image_file_3', $existing_image_3);
    $image_url_4 = upload_image('image_file_4', $existing_image_4);

    $sql_update = "UPDATE otop_products SET 
                   name='$name', 
                   price='$price', 
                   description='$desc',
                   image_url='$image_url', 
                   image_url_2='$image_url_2', 
                   image_url_3='$image_url_3', 
                   image_url_4='$image_url_4', 
                   tag='$tag', 
                   tag_color='$tag_color' 
                   WHERE id=$id";
                   
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('อัปเดตข้อมูลสินค้า OTOP สำเร็จ!'); window.location='admin_product.php';</script>";
    }
}

// ดึงข้อมูลกรณีมีการกดปุ่ม "แก้ไข"
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result_edit = mysqli_query($conn, "SELECT * FROM otop_products WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result_edit);
}

include('includes/header.php');
?>

<div class="bg-dark text-white text-center py-4 mb-4 shadow-sm" style="border-bottom: 4px solid #0dcaf0;">
    <div class="container">
        <h1 class="fw-bold mb-0"><i class="fas fa-shopping-basket me-3"></i>จัดการข้อมูลสินค้า OTOP</h1>
        <p class="mb-0 mt-2 text-white-50">เพิ่ม ลบ และแก้ไข สินค้า OTOP แนะนำสำหรับแสดงผลบนหน้าเว็บไซต์</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid <?= $edit_data ? '#ffc107' : '#0dcaf0' ?> !important; border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold <?= $edit_data ? 'text-warning' : 'text-info text-dark' ?> mb-0">
                        <i class="fas <?= $edit_data ? 'fa-edit' : 'fa-plus-circle' ?> me-2"></i>
                        <?= $edit_data ? 'แก้ไขสินค้า OTOP' : 'เพิ่มสินค้า OTOP ใหม่' ?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="admin_product.php" enctype="multipart/form-data">
                        <?php if($edit_data): ?>
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_data['image_url'] ?? '') ?>">
                            <input type="hidden" name="existing_image_2" value="<?= htmlspecialchars($edit_data['image_url_2'] ?? '') ?>">
                            <input type="hidden" name="existing_image_3" value="<?= htmlspecialchars($edit_data['image_url_3'] ?? '') ?>">
                            <input type="hidden" name="existing_image_4" value="<?= htmlspecialchars($edit_data['image_url_4'] ?? '') ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อสินค้า</label>
                            <input type="text" name="name" class="form-control" value="<?= $edit_data ? htmlspecialchars($edit_data['name']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ราคา (บาท)</label>
                            <input type="number" name="price" class="form-control" value="<?= $edit_data ? htmlspecialchars($edit_data['price']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">คำอธิบายสินค้า</label>
                            <textarea name="description" class="form-control" rows="4"><?= $edit_data ? htmlspecialchars($edit_data['description'] ?? '') : '' ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">รูปภาพหลัก (ปก)</label>
                            <?php if($edit_data && !empty($edit_data['image_url'])): ?>
                                <div class="mb-2"><img src="<?= htmlspecialchars($edit_data['image_url']) ?>" class="img-thumbnail" style="height:80px; object-fit:cover;"></div>
                            <?php endif; ?>
                            <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*" <?= ($edit_data && !empty($edit_data['image_url'])) ? '' : 'required' ?>>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold text-muted mb-3"><i class="fas fa-images me-2"></i>รูปภาพเพิ่มเติม (Thumbnail)</label>
                            
                            <!-- รูปที่ 2 -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold">รูปภาพที่ 2</label>
                                <?php if($edit_data && !empty($edit_data['image_url_2'])): ?>
                                    <div class="mb-2"><img src="<?= htmlspecialchars($edit_data['image_url_2']) ?>" class="img-thumbnail" style="height:60px; object-fit:cover;"></div>
                                <?php endif; ?>
                                <input type="file" name="image_file_2" class="form-control form-control-sm" accept="image/*">
                            </div>
                            
                            <!-- รูปที่ 3 -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold">รูปภาพที่ 3</label>
                                <?php if($edit_data && !empty($edit_data['image_url_3'])): ?>
                                    <div class="mb-2"><img src="<?= htmlspecialchars($edit_data['image_url_3']) ?>" class="img-thumbnail" style="height:60px; object-fit:cover;"></div>
                                <?php endif; ?>
                                <input type="file" name="image_file_3" class="form-control form-control-sm" accept="image/*">
                            </div>
                            
                            <!-- รูปที่ 4 -->
                            <div class="mb-2">
                                <label class="form-label small fw-bold">รูปภาพที่ 4</label>
                                <?php if($edit_data && !empty($edit_data['image_url_4'])): ?>
                                    <div class="mb-2"><img src="<?= htmlspecialchars($edit_data['image_url_4']) ?>" class="img-thumbnail" style="height:60px; object-fit:cover;"></div>
                                <?php endif; ?>
                                <input type="file" name="image_file_4" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label fw-bold">ป้าย (Tag)</label>
                                <input type="text" name="tag" class="form-control" placeholder="เช่น ขายดี" value="<?= $edit_data ? htmlspecialchars($edit_data['tag']) : '' ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">สีป้าย (Class)</label>
                                <select name="tag_color" class="form-select" required>
                                    <option value="bg-danger" <?= ($edit_data && $edit_data['tag_color'] == 'bg-danger') ? 'selected' : '' ?>>แดง (Danger)</option>
                                    <option value="bg-warning text-dark" <?= ($edit_data && $edit_data['tag_color'] == 'bg-warning text-dark') ? 'selected' : '' ?>>เหลือง (Warning)</option>
                                    <option value="bg-success" <?= ($edit_data && $edit_data['tag_color'] == 'bg-success') ? 'selected' : '' ?>>เขียว (Success)</option>
                                    <option value="bg-info text-dark" <?= ($edit_data && $edit_data['tag_color'] == 'bg-info text-dark') ? 'selected' : '' ?>>ฟ้า (Info)</option>
                                    <option value="bg-primary" <?= ($edit_data && $edit_data['tag_color'] == 'bg-primary') ? 'selected' : '' ?>>น้ำเงิน (Primary)</option>
                                </select>
                            </div>
                        </div>

                        <?php if($edit_data): ?>
                            <button type="submit" name="update_product" class="btn btn-warning w-100 fw-bold rounded-pill shadow-sm mb-2 text-dark"><i class="fas fa-save me-2"></i>บันทึกการแก้ไข</button>
                            <a href="admin_product.php" class="btn btn-light w-100 fw-bold rounded-pill border">ยกเลิก</a>
                        <?php else: ?>
                            <button type="submit" name="add_product" class="btn btn-info w-100 fw-bold rounded-pill shadow-sm text-white"><i class="fas fa-plus me-2"></i>เพิ่มข้อมูล</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #0d6efd !important; border-radius: 12px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th width="15%">รูปสินค้า</th>
                                    <th width="25%">ชื่อสินค้า</th>
                                    <th width="15%">ราคา</th>
                                    <th width="20%">ป้าย (Tag)</th>
                                    <th width="25%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM otop_products ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                
                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><img src='".htmlspecialchars($row['image_url'])."' class='img-thumbnail rounded shadow-sm' style='width: 80px; height: 80px; object-fit: cover;'></td>";
                                        echo "<td class='fw-bold text-success'>".htmlspecialchars($row['name'])."</td>";
                                        echo "<td class='fw-bold fs-5'>฿".htmlspecialchars($row['price'])."</td>";
                                        echo "<td><span class='badge " . htmlspecialchars($row['tag_color']) . " px-3 py-2 rounded-pill'>".htmlspecialchars($row['tag'])."</span></td>";
                                        echo "<td>
                                                <a href='admin_product.php?edit={$row['id']}' class='btn btn-sm btn-warning text-dark mb-1 px-3 rounded-pill shadow-sm'><i class='fas fa-edit'></i> แก้ไข</a>
                                                <a href='admin_product.php?delete={$row['id']}' class='btn btn-sm btn-danger mb-1 px-3 rounded-pill shadow-sm' onclick=\"return confirm('ระวัง! คุณแน่ใจหรือไม่ที่จะลบสินค้าชิ้นนี้ออกจากระบบ?');\"><i class='fas fa-trash'></i> ลบ</a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='py-4 text-muted'>ยังไม่มีข้อมูลสินค้า OTOP</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>
