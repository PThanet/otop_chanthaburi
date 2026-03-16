<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['admin_username'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เฉพาะผู้ดูแลระบบเท่านั้น!', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'login_admin.php'; }); }, 100);</script>";
    exit();
}

$current_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'superadmin';
if ($current_role !== 'superadmin' && $current_role !== 'admin_travel') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สิทธิ์การเข้าถึงถูกปฏิเสธ! คุณไม่มีสิทธิ์จัดการส่วนนี้', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php'; }); }, 100);</script>";
    exit();
}

include('includes/db_config.php');

// --- อัตโนมัติสร้าง columns สำหรับหลายรูปภาพ (ถ้ายังไม่มี) ---
$columns_to_check = ['image_url_2', 'image_url_3', 'image_url_4'];
$existing_columns = [];

$result = mysqli_query($conn, "DESCRIBE travel_places");
while ($row = mysqli_fetch_assoc($result)) {
    $existing_columns[] = $row['Field'];
}

if (!in_array('image_url_2', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `travel_places` ADD COLUMN `image_url_2` VARCHAR(255) DEFAULT NULL AFTER `image_url`");
}
if (!in_array('image_url_3', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `travel_places` ADD COLUMN `image_url_3` VARCHAR(255) DEFAULT NULL AFTER `image_url_2`");
}
if (!in_array('image_url_4', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `travel_places` ADD COLUMN `image_url_4` VARCHAR(255) DEFAULT NULL AFTER `image_url_3`");
}

// --- จัดการการลบข้อมูล (Delete) ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql_delete = "DELETE FROM travel_places WHERE id = $id";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'ลบข้อมูลสำเร็จ!', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_travel.php'; }); }, 100);</script>";
    }
}

// --- จัดการการเพิ่มข้อมูล (Add) ---
if (isset($_POST['add_place'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $tag = mysqli_real_escape_string($conn, $_POST['tag']);
    
    $images = ['', '', '', ''];
    
    // จัดการอัปโหลดรูปภาพ (สูงสุด 4 รูป)
    for ($i = 0; $i < 4; $i++) {
        $file_key = 'image_file_' . ($i + 1);
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $file_extension = pathinfo($_FILES[$file_key]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = "uploads/travel/" . $new_filename;
            if (move_uploaded_file($_FILES[$file_key]["tmp_name"], $target_file)) {
                $images[$i] = $target_file;
            }
        }
    }

    $sql_insert = "INSERT INTO travel_places (name, description, image_url, image_url_2, image_url_3, image_url_4, tag) 
                   VALUES ('$name', '$desc', '$images[0]', '$images[1]', '$images[2]', '$images[3]', '$tag')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เพิ่มข้อมูลสำเร็จ!', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_travel.php'; }); }, 100);</script>";
    }
}

