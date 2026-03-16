<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_username'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'เฉพาะผู้ดูแลระบบเท่านั้น!', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'login_admin.php'; }); }, 100);</script>";
    exit();
}

$current_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'superadmin';
if ($current_role !== 'superadmin' && $current_role !== 'admin_product') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สิทธิ์ถูกปฏิเสธ!', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'admin_dashboard.php'; }); }, 100);</script>";
    exit();
}

include('includes/db_config.php');

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM otop_products WHERE id = $id");
    echo "<script>setTimeout(function() { window.location = 'admin_product_manage.php'; }, 500);</script>";
}

// Helper function
function upload_image($file_input_name, $existing_image = '')
{
    $img = $existing_image;
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
        $file_extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = "uploads/otop/" . $new_filename;
        if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
            $img = $target_file;
        }
    }
    return $img;
}

// Add
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

    $sql = "INSERT INTO otop_products (name, price, description, image_url, image_url_2, image_url_3, image_url_4, tag, tag_color) 
            VALUES ('$name', '$price', '$desc', '$image_url', '$image_url_2', '$image_url_3', '$image_url_4', '$tag', '$tag_color')";
    mysqli_query($conn, $sql);
    echo "<script>setTimeout(function() { window.location = 'admin_product_manage.php'; }, 500);</script>";
}

