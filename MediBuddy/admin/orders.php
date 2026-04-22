<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
$orders = mysqli_query($conn, "SELECT o.*, u.name as name FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.order_date DESC");
?>
<style>
    body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); min-height: 100vh; }
    .main-content { padding: 40px; }
    .search-box { width: 300px; border-radius: 12px; padding: 10px 15px; border: 1px solid #e0e0e0; outline: none; }
    .order-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
    .order-card { background: #fff; border-radius: 18px; padding: 20px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.04); transition: 0.3s; position: relative; }
    .order-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    .order-id { font-weight: 800; color: #1a233a; font-size: 14px; margin-bottom: 10px; display: block; }
    .user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
    .user-avatar { width: 35px; height: 35px; background: #eef2ff; color: #0d6efd; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 14px; }
    .price-tag { font-size: 22px; font-weight: 800; color: #2dc937; }
    .order-date { font-size: 12px; color: #94a3b8; margin-top: 5px; }
    .status-badge { position: absolute; top: 20px; right: 20px; padding: 5px 12px; border-radius: 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .pending { background: #fffbeb; color: #d97706; }
    .review { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; } /* New Review Status */
    .confirm { background: #eff6ff; color: #2563eb; }
    .delivered { background: #f0fdf4; color: #16a34a; }
    .cancel { background: #fef2f2; color: #dc2626; }
    .pres-badge { font-size: 10px; background: #6366f1; color: white; padding: 2px 8px; border-radius: 5px; margin-bottom: 10px; display: inline-block; }
    .btn-update { margin-top: 20px; background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; font-weight: 600; width: 100%; padding: 10px; border-radius: 12px; transition: 0.3s; }
    .btn-update:hover { background: #0d6efd; color: #fff; border-color: #0d6efd; }
</style>
<div class="sidebar" style="width: 250px; height: 100vh; background: #1a233a; position: fixed; left: 0; top: 0;">
    <div class="p-4"><h3 style="color:#fff; font-family:cursive; font-weight:900;">MediCart</h3></div>
    <ul class="list-unstyled">
        <li class="px-4 py-2"><a href="dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
        <li class="px-4 py-2"><a href="manage_medicines.php" class="text-white-50 text-decoration-none">Medicines</a></li>
        <li class="px-4 py-2 bg-primary rounded-end m-2"><a href="orders.php" class="text-white text-decoration-none fw-bold">Orders</a></li>
        <li class="px-4 py-2"><a href="users.php" class="text-white-50 text-decoration-none">Users</a></li>
        <li class="px-4 py-2 mt-5"><a href="logout.php" class="text-danger text-decoration-none">Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 style="font-weight: 900; color: #1a233a; margin:0; font-family: cursive">Order <span class="text-primary">Management</span></h2>
            <p class="text-muted m-0">Review prescriptions and update bills</p>
        </div>
        <input type="text" id="search" class="search-box" placeholder=" Search Order ID or Name...">
    </div>
    <div class="order-grid" id="orderGrid">
        <?php while($o = mysqli_fetch_assoc($orders)) { 
            $db_status = strtolower(trim($o['status']));
            $status_class = 'pending';        
            if($db_status == 'awaiting review') { $status_class = 'review'; }
            elseif($db_status == 'completed' || $db_status == 'delivered') { $status_class = 'delivered'; }
            elseif($db_status == 'confirmed' || $db_status == 'confirm') { $status_class = 'confirm'; }
            elseif($db_status == 'cancelled' || $db_status == 'cancel') { $status_class = 'cancel'; }
        ?>
        <div class="order-card">
            <span class="status-badge <?= $status_class ?>">
                <?= $o['status'] ?>
            </span>
            <span class="order-id">#MB-<?= $o['order_id'] ?></span>
            <?php if(!empty($o['prescription_file'])): ?>
                <span class="pres-badge"><i class="bi bi-file-earmark-medical"></i> Prescription Attached</span>
            <?php endif; ?>
            <div class="user-info">
                <div class="user-avatar"><?= strtoupper(substr($o['name'], 0, 1)) ?></div>
                <div>
                    <div class="fw-bold text-dark"><?= htmlspecialchars($o['name']) ?></div>
                    <div class="text-muted small">Phone: <?= $o['phone'] ?></div>
                </div>
            </div>
            <div class="price-tag">
                ৳ <?= number_format($o['total_amount'], 2) ?>
                <?php if($o['total_amount'] == 0): ?>
                    <small class="text-danger" style="font-size: 10px;">(Price Pending)</small>
                <?php endif; ?>
            </div>
            <div class="order-date">
                <i class="bi bi-clock"></i> <?= date("d M Y, h:i A", strtotime($o['order_date'])) ?>
            </div>
            <?php if($db_status == 'awaiting review'): ?>
                <a href="admin_review_order.php?id=<?= $o['order_id'] ?>" class="btn btn-update btn-primary text-gray text-decoration-none text-center d-block">
                    <i class="bi bi-calculator"></i> Review & Add Bill
                </a>
            <?php else: ?>
                <a href="update_order.php?id=<?= $o['order_id'] ?>" class="btn btn-update text-decoration-none text-center d-block">
                    Manage Order
                </a>
            <?php endif; ?>
        </div>
        <?php } ?>
    </div>
</div>
<script>
    document.getElementById("search").addEventListener("keyup", function () {
        let value = this.value.toLowerCase();
        let cards = document.querySelectorAll(".order-card");
        cards.forEach(card => {
            card.style.display = card.innerText.toLowerCase().includes(value) ? "block" : "none";
        });
    });
</script>