// --- จัดการการแก้ไขข้อมูล (Update) ---
if (isset($_POST['update_place'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $tag = mysqli_real_escape_string($conn, $_POST['tag']);
    
    $images = [];
    for ($i = 1; $i <= 4; $i++) {
        $key = 'existing_image_' . $i;
        $images[$i-1] = isset($_POST[$key]) ? mysqli_real_escape_string($conn, $_POST[$key]) : '';
    }

    // จัดการอัปโหลดรูปภาพใหม่
    for ($i = 0; $i < 4; $i++) {
        $file_key = 'image_file_' . ($i + 1);
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $file_extension = pathinfo($_FILES[$file_key]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = "uploads/travel/" . $new_filename;
            if (move_uploaded_file($_FILES[$file_key]["tmp_name"], $target_file)) {
                $images[$i] = $target_file;
            }
        }
    }

    $sql_update = "UPDATE travel_places SET 
                   name='$name', 
                   description='$desc', 
                   image_url='$images[0]', 
                   image_url_2='$images[1]', 
                   image_url_3='$images[2]', 
                   image_url_4='$images[3]', 
                   tag='$tag' 
                   WHERE id=$id";
    if (mysqli_query($conn, $sql_update)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'อัปเดตข้อมูลสำเร็จ!', icon: 'success', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_travel.php'; }); }, 100);</script>";
    }
}

// ดึงข้อมูลกรณีมีการกดปุ่ม "แก้ไข"
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result_edit = mysqli_query($conn, "SELECT * FROM travel_places WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result_edit);
}

include('includes/header.php');
?>

<div class="bg-dark text-white text-center py-4 mb-4 shadow-sm" style="border-bottom: 4px solid #198754;">
    <div class="container">
        <h1 class="fw-bold mb-0"><i class="fas fa-map-marked-alt me-3"></i>จัดการข้อมูลสถานที่ท่องเที่ยว</h1>
        <p class="mb-0 mt-2 text-white-50">เพิ่ม ลบ และแก้ไข แหล่งท่องเที่ยวสำหรับแสดงผลบนหน้าเว็บไซต์</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid <?= $edit_data ? '#ffc107' : '#198754' ?> !important; border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold <?= $edit_data ? 'text-warning' : 'text-success' ?> mb-0">
                        <i class="fas <?= $edit_data ? 'fa-edit' : 'fa-plus-circle' ?> me-2"></i>
                        <?= $edit_data ? 'แก้ไขสถานที่ท่องเที่ยว' : 'เพิ่มสถานที่ใหม่' ?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="admin_travel.php" enctype="multipart/form-data">
                        <?php if($edit_data): ?>
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                            <input type="hidden" name="existing_image_1" value="<?= htmlspecialchars($edit_data['image_url'] ?? '') ?>">
                            <input type="hidden" name="existing_image_2" value="<?= htmlspecialchars($edit_data['image_url_2'] ?? '') ?>">
                            <input type="hidden" name="existing_image_3" value="<?= htmlspecialchars($edit_data['image_url_3'] ?? '') ?>">
                            <input type="hidden" name="existing_image_4" value="<?= htmlspecialchars($edit_data['image_url_4'] ?? '') ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อสถานที่</label>
                            <input type="text" name="name" class="form-control" value="<?= $edit_data ? htmlspecialchars($edit_data['name']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">คำบรรยาย</label>
                            <textarea name="description" class="form-control" rows="3" required><?= $edit_data ? htmlspecialchars($edit_data['description']) : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ป้ายกำกับ (Tag)</label>
                            <input type="text" name="tag" class="form-control" placeholder="เช่น ธรรมชาติ, ทะเล, วัด" value="<?= $edit_data ? htmlspecialchars($edit_data['tag']) : '' ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-images me-2"></i>อัปโหลดหลายรูปภาพ (สูงสุด 4 รูป)</label>
                            <small class="text-muted d-block mb-3">ระบบจะปรับขนาดรูปภาพให้เท่ากันโดยอัตโนมัติ</small>
                            
                            <?php for ($i = 1; $i <= 4; $i++): 
                                $img_key = 'image_url' . ($i > 1 ? '_' . $i : '');
                                $img_src = $edit_data ? ($edit_data[$img_key] ?? '') : '';
                            ?>
                                <div class="mb-3 p-3 border rounded" style="background: #f8f9fa;">
                                    <label class="form-label fw-bold mb-2">รูปภาพที่ <?= $i ?></label>
                                    <?php if($edit_data && $img_src): ?>
                                        <div class="mb-2 text-center">
                                            <img src="<?= htmlspecialchars($img_src) ?>" alt="Image <?= $i ?>" class="img-thumbnail rounded" style="max-height:100px; object-fit:cover;">
                                        </div>
                                        <small class="text-muted d-block mb-2">เลือกไฟล์ใหม่หากต้องการเปลี่ยน</small>
                                    <?php endif; ?>
                                    <input type="file" name="image_file_<?= $i ?>" class="form-control" accept="image/*" <?= ($i === 1 && !$edit_data) ? 'required' : '' ?>>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <?php if($edit_data): ?>
                            <button type="submit" name="update_place" class="btn btn-warning w-100 fw-bold rounded-pill shadow-sm mb-2"><i class="fas fa-save me-2"></i>บันทึกการแก้ไข</button>
                            <a href="admin_travel.php" class="btn btn-light w-100 fw-bold rounded-pill border">ยกเลิก</a>
                        <?php else: ?>
                            <button type="submit" name="add_place" class="btn btn-success w-100 fw-bold rounded-pill shadow-sm"><i class="fas fa-plus me-2"></i>เพิ่มข้อมูล</button>
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
                                    <th width="15%">รูปภาพ</th>
                                    <th width="20%">ชื่อสถานที่</th>
                                    <th width="35%">คำบรรยาย</th>
                                    <th width="10%">Tag</th>
                                    <th width="20%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM travel_places ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                
                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><img src='".htmlspecialchars($row['image_url'])."' class='img-thumbnail rounded' style='width: 80px; height: 60px; object-fit: cover;'></td>";
                                        echo "<td class='fw-bold'>".htmlspecialchars($row['name'])."</td>";
                                        echo "<td class='text-start small text-muted text-truncate' style='max-width: 200px;'>".htmlspecialchars($row['description'])."</td>";
                                        echo "<td><span class='badge bg-secondary'>".htmlspecialchars($row['tag'])."</span></td>";
                                        echo "<td>
                                                <a href='admin_travel.php?edit={$row['id']}' class='btn btn-sm btn-warning mb-1'><i class='fas fa-edit'></i></a>
                                                <a href='admin_travel.php?delete={$row['id']}' class='btn btn-sm btn-danger mb-1' onclick=\"event.preventDefault(); Swal.fire({title: 'คุณแน่ใจหรือไม่ว่าต้องการลบสถานที่นี้?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'ตกลง', cancelButtonText: 'ยกเลิก'}).then((result) => { if (result.isConfirmed) { window.location.href = this.href; } })\"><i class='fas fa-trash'></i></a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='py-4 text-muted'>ยังไม่มีข้อมูลสถานที่ท่องเที่ยว</td></tr>";
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