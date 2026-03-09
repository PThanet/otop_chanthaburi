<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['admin_username'])) {
    echo "<script>alert('เฉพาะผู้ดูแลระบบเท่านั้น!'); window.location='login_admin.php';</script>";
    exit();
}

include('includes/db_config.php');

// --- จัดการการลบข้อมูล (Delete) ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql_delete = "DELETE FROM travel_places WHERE id = $id";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('ลบข้อมูลสำเร็จ!'); window.location='admin_travel.php';</script>";
    }
}

// --- จัดการการเพิ่มข้อมูล (Add) ---
if (isset($_POST['add_place'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $img = mysqli_real_escape_string($conn, $_POST['image_url']);
    $tag = mysqli_real_escape_string($conn, $_POST['tag']);

    $sql_insert = "INSERT INTO travel_places (name, description, image_url, tag) VALUES ('$name', '$desc', '$img', '$tag')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('เพิ่มข้อมูลสำเร็จ!'); window.location='admin_travel.php';</script>";
    }
}

// --- จัดการการแก้ไขข้อมูล (Update) ---
if (isset($_POST['update_place'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $img = mysqli_real_escape_string($conn, $_POST['image_url']);
    $tag = mysqli_real_escape_string($conn, $_POST['tag']);

    $sql_update = "UPDATE travel_places SET name='$name', description='$desc', image_url='$img', tag='$tag' WHERE id=$id";
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location='admin_travel.php';</script>";
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
                    <form method="POST" action="admin_travel.php">
                        <?php if($edit_data): ?>
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
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
                            <label class="form-label fw-bold">URL รูปภาพ</label>
                            <input type="text" name="image_url" class="form-control" placeholder="เช่น otop/waterfall.jpg หรือลิงก์เว็บ" value="<?= $edit_data ? htmlspecialchars($edit_data['image_url']) : '' ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">ป้ายกำกับ (Tag)</label>
                            <input type="text" name="tag" class="form-control" placeholder="เช่น ธรรมชาติ, ทะเล, วัด" value="<?= $edit_data ? htmlspecialchars($edit_data['tag']) : '' ?>" required>
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
                                                <a href='admin_travel.php?delete={$row['id']}' class='btn btn-sm btn-danger mb-1' onclick=\"return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสถานที่นี้?');\"><i class='fas fa-trash'></i></a>
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