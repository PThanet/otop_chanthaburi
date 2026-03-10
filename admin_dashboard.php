<?php
// เริ่มต้น Session ก่อน
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบสิทธิ์การเข้าถึง: ถ้าไม่มี Session ของ admin_username ให้เด้งกลับไปหน้าล็อกอินแอดมิน
if (!isset($_SESSION['admin_username'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>setTimeout(function() { Swal.fire({title: 'สิทธิ์การเข้าถึงถูกปฏิเสธ! เฉพาะผู้ดูแลระบบเท่านั้น', icon: 'error', showConfirmButton: false, timer: 1500}).then(function() { window.location = 'login_admin.php'; }); }, 100);</script>";
    exit();
}

$current_admin_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'superadmin'; // Assuming superadmin if not set for safety during migration

include('includes/header.php');
include('includes/db_config.php');
?>

<div class="bg-dark text-white text-center py-4 mb-5 shadow-sm" style="border-bottom: 4px solid #dc3545;">
    <div class="container">
        <h1 class="fw-bold mb-0"><i class="fas fa-users-cog me-3"></i>ระบบจัดการข้อมูล (Admin Dashboard)</h1>
        <p class="mb-0 mt-2 text-white-50">ยินดีต้อนรับคุณ <?= htmlspecialchars($_SESSION['admin_fullname']); ?>
            (ผู้ดูแลระบบ)</p>
    </div>
</div>

<div class="container my-5">

    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-light" style="border-radius: 12px;">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center p-4">
                    <div class="mb-3 mb-md-0">
                        <h4 class="mb-1 fw-bold text-dark"><i class="fas fa-tools me-2"></i>จัดการเนื้อหาเว็บไซต์</h4>
                        <p class="text-muted mb-0 small">เมนูลัดสำหรับเพิ่ม ลบ หรือแก้ไขข้อมูลต่างๆ บนหน้าเว็บ</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($current_admin_role === 'superadmin' || $current_admin_role === 'admin_travel'): ?>
                            <a href="admin_travel.php" class="btn btn-success btn-lg fw-bold rounded-pill shadow-sm px-4">
                                <i class="fas fa-map-marked-alt me-2"></i>จัดการสถานที่ท่องเที่ยว
                            </a>
                        <?php endif; ?>

                        <?php if ($current_admin_role === 'superadmin' || $current_admin_role === 'admin_tradition'): ?>
                            <a href="admin_tradition.php"
                                class="btn btn-warning btn-lg fw-bold rounded-pill shadow-sm px-4 text-dark">
                                <i class="fas fa-calendar-alt me-2"></i>จัดการงานประเพณี
                            </a>
                        <?php endif; ?>

                        <?php if ($current_admin_role === 'superadmin' || $current_admin_role === 'admin_product'): ?>
                            <a href="admin_product.php"
                                class="btn btn-info btn-lg fw-bold rounded-pill shadow-sm px-4 text-white">
                                <i class="fas fa-shopping-basket me-2"></i>จัดการสินค้า OTOP
                            </a>
                        <?php endif; ?>

                        <a href="admin_orders.php"
                            class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm px-4 text-white">
                            <i class="fas fa-box me-2"></i>จัดการออเดอร์
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-5" style="border-top: 4px solid #dc3545 !important; border-radius: 12px;"
        id="admins-table">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h3 class="fw-bold text-danger mb-0"><i class="fas fa-user-shield me-2"></i>รายชื่อผู้ดูแลระบบ (Admins)</h3>
            <?php if ($current_admin_role === 'superadmin'): ?>
                <form method="GET" action="admin_dashboard.php#admins-table" class="d-flex w-50 justify-content-end">
                    <input type="text" name="search_admin" class="form-control rounded-pill me-2 px-3"
                        placeholder="ค้นหา: ID, User, ชื่อ..."
                        value="<?= isset($_GET['search_admin']) ? htmlspecialchars($_GET['search_admin']) : '' ?>">
                    <!-- Preserve existing search_user query parameter if any -->
                    <?php if (isset($_GET['search_user'])): ?><input type="hidden" name="search_user"
                            value="<?= htmlspecialchars($_GET['search_user']) ?>"><?php endif; ?>
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4 fw-bold">ค้นหา</button>
                    <?php if (isset($_GET['search_admin']) && $_GET['search_admin'] != ''): ?>
                        <a href="admin_dashboard.php<?= isset($_GET['search_user']) ? '?search_user=' . htmlspecialchars($_GET['search_user']) . '#admins-table' : '#admins-table' ?>"
                            class="btn btn-light border rounded-pill px-3 ms-2">ล้าง</a>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-danger">
                        <tr>
                            <th width="10%">ID</th>
                            <?php if ($current_admin_role === 'superadmin'): ?>
                                <th width="25%">ชื่อผู้ใช้งาน (Username)</th>
                            <?php endif; ?>
                            <th width="30%">ชื่อ-นามสกุล (Fullname)</th>
                            <th width="20%">ตำแหน่ง (Role)</th>
                            <?php if ($current_admin_role === 'superadmin'): ?>
                                <th width="15%">จัดการ</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $search_admin = isset($_GET['search_admin']) ? mysqli_real_escape_string($conn, trim($_GET['search_admin'])) : '';

                        if ($search_admin != '' && $current_admin_role === 'superadmin') {
                            $sql_admins = "SELECT * FROM admins WHERE id LIKE '%$search_admin%' OR username LIKE '%$search_admin%' OR fullname LIKE '%$search_admin%' ORDER BY id ASC";
                        } else {
                            $sql_admins = "SELECT * FROM admins ORDER BY id ASC";
                        }

                        $result_admins = mysqli_query($conn, $sql_admins);

                        if (mysqli_num_rows($result_admins) > 0) {
                            while ($row = mysqli_fetch_assoc($result_admins)) {

                                $role_display = "ผู้ดูแลระบบสูงสุด";
                                $role_badge = "bg-danger";

                                if (isset($row['role'])) {
                                    switch ($row['role']) {
                                        case 'admin_travel':
                                            $role_display = "แอดมินสถานที่ท่องเที่ยว";
                                            $role_badge = "bg-success";
                                            break;
                                        case 'admin_tradition':
                                            $role_display = "แอดมินงานประเพณี";
                                            $role_badge = "bg-warning text-dark";
                                            break;
                                        case 'admin_product':
                                            $role_display = "แอดมินสินค้า OTOP";
                                            $role_badge = "bg-info";
                                            break;
                                        case 'superadmin':
                                            $role_display = "ผู้ดูแลระบบสูงสุด";
                                            $role_badge = "bg-danger";
                                            break;
                                    }
                                }

                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";

                                if ($current_admin_role === 'superadmin') {
                                    echo "<td class='fw-bold'>{$row['username']}</td>";
                                }

                                echo "<td>{$row['fullname']}</td>";
                                echo "<td><span class='badge {$role_badge}'>{$role_display}</span></td>";

                                if ($current_admin_role === 'superadmin') {
                                    echo "<td>";
                                    if (isset($row['role']) && $row['role'] !== 'superadmin') {
                                        echo "<div class='d-flex justify-content-center align-items-center gap-2'>";
                                        echo "<a href='admin_demote_user.php?id={$row['id']}' class='btn btn-warning btn-sm fw-bold text-dark shadow-sm' style='border-radius: 4px; border: 1px solid #d39e00;'><i class='fas fa-level-down-alt' style='color: #000;'></i> ลดขั้นแอดมิน</a>";
                                        echo "</div>";
                                    } else {
                                        echo "<span class='text-muted small'>ไม่มีสิทธิ์จัดการ</span>";
                                    }
                                    echo "</td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-muted py-3'>ไม่พบข้อมูลผู้ดูแลระบบ</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($current_admin_role === 'superadmin'): ?>
        <div id="users-table" class="card shadow-sm border-0"
            style="border-top: 4px solid #0d6efd !important; border-radius: 12px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold text-primary mb-0"><i class="fas fa-users me-2"></i>รายชื่อผู้ใช้งานทั่วไป (Users)</h3>
                <form method="GET" action="admin_dashboard.php#users-table" class="d-flex w-50 justify-content-end">
                    <input type="text" name="search_user" class="form-control rounded-pill me-2 px-3"
                        placeholder="ค้นหา: ID, User, ชื่อ..."
                        value="<?= isset($_GET['search_user']) ? htmlspecialchars($_GET['search_user']) : '' ?>">
                    <!-- Preserve existing search_admin query parameter if any -->
                    <?php if (isset($_GET['search_admin'])): ?><input type="hidden" name="search_admin"
                            value="<?= htmlspecialchars($_GET['search_admin']) ?>"><?php endif; ?>
                    <button type="submit" class="btn btn-outline-primary rounded-pill px-4 fw-bold">ค้นหา</button>
                    <?php if (isset($_GET['search_user']) && $_GET['search_user'] != ''): ?>
                        <a href="admin_dashboard.php<?= isset($_GET['search_admin']) ? '?search_admin=' . htmlspecialchars($_GET['search_admin']) . '#users-table' : '#users-table' ?>"
                            class="btn btn-light border rounded-pill px-3 ms-2">ล้าง</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">ชื่อผู้ใช้งาน (Username)</th>
                                <th width="30%">ชื่อ-นามสกุล (Fullname)</th>
                                <th width="20%">วันที่สมัคร</th>
                                <th width="15%">จัดการ</th>
                                <th width="15%">เลื่อนขั้น</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $search_user = isset($_GET['search_user']) ? mysqli_real_escape_string($conn, trim($_GET['search_user'])) : '';

                            if ($search_user != '') {
                                $sql_users = "SELECT * FROM users WHERE id LIKE '%$search_user%' OR username LIKE '%$search_user%' OR fullname LIKE '%$search_user%' ORDER BY id ASC";
                            } else {
                                $sql_users = "SELECT * FROM users ORDER BY id ASC";
                            }

                            $result_users = mysqli_query($conn, $sql_users);

                            if (mysqli_num_rows($result_users) > 0) {
                                while ($row = mysqli_fetch_assoc($result_users)) {
                                    $created = isset($row['created_at']) ? $row['created_at'] : '-';
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td class='fw-bold text-primary'>{$row['username']}</td>";
                                    echo "<td>{$row['fullname']}</td>";
                                    echo "<td>{$created}</td>";
                                    echo "<td>";
                                    echo "<div class='d-flex justify-content-center align-items-center gap-2'>";
                                    echo "<a href='admin_edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm text-dark px-3 py-1 fw-bold' style='border-radius: 4px;'><i class='fas fa-edit'></i> แก้ไข</a>";
                                    echo "<a href='admin_delete_user.php?id={$row['id']}' class='btn btn-danger btn-sm text-white px-3 py-1 fw-bold' style='border-radius: 4px;'><i class='fas fa-trash-alt'></i> ลบ</a>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "<td>";
                                    echo "<div class='d-flex justify-content-center align-items-center gap-2'>";
                                    echo "<a href='admin_promote_user.php?id={$row['id']}' class='btn btn-success btn-sm text-white px-3 py-1 fw-bold' style='border-radius: 4px;'><i class='fas fa-level-up-alt'></i> เลื่อนขั้น</a>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-muted py-3'>ไม่พบข้อมูลผู้ใช้งาน</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php include('includes/footer.php'); ?>