// Update
if (isset($_POST['update_product'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $tag = mysqli_real_escape_string($conn, $_POST['tag']);
    $tag_color = mysqli_real_escape_string($conn, $_POST['tag_color']);

    $image_url = upload_image('image_file', mysqli_real_escape_string($conn, $_POST['existing_image']));
    $image_url_2 = upload_image('image_file_2', mysqli_real_escape_string($conn, $_POST['existing_image_2']));
    $image_url_3 = upload_image('image_file_3', mysqli_real_escape_string($conn, $_POST['existing_image_3']));
    $image_url_4 = upload_image('image_file_4', mysqli_real_escape_string($conn, $_POST['existing_image_4']));

    $sql = "UPDATE otop_products SET 
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
    mysqli_query($conn, $sql);
    echo "<script>setTimeout(function() { window.location = 'admin_product_manage.php'; }, 500);</script>";
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM otop_products WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result);
}

include('includes/header.php');
?>

<style>
    .admin-header {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
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
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border-top: 5px solid #ff6b35;
    }

    .form-section h3 {
        color: #ff6b35;
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
        color: #ff6b35;
    }

    .form-control,
    .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ff6b35;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.1);
    }

    /* ปรับ input file ให้อ่านชัด */
    input[type="file"].form-control {
        padding: 0.5rem;
        color: #ff6b35;
        font-weight: 600;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
        font-family: 'Sarabun', sans-serif;
    }

    .image-preview {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e0e0e0;
        margin-right: 0.5rem;
    }

    .image-upload-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .image-upload-item {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 1rem;
        background: #f9f9f9;
        text-align: center;
        overflow: hidden;
        min-width: 0;
        position: relative;
    }

    .image-upload-item .image-wrapper {
        position: relative;
        display: block;
        padding: 4px;
        padding-bottom: 4px;
        margin-bottom: 0.5rem;
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

    .image-upload-item .image-wrapper img.image-box {
        position: static;
        width: 100%;
        aspect-ratio: 1;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
        display: block;
    }

    .file-input-custom {
        display: block;
        width: 100%;
        padding: 0.6rem 0.5rem;
        background: #fff;
        border: 2px dashed #ff6b35;
        border-radius: 8px;
        color: #ff6b35;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-input-hidden {
        display: none;
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

    .price-input {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .btn-submit {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
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

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
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
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border-top: 5px solid #0d6efd;
        overflow-x: hidden;
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
        table-layout: fixed;
        min-width: 0;
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
        border-radius: 6px;
        object-fit: cover;
    }

    .price-badge {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        display: inline-block;
    }

    .btn-action {
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        margin-right: 0.3rem;
        white-space: nowrap;
        display: inline-block;
    }

    @media (max-width: 1024px) {
        .two-column {
            grid-template-columns: 1fr;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .btn-action {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 768px) {
        .image-upload-group {
            grid-template-columns: repeat(2, 1fr);
        }

        .admin-table {
            min-width: 750px;
            font-size: 0.8rem;
        }

        .admin-table th,
        .admin-table td {
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

    .btn-edit {
        background: #0dcaf0;
        color: white;
    }

    .btn-edit:hover {
        background: #0ba4c7;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
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

    @media (max-width: 768px) {
        .image-upload-group {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="admin-header">
    <div class="admin-container">
        <h1 class="mb-2"><i class="fas fa-shopping-basket me-2"></i>จัดการสินค้า OTOP</h1>
        <p class="mb-0 text-white-50">เพิ่ม แก้ไข และลบข้อมูลสินค้า OTOP</p>
    </div>
</div>

<div class="container admin-container">
    <div class="row">
        <div class="col-lg-5">
            <div class="form-section">
                <h3><i
                        class="fas fa-<?= $edit_data ? 'edit' : 'plus' ?> me-2"></i><?= $edit_data ? 'แก้ไขสินค้า' : 'เพิ่มสินค้าใหม่' ?>
                </h3>

                <form method="POST" enctype="multipart/form-data">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                        <input type="hidden" name="existing_image"
                            value="<?= htmlspecialchars($edit_data['image_url'] ?? '') ?>">
                        <input type="hidden" name="existing_image_2"
                            value="<?= htmlspecialchars($edit_data['image_url_2'] ?? '') ?>">
                        <input type="hidden" name="existing_image_3"
                            value="<?= htmlspecialchars($edit_data['image_url_3'] ?? '') ?>">
                        <input type="hidden" name="existing_image_4"
                            value="<?= htmlspecialchars($edit_data['image_url_4'] ?? '') ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i>ชื่อสินค้า</label>
                        <input type="text" name="name" class="form-control"
                            value="<?= $edit_data ? htmlspecialchars($edit_data['name']) : '' ?>"
                            placeholder="ชื่อสินค้า OTOP" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i>ราคา (บาท)</label>
                        <input type="number" name="price" class="form-control price-input"
                            value="<?= $edit_data ? htmlspecialchars($edit_data['price']) : '' ?>" placeholder="0.00"
                            step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i>คำอธิบายสินค้า</label>
                        <textarea name="description" class="form-control" placeholder="อธิบายเกี่ยวกับสินค้า..."
                            required><?= $edit_data ? htmlspecialchars($edit_data['description'] ?? '') : '' ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-images"></i>รูปภาพ (สูงสุด 4 รูป)</label>
                        <small
                            style="color: #999; display: block; margin-bottom: 1rem;">รูปภาพจะปรับขนาดให้เท่ากันโดยอัตโนมัติ</small>

                        <div class="image-upload-group">
                            <?php for ($i = 1; $i <= 4; $i++):
                                $img_key = $i === 1 ? 'image_url' : 'image_url_' . $i;
                                $img_src = $edit_data ? ($edit_data[$img_key] ?? '') : '';
                                ?>
                                <div class="image-upload-item" data-slot="<?= $i ?>">
                                    <div
                                        style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem; display: block; font-weight: 600;">
                                        รูปที่ <?= $i ?></div>
                                    <?php if ($img_src): ?>
                                        <div class="image-wrapper">
                                            <img src="<?= htmlspecialchars($img_src) ?>" class="image-box">
                                            <button type="button" class="btn-remove-image" title="ลบรูป">&times;</button>
                                        </div>
                                    <?php endif; ?>
                                    <label class="file-input-custom" data-image="<?= $i ?>">
                                        <i class="fas fa-cloud-upload-alt"></i> เลือกรูป
                                        <input type="file" name="<?= $i === 1 ? 'image_file' : 'image_file_' . $i ?>" class="file-input-hidden"
                                            accept="image/*" <?= ($i === 1 && !$edit_data) ? 'required' : '' ?>>
                                    </label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-label"></i>ป้ายกำกับ</label>
                                <input type="text" name="tag" class="form-control" placeholder="เช่น ขายดี, ยอดนิยม"
                                    value="<?= $edit_data ? htmlspecialchars($edit_data['tag']) : '' ?>" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-palette"></i>สีป้าย</label>
                                <select name="tag_color" class="form-select" required>
                                    <option value="bg-danger" <?= ($edit_data && $edit_data['tag_color'] == 'bg-danger') ? 'selected' : '' ?>>🔴 แดง</option>
                                    <option value="bg-warning text-dark" <?= ($edit_data && $edit_data['tag_color'] == 'bg-warning text-dark') ? 'selected' : '' ?>>🟡 เหลือง
                                    </option>
                                    <option value="bg-success" <?= ($edit_data && $edit_data['tag_color'] == 'bg-success') ? 'selected' : '' ?>>🟢เขียว</option>
                                    <option value="bg-info text-dark" <?= ($edit_data && $edit_data['tag_color'] == 'bg-info text-dark') ? 'selected' : '' ?>>🔵 ฟ้า
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="<?= $edit_data ? 'update_product' : 'add_product' ?>"
                        class="btn-submit">
                        <i
                            class="fas fa-<?= $edit_data ? 'save' : 'plus' ?> me-2"></i><?= $edit_data ? 'บันทึกการแก้ไข' : 'เพิ่มข้อมูล' ?>
                    </button>

                    <?php if ($edit_data): ?>
                        <a href="admin_product_manage.php" class="btn-cancel"
                            style="text-decoration: none; display: inline-block;">ยกเลิก</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="table-section">
                <h3><i class="fas fa-list me-2"></i>รายการสินค้าทั้งหมด</h3>

                <?php
                $sql = "SELECT * FROM otop_products ORDER BY id DESC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0):
                    ?>
                    <div>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 80px; text-align: center;">รูป</th>
                                    <th>ชื่อสินค้า</th>
                                    <th style="width: 120px; text-align: center;">ราคา</th>
                                    <th style="width: 120px; text-align: center;">ป้าย</th>
                                    <th style="width: 160px; text-align: center;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <img src="<?= htmlspecialchars($row['image_url']) ?>" class="table-image">
                                        </td>
                                        <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                                        <td style="text-align: center;"><span class="price-badge"
                                                style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">฿<?= number_format($row['price'], 2) ?></span>
                                        </td>
                                        <td style="text-align: center;"><span
                                                class="badge <?= htmlspecialchars($row['tag_color']) ?>"
                                                style="padding: 0.5rem 0.8rem; font-size: 0.85rem; border-radius: 50px; font-weight: normal;"><?= htmlspecialchars($row['tag']) ?></span>
                                        </td>
                                        <td class="actions-cell">
                                            <div class="action-buttons">
                                                <a href="admin_product_manage.php?edit=<?= $row['id'] ?>"
                                                    class="btn-action btn-edit"><i class="fas fa-edit"></i> แก้ไข</a>
                                                <a href="admin_product_manage.php?delete=<?= $row['id'] ?>"
                                                    class="btn-action btn-delete" onclick="return confirm('ลบสินค้านี้?')"><i
                                                        class="fas fa-trash"></i> ลบ</a>
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
                        <p>ยังไม่มีข้อมูลสินค้า OTOP</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div style="height: 3rem;"></div>
</div>

<?php include('includes/footer.php'); ?>

<script>
    // file input preview for product admin
    document.querySelectorAll('.file-input-custom').forEach(label => {
        const input = label.querySelector('.file-input-hidden');
        if (!input) return;
        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const item = label.closest('.image-upload-item');
                const reader = new FileReader();
                reader.onload = function (e) {
                    let wrapper = item.querySelector('.image-wrapper');
                    if (!wrapper) {
                        wrapper = document.createElement('div');
                        wrapper.className = 'image-wrapper';
                        const img = document.createElement('img');
                        img.className = 'image-box';
                        wrapper.appendChild(img);
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn-remove-image';
                        removeBtn.title = 'ลบรูป';
                        removeBtn.innerHTML = '&times;';
                        wrapper.appendChild(removeBtn);
                        item.insertBefore(wrapper, label);
                        attachRemoveHandler(removeBtn);
                    }
                    wrapper.querySelector('.image-box').src = e.target.result;
                    // Show remove button
                    const rmBtn = wrapper.querySelector('.btn-remove-image');
                    if (rmBtn) rmBtn.style.display = '';
                };
                reader.readAsDataURL(this.files[0]);
                const fileInput = label.querySelector('.file-input-hidden');
                label.innerHTML = '<i class="fas fa-check-circle" style="color: #4caf50;"></i> ' + this.files[0].name;
                if (fileInput) label.appendChild(fileInput);
                label.style.background = '#fff7ed';
                label.style.borderColor = '#ff6b35';
                label.style.color = '#b8431d';
            }
        });
    });

    // Remove image handler
    function attachRemoveHandler(btn) {
        btn.addEventListener('click', function() {
            const item = this.closest('.image-upload-item');
            const slot = item.dataset.slot;
            // Remove image wrapper
            const wrapper = item.querySelector('.image-wrapper');
            if (wrapper) wrapper.remove();
            // Clear file input
            const fileInput = item.querySelector('.file-input-hidden');
            if (fileInput) fileInput.value = '';
            // Clear existing image hidden field
            const existingField = slot == 1
                ? document.querySelector('input[name="existing_image"]')
                : document.querySelector('input[name="existing_image_' + slot + '"]');
            if (existingField) existingField.value = '';
            // Reset label
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

    // Attach to existing remove buttons
    document.querySelectorAll('.btn-remove-image').forEach(attachRemoveHandler);
</script>