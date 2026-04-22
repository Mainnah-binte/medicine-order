<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
if(!isset($_GET['id'])){
    die("User ID missing!");
}
$user_id = intval($_GET['id']);
$user_res = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
if(mysqli_num_rows($user_res) == 0) {
    die("User not found!");
}
$user = mysqli_fetch_assoc($user_res);
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>
<style>
    body {
        background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
        min-height: 100vh;
    }
    .main-content {
        padding: 40px;
    }
    .profile-card {
        background: #fff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }
    .profile-header {
        background: #1a233a;
        padding: 30px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .user-avatar-large {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.1);
        border: 3px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 32px;
        font-weight: bold;
    }
    .table-container {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>
<div class="sidebar" style="width: 250px; height: 100vh; background: #1a233a; position: fixed; left: 0; top: 0;">
    <div class="p-4"><h3 style="color:#fff; font-family:cursive; font-weight:900;">MediCart</h3></div>
    <ul class="list-unstyled">
        <li class="px-4 py-2"><a href="dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
        <li class="px-4 py-2"><a href="manage_medicines.php" class="text-white-50 text-decoration-none">Medicines</a></li>
        <li class="px-4 py-2"><a href="orders.php" class="text-white-50 text-decoration-none">Orders</a></li>
        <li class="px-4 py-2 bg-primary rounded-end m-2"><a href="users.php" class="text-white text-decoration-none fw-bold">Users</a></li>
        <li class="px-4 py-2 mt-5"><a href="logout.php" class="text-danger text-decoration-none">Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="profile-card">
        <div class="profile-header">
            <div class="user-avatar-large"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
            <div>
                <h3 class="m-0 fw-bold"><?= htmlspecialchars($user['name']) ?></h3>
                <p class="m-0 text-white-50"><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="bi bi-bag-check"></i> Order History</h5>
                <a href="users.php" class="btn btn-light btn-sm rounded-pill px-3">← Back to Users</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Order ID</th>
                            <th class="border-0">Amount</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Purchase Date</th>
                            <th class="border-0 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($orders) > 0) { ?>
                            <?php while($o = mysqli_fetch_assoc($orders)) { 
                                $status = $o['status'];
                                $bg = "secondary";
                                if($status == "Pending") $bg = "warning text-dark";
                                if($status == "Processing" || $status == "Confirmed") $bg = "primary";
                                if($status == "Delivered" || $status == "Completed") $bg = "success";
                                if($status == "Cancelled") $bg = "danger";
                            ?>
                            <tr>
                                <td class="fw-bold">#ORD-<?= $o['order_id'] ?></td>
                                <td class="fw-bold text-dark">৳ <?= number_format($o['total_price'], 2) ?></td>
                                <td>
                                    <span class="badge badge-status bg-<?= $bg ?>">
                                        <?= $status ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?= date("d M Y, h:i A", strtotime($o['order_date'])) ?>
                                </td>
                                <td class="text-center">
                                    <a href="update_order.php?id=<?= $o['order_id'] ?>" class="btn btn-outline-primary btn-sm">Details</a>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                    No orders found for this customer.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
