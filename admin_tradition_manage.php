<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_username'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เฉพาะผู้ดูแลระบบเท่านั้น!', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'login_admin.php'; }); }, 100);</script>";
    exit();
}

$current_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'superadmin';
if ($current_role !== 'superadmin' && $current_role !== 'admin_tradition') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สิทธิ์การเข้าถึงถูกปฏิเสธ!', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php'; }); }, 100);</script>";
    exit();
}

include('includes/db_config.php');

// ตรวจสอบและสร้าง columns หากไม่มี
$columns_to_check = ['event_date', 'event_details', 'event_location', 'image_url_2', 'image_url_3', 'image_url_4'];
$existing_columns = [];
$result = mysqli_query($conn, "DESCRIBE traditions");
while ($row = mysqli_fetch_assoc($result)) {
    $existing_columns[] = $row['Field'];
}
if (!in_array('event_date', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `traditions` ADD COLUMN `event_date` VARCHAR(50) DEFAULT NULL AFTER `description`");
}
if (!in_array('event_details', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `traditions` ADD COLUMN `event_details` TEXT DEFAULT NULL AFTER `event_date`");
}
if (!in_array('event_location', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `traditions` ADD COLUMN `event_location` VARCHAR(255) DEFAULT NULL AFTER `event_details`");
}
if (!in_array('image_url_2', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `traditions` ADD COLUMN `image_url_2` VARCHAR(255) DEFAULT NULL AFTER `image_url`");
}
if (!in_array('image_url_3', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `traditions` ADD COLUMN `image_url_3` VARCHAR(255) DEFAULT NULL AFTER `image_url_2`");
}
if (!in_array('image_url_4', $existing_columns)) {
    @mysqli_query($conn, "ALTER TABLE `traditions` ADD COLUMN `image_url_4` VARCHAR(255) DEFAULT NULL AFTER `image_url_3`");
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM traditions WHERE id = $id");
    echo "<script>setTimeout(function() { window.location = 'admin_tradition_manage.php'; }, 500);</script>";
}

// Add
if (isset($_POST['add_tradition'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_location = mysqli_real_escape_string($conn, $_POST['event_location']);
    $event_details = mysqli_real_escape_string($conn, $_POST['event_details']);
    
    $images = ['', '', '', ''];
    for ($i = 0; $i < 4; $i++) {
        $file_key = 'image_file_' . ($i + 1);
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $file_extension = pathinfo($_FILES[$file_key]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = "uploads/traditions/" . $new_filename;
            if (move_uploaded_file($_FILES[$file_key]["tmp_name"], $target_file)) {
                $images[$i] = $target_file;
            }
        }
    }

    $sql = "INSERT INTO traditions (name, description, image_url, image_url_2, image_url_3, image_url_4, event_date, event_location, event_details) 
            VALUES ('$name', '$desc', '$images[0]', '$images[1]', '$images[2]', '$images[3]', '$event_date', '$event_location', '$event_details')";
    mysqli_query($conn, $sql);
    echo "<script>setTimeout(function() { window.location = 'admin_tradition_manage.php'; }, 500);</script>";
}

// Update
if (isset($_POST['update_tradition'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_location = mysqli_real_escape_string($conn, $_POST['event_location']);
    $event_details = mysqli_real_escape_string($conn, $_POST['event_details']);
    
    $images = [];
    for ($i = 1; $i <= 4; $i++) {
        $key = 'existing_image_' . $i;
        $images[$i-1] = isset($_POST[$key]) ? mysqli_real_escape_string($conn, $_POST[$key]) : '';
    }

    for ($i = 0; $i < 4; $i++) {
        $file_key = 'image_file_' . ($i + 1);
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $file_extension = pathinfo($_FILES[$file_key]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = "uploads/traditions/" . $new_filename;
            if (move_uploaded_file($_FILES[$file_key]["tmp_name"], $target_file)) {
                $images[$i] = $target_file;
            }
        }
    }

    $sql = "UPDATE traditions SET 
            name='$name', 
            description='$desc', 
            image_url='$images[0]', 
            image_url_2='$images[1]', 
            image_url_3='$images[2]', 
            image_url_4='$images[3]', 
            event_date='$event_date', 
            event_location='$event_location', 
            event_details='$event_details' 
            WHERE id=$id";
    mysqli_query($conn, $sql);
    echo "<script>setTimeout(function() { window.location = 'admin_tradition_manage.php'; }, 500);</script>";
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM traditions WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result);
}

include('includes/header.php');
?>

<style>
    .admin-header {
        background: linear-gradient(135deg, #6f42c1 0%, #8554dd 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .form-section {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        border-top: 5px solid #6f42c1;
    }

    .form-section h3 {
        color: #6f42c1;
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        margin-right: 0.5rem;
        color: #6f42c1;
    }

    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.1);
        outline: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
        font-family: 'Sarabun', sans-serif;
    }

    .image-upload-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .image-upload-item {
        display: flex;
        flex-direction: column;
        min-width: 0;
        position: relative;
    }

    .image-upload-item .image-wrapper {
        position: relative;
        display: block;
        padding: 4px;
        padding-bottom: 4px;
        margin: 0 auto 0.5rem;
        height: auto;
        overflow: visible;
    }

    .btn-remove-image {
        position: absolute;
        top: 0;
        right: 0;
        width: 22px;
        height: 22px;
        background: #dc3545;
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        font-size: 12px;
        line-height: 18px;
        text-align: center;
        cursor: pointer;
        z-index: 10;
        padding: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
    }

    .btn-remove-image:hover {
        background: #c82333;
        transform: scale(1.15);
    }

    .image-upload-label {
        font-weight: 600;
        color: #6f42c1;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .file-input-wrapper {
        position: relative;
    }

    .file-input-custom {
        display: block;
        width: 100%;
        padding: 0.75rem 0.5rem;
        background: #f8f9fa;
        border: 2px dashed #6f42c1;
        border-radius: 10px;
        text-align: center;
        color: #6f42c1;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-input-custom:hover {
        background: #f1edfa;
        border-color: #5a32a3;
    }

    .file-input-hidden {
        display: none;
    }

    .image-upload-item .image-wrapper img.image-preview {
        position: static;
        width: 100%;
        aspect-ratio: 1;
        height: auto;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #e0e0e0;
        display: block;
    }

    .btn-submit {
        background: linear-gradient(135deg, #6f42c1 0%, #8554dd 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(111, 66, 193, 0.3);
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #333;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 0.5rem;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .table-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border-top: 5px solid #0d6efd;
        overflow-x: visible;
    }

    .table-section h3 {
        color: #0d6efd;
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
    }

    .admin-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .admin-table th {
        padding: 1rem 0.75rem;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        white-space: nowrap;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .admin-table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #dee2e6;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .admin-table tbody tr:hover {
        background: #f8f9fa;
    }

    .table-image {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.65rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-date {
        background: #fff3cd;
        color: #856404;
    }

    .btn-action {
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        margin: 0;
        white-space: nowrap;
        display: inline-block;
    }

    .actions-cell {
        vertical-align: middle;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: center;
    }

    .btn-edit {
        background: #0dcaf0;
        color: white;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: #0ba4c7;
        text-decoration: none;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
        text-decoration: none;
    }

    .btn-delete:hover {
        background: #c82333;
        text-decoration: none;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 1024px) {
        .two-column {
            grid-template-columns: 1fr;
        }

        .admin-table th, .admin-table td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .btn-action {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }

        .table-image {
            width: 45px;
            height: 45px;
        }
    }

    @media (max-width: 768px) {
        .two-column {
            grid-template-columns: 1fr;
        }

        .image-upload-group {
            grid-template-columns: repeat(2, 1fr);
        }

        .admin-table {
            min-width: 750px;
            font-size: 0.8rem;
        }

        .admin-table th, .admin-table td {
            padding: 0.5rem;
        }

        .btn-action {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
            margin-right: 0.2rem;
        }

        .form-label {
            font-size: 0.95rem;
        }
    }
</style>

<div class="admin-header">
    <div class="admin-container">
        <h1 class="mb-2"><i class="fas fa-torii-gate me-2"></i>จัดการประเพณี</h1>
        <p class="mb-0 text-white-50">เพิ่ม แก้ไข และลบข้อมูลงานประเพณี</p>
    </div>
</div>

<div class="container admin-container">
    <div class="row">
        <div class="col-lg-5">
            <div class="form-section">
                <h3><i class="fas fa-<?= $edit_data ? 'edit' : 'plus' ?> me-2"></i><?= $edit_data ? 'แก้ไขประเพณี' : 'เพิ่มประเพณีใหม่' ?></h3>
                
                <form method="POST" enctype="multipart/form-data">
                    <?php if($edit_data): ?>
                        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                        <input type="hidden" name="existing_image_1" value="<?= htmlspecialchars($edit_data['image_url']) ?>">
                        <input type="hidden" name="existing_image_2" value="<?= htmlspecialchars($edit_data['image_url_2'] ?? '') ?>">
                        <input type="hidden" name="existing_image_3" value="<?= htmlspecialchars($edit_data['image_url_3'] ?? '') ?>">
                        <input type="hidden" name="existing_image_4" value="<?= htmlspecialchars($edit_data['image_url_4'] ?? '') ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i>ชื่องาน *</label>
                        <input type="text" name="name" class="form-control" value="<?= $edit_data ? htmlspecialchars($edit_data['name']) : '' ?>" placeholder="เช่น งานเก่าแพร่" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i>รายละเอียด</label>
                        <textarea name="description" class="form-control" placeholder="อธิบายประเพณี..."><?= $edit_data ? htmlspecialchars($edit_data['description']) : '' ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-calendar"></i>วันที่จัดงาน</label>
                        <input type="text" name="event_date" class="form-control" value="<?= $edit_data ? htmlspecialchars($edit_data['event_date'] ?? '') : '' ?>" placeholder="เช่น 22 มกราคม">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i>สถานที่</label>
                        <input type="text" name="event_location" class="form-control" value="<?= $edit_data ? htmlspecialchars($edit_data['event_location'] ?? '') : '' ?>" placeholder="เช่น จันทบุรี">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-info-circle"></i>ข้อมูลเพิ่มเติม</label>
                        <textarea name="event_details" class="form-control" placeholder="รายละเอียดเพิ่มเติม..."><?= $edit_data ? htmlspecialchars($edit_data['event_details'] ?? '') : '' ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-images me-1"></i>รูปภาพ (สูงสุด 4 รูป)</label>
                        <div class="image-upload-group">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <div class="image-upload-item" data-slot="<?= $i ?>">
                                    <div class="image-upload-label">รูปที่ <?= $i ?></div>
                                    <?php 
                                        $image_key = 'image_url' . ($i > 1 ? '_' . $i : '');
                                        if ($edit_data && !empty($edit_data[$image_key])): 
                                    ?>
                                        <div class="image-wrapper">
                                            <img src="<?= htmlspecialchars($edit_data[$image_key]) ?>" class="image-preview" alt="Preview">
                                            <button type="button" class="btn-remove-image" title="ลบรูป">&times;</button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="file-input-wrapper">
                                        <label class="file-input-custom" data-image="<?= $i ?>">
                                            <i class="fas fa-cloud-upload-alt"></i> เลือกรูป
                                            <input type="file" name="image_file_<?= $i ?>" class="file-input-hidden" accept="image/*">
                                        </label>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <button type="submit" name="<?= $edit_data ? 'update_tradition' : 'add_tradition' ?>" class="btn-submit">
                        <i class="fas fa-<?= $edit_data ? 'save' : 'plus' ?> me-2"></i><?= $edit_data ? 'บันทึกการแก้ไข' : 'เพิ่มข้อมูล' ?>
                    </button>
                    
                    <?php if($edit_data): ?>
                        <a href="admin_tradition_manage.php" class="btn-cancel" style="text-decoration: none; display: inline-block;">ยกเลิก</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="table-section">
                <h3><i class="fas fa-list me-2"></i>รายการประเพณีทั้งหมด</h3>
                
                <?php
                $sql = "SELECT * FROM traditions ORDER BY id DESC";
                $result = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($result) > 0):
                ?>
                    <div>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                        <th style="width: 70px; text-align: center;">รูป</th>
                                        <th>ชื่องาน</th>
                                        <th style="width: 140px; text-align: center;">วันที่</th>
                                        <th style="width: 220px; text-align: center;">จัดการ</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <img src="<?= htmlspecialchars($row['image_url']) ?>" class="table-image" alt="Image" style="width: 50px; height: 50px;">
                                        </td>
                                        <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                                        <td style="text-align: center;">
                                            <?php if(!empty($row['event_date'])): ?>
                                                <span class="badge badge-date" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; font-weight: normal;"><?= htmlspecialchars($row['event_date']) ?></span>
                                            <?php else: ?>
                                                <span style="color: #999;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="actions-cell">
                                            <div class="action-buttons">
                                                <a href="admin_tradition_manage.php?edit=<?= $row['id'] ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i> แก้ไข</a>
                                                <a href="admin_tradition_manage.php?delete=<?= $row['id'] ?>" class="btn-action btn-delete" onclick="return confirm('ลบประเพณีนี้?')"><i class="fas fa-trash"></i> ลบ</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>ยังไม่มีข้อมูลประเพณี</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div style="height: 3rem;"></div>
</div>

<?php include('includes/footer.php'); ?>

<script>
// ปรับปุ่มเลือกรูป
document.querySelectorAll('.file-input-custom').forEach(label => {
    const input = label.querySelector('.file-input-hidden');
    const imageNum = label.dataset.image;
    
    input.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const item = label.closest('.image-upload-item');
            const reader = new FileReader();
            reader.onload = function(event) {
                let wrapper = item.querySelector('.image-wrapper');
                if (!wrapper) {
                    wrapper = document.createElement('div');
                    wrapper.className = 'image-wrapper';
                    const img = document.createElement('img');
                    img.className = 'image-preview';
                    wrapper.appendChild(img);
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn-remove-image';
                    removeBtn.title = 'ลบรูป';
                    removeBtn.innerHTML = '&times;';
                    wrapper.appendChild(removeBtn);
                    const fileWrapper = item.querySelector('.file-input-wrapper');
                    item.insertBefore(wrapper, fileWrapper);
                    attachRemoveHandler(removeBtn);
                }
                wrapper.querySelector('.image-preview').src = event.target.result;
                const rmBtn = wrapper.querySelector('.btn-remove-image');
                if (rmBtn) rmBtn.style.display = '';
            };
            reader.readAsDataURL(this.files[0]);
            const fileInput = label.querySelector('.file-input-hidden');
            label.innerHTML = '<i class="fas fa-check-circle" style="color: #6f42c1;"></i> ' + this.files[0].name;
            if (fileInput) label.appendChild(fileInput);
            label.style.background = '#f1edfa';
            label.style.borderColor = '#6f42c1';
            label.style.color = '#5a32a3';
        }
    });
});

function attachRemoveHandler(btn) {
    btn.addEventListener('click', function() {
        const item = this.closest('.image-upload-item');
        const slot = item.dataset.slot;
        const wrapper = item.querySelector('.image-wrapper');
        if (wrapper) wrapper.remove();
        const fileInput = item.querySelector('.file-input-hidden');
        if (fileInput) fileInput.value = '';
        const existingField = document.querySelector('input[name="existing_image_' + slot + '"]');
        if (existingField) existingField.value = '';
        const label = item.querySelector('.file-input-custom');
        if (label) {
            const fi = label.querySelector('.file-input-hidden');
            label.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> เลือกรูป';
            if (fi) label.appendChild(fi);
            label.style.background = '';
            label.style.borderColor = '';
            label.style.color = '';
        }
    });
}

document.querySelectorAll('.btn-remove-image').forEach(attachRemoveHandler);
</script>
