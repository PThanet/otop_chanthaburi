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
    $sql_delete = "DELETE FROM traditions WHERE id = $id";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('ลบข้อมูลประเพณีสำเร็จ!'); window.location='admin_tradition.php';</script>";
    }
}

// --- จัดการการเพิ่มข้อมูล (Add) ---
if (isset($_POST['add_tradition'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);

    $sql_insert = "INSERT INTO traditions (name, description, icon) VALUES ('$name', '$desc', '$icon')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('เพิ่มข้อมูลประเพณีสำเร็จ!'); window.location='admin_tradition.php';</script>";
    }
}

// --- จัดการการแก้ไขข้อมูล (Update) ---
if (isset($_POST['update_tradition'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);

    $sql_update = "UPDATE traditions SET name='$name', description='$desc', icon='$icon' WHERE id=$id";
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('อัปเดตข้อมูลประเพณีสำเร็จ!'); window.location='admin_tradition.php';</script>";
    }
}

// ดึงข้อมูลกรณีมีการกดปุ่ม "แก้ไข"
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result_edit = mysqli_query($conn, "SELECT * FROM traditions WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result_edit);
}

include('includes/header.php');
?>

<div class="bg-dark text-white text-center py-4 mb-4 shadow-sm" style="border-bottom: 4px solid #ff9800;">
    <div class="container">
        <h1 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-3"></i>จัดการข้อมูลงานประเพณี</h1>
        <p class="mb-0 mt-2 text-white-50">เพิ่ม ลบ และแก้ไข งานประเพณีสำคัญสำหรับแสดงผลบนหน้าเว็บไซต์</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid <?= $edit_data ? '#0dcaf0' : '#ff9800' ?> !important; border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold <?= $edit_data ? 'text-info' : 'text-warning text-dark' ?> mb-0">
                        <i class="fas <?= $edit_data ? 'fa-edit' : 'fa-plus-circle' ?> me-2"></i>
                        <?= $edit_data ? 'แก้ไขงานประเพณี' : 'เพิ่มประเพณีใหม่' ?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="admin_tradition.php">
                        <?php if($edit_data): ?>
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่องานประเพณี</label>
                            <input type="text" name="name" class="form-control" value="<?= $edit_data ? htmlspecialchars($edit_data['name']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">คำบรรยาย</label>
                            <textarea name="description" class="form-control" rows="4" required><?= $edit_data ? htmlspecialchars($edit_data['description']) : '' ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">คลาสไอคอน (Font Awesome)</label>
                            <input type="text" name="icon" class="form-control" placeholder="เช่น fa-monument" value="<?= $edit_data ? htmlspecialchars($edit_data['icon']) : '' ?>" required>
                            <small class="text-muted">ดูชื่อไอคอนได้ที่ <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">Font Awesome</a></small>
                        </div>

                        <?php if($edit_data): ?>
                            <button type="submit" name="update_tradition" class="btn btn-info w-100 fw-bold rounded-pill shadow-sm mb-2 text-white"><i class="fas fa-save me-2"></i>บันทึกการแก้ไข</button>
                            <a href="admin_tradition.php" class="btn btn-light w-100 fw-bold rounded-pill border">ยกเลิก</a>
                        <?php else: ?>
                            <button type="submit" name="add_tradition" class="btn btn-warning w-100 fw-bold rounded-pill shadow-sm text-dark"><i class="fas fa-plus me-2"></i>เพิ่มข้อมูล</button>
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
                                    <th width="10%">ไอคอน</th>
                                    <th width="25%">ชื่องานประเพณี</th>
                                    <th width="40%">คำบรรยาย</th>
                                    <th width="25%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM traditions ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                
                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><div style='width: 50px; height: 50px; border-radius: 50%; background: #ffc107; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto;'><i class='fas " . htmlspecialchars($row['icon']) . " fa-lg'></i></div></td>";
                                        echo "<td class='fw-bold'>".htmlspecialchars($row['name'])."</td>";
                                        echo "<td class='text-start small text-muted text-truncate' style='max-width: 250px;'>".htmlspecialchars($row['description'])."</td>";
                                        echo "<td>
                                                <a href='admin_tradition.php?edit={$row['id']}' class='btn btn-sm btn-info text-white mb-1 px-3 rounded-pill shadow-sm'><i class='fas fa-edit'></i> แก้ไข</a>
                                                <a href='admin_tradition.php?delete={$row['id']}' class='btn btn-sm btn-danger mb-1 px-3 rounded-pill shadow-sm' onclick=\"return confirm('ระวัง! คุณแน่ใจหรือไม่ที่จะลบประเพณีนี้?');\"><i class='fas fa-trash'></i> ลบ</a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='py-4 text-muted'>ยังไม่มีข้อมูลงานประเพณี</td></tr>";
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
