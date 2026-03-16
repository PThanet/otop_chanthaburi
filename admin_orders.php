<?php
include 'includes/header.php';
include 'includes/db_config.php';

// ตรวจสอบว่าเป็น Admin
if (!isset($_SESSION['admin_username'])) {
    header('Location: login_admin.php');
    exit;
}

// รับ parameter
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// สร้าง SQL
$where = "WHERE 1=1";
if (!empty($filter_status)) {
    $where .= " AND status = '$filter_status'";
}
if (!empty($search)) {
    $where .= " AND (order_number LIKE '%$search%' OR customer_name LIKE '%$search%' OR customer_phone LIKE '%$search%')";
}
if ($user_id > 0) {
    $where .= " AND user_id = $user_id";
}

$sql = "SELECT * FROM orders $where ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$orders = [];
while ($order = mysqli_fetch_assoc($result)) {
    $orders[] = $order;
}

// สถานะสั่ง
$order_statuses = [
    'pending' => 'รอการยืนยัน',
    'confirmed' => 'ยืนยันแล้ว',
    'processing' => 'กำลังเตรียม',
    'shipped' => 'ส่งแล้ว',
    'delivered' => 'ส่งถึงแล้ว',
    'cancelled' => 'ยกเลิก'
];
?>

<style>
    .admin-container {
        margin: 30px auto;
        max-width: 1300px;
    }

    .admin-header {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
        align-items: center;
        margin-bottom: 30px;
    }

    .admin-title h2 {
        color: #004d40;
        font-weight: bold;
        margin: 0;
    }

    .admin-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-box {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #004d40;
    }

    .stat-label {
        color: #999;
        font-size: 0.9rem;
        margin-top: 5px;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .filter-group {
        display: flex;
        gap: 10px;
    }

    .filter-group input,
    .filter-group select {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
    }

    .filter-group button {
        padding: 10px 20px;
        background: #004d40;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .filter-group button:hover {
        background: #00695c;
    }

    .orders-table {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-container {
        overflow-x: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        min-width: 0;
    }

    thead {
        background: #004d40;
        color: white;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Keep order number and customer name from forcing horizontal scroll */
    table td:nth-child(1),
    table th:nth-child(1),
    table td:nth-child(3),
    table th:nth-child(3) {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    tbody tr:hover {
        background: #f9f9f9;
    }

    .order-number {
        font-weight: bold;
        font-family: 'Courier New', monospace;
        color: #004d40;
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: bold;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #cfe2ff;
        color: #084298;
    }

    .status-processing {
        background: #e2e3e5;
        color: #41464b;
    }

    .status-shipped {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-delivered {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-small {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-view {
        background: #0dcaf0;
        color: white;
    }

    .btn-view:hover {
        background: #0ba5cd;
    }

    .btn-confirm {
        background: #198754;
        color: white;
    }

    .btn-confirm:hover {
        background: #157347;
    }

    .btn-ship {
        background: #0d6efd;
        color: white;
    }

    .btn-ship:hover {
        background: #0b5ed7;
    }

    @media (max-width: 768px) {
        .admin-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 0.9rem;
        }

        th, td {
            padding: 10px;
        }
    }
</style>

<div class="container admin-container">
    <div class="admin-header">
        <div class="admin-title">
            <h2><i class="fas fa-box me-2"></i>จัดการออเดอร์</h2>
        </div>
    </div>

    <!-- สถิติ -->
    <div class="admin-stats">
        <?php
        // นับออเดอร์แต่ละสถานะ
        $stats = [
            'pending' => 0,
            'confirmed' => 0,
            'shipped' => 0,
            'delivered' => 0
        ];

        foreach ($orders as $order) {
            if (isset($stats[$order['status']])) {
                $stats[$order['status']]++;
            }
        }
        ?>
        <div class="stat-box">
            <div class="stat-number"><?= count($orders) ?></div>
            <div class="stat-label">ทั้งหมด</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $stats['pending'] ?></div>
            <div class="stat-label">รอยืนยัน</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $stats['confirmed'] ?></div>
            <div class="stat-label">ยืนยันแล้ว</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $stats['shipped'] ?></div>
            <div class="stat-label">ส่งแล้ว</div>
        </div>
    </div>

    <!-- ตัวกรอง -->
    <div class="filter-section">
        <form method="GET" class="filter-row">
            <?php if ($user_id > 0): ?>
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <?php endif; ?>
            <div class="filter-group">
                <input type="text" name="search" placeholder="ค้นหา: เลขออเดอร์, ชื่อ, เบอร์โทร" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="filter-group">
                <select name="status">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($order_statuses as $key => $text): ?>
                        <option value="<?= $key ?>" <?= $filter_status === $key ? 'selected' : '' ?>>
                            <?= $text ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <button type="submit"><i class="fas fa-search me-1"></i>ค้นหา</button>
                <button type="button" onclick="window.location.href='admin_orders.php'" style="background: #6c757d;">รีเซ็ต</button>
            </div>
        </form>
    </div>

    <!-- ตาราง -->
    <div class="orders-table">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>เลขออเดอร์</th>
                        <th>วันที่</th>
                        <th>ลูกค้า</th>
                        <th>เบอร์โทร</th>
                        <th>จำนวน</th>
                        <th>ยอดเงิน</th>
                        <th>วิธีชำระ</th>
                        <th>สถานะ</th>
                        <th>การกระทำ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <span class="order-number"><?= htmlspecialchars($order['order_number']) ?></span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['customer_phone']) ?></td>
                            <td>
                                <?php
                                $count_sql = "SELECT COUNT(*) as cnt FROM order_items WHERE order_id = {$order['id']}";
                                $count_result = mysqli_query($conn, $count_sql);
                                $count_data = mysqli_fetch_assoc($count_result);
                                echo $count_data['cnt'] . ' รายการ';
                                ?>
                            </td>
                            <td>฿<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <?php
                                $payment_text = [
                                    'bank_transfer' => 'โอนธนาคาร',
                                    'qr_code' => 'QR Code',
                                    'cash_on_delivery' => 'COD'
                                ];
                                echo $payment_text[$order['payment_method']] ?? $order['payment_method'];
                                ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $order['status'] ?>">
                                    <?= $order_statuses[$order['status']] ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-small btn-view" onclick="window.location.href='order_confirmation.php?order_id=<?= $order['id'] ?>&order_number=<?= htmlspecialchars($order['order_number']) ?>'">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <button class="btn-small btn-confirm" onclick="updateOrderStatus(<?= $order['id'] ?>, 'confirmed')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php elseif ($order['status'] === 'confirmed'): ?>
                                        <button class="btn-small btn-ship" onclick="updateOrderStatus(<?= $order['id'] ?>, 'shipped')">
                                            <i class="fas fa-truck"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function updateOrderStatus(orderId, newStatus) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่ว่าต้องการอัปเดตสถานะออเดอร์?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'อัปเดตสถานะสำเร็จ!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            });
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
