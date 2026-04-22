<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
if (!isset($_GET['id'])) {
    die("Order ID missing!");
}
$order_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.user_id WHERE o.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Order not found!");
}
$order = $result->fetch_assoc();
$success = "";
$error = "";
if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $new_amount = floatval($_POST['total_amount']);
    $update = $conn->prepare("UPDATE orders SET status = ?, total_amount = ? WHERE order_id = ?");
    $update->bind_param("sdi", $status, $new_amount, $order_id);
    if ($update->execute()) {
        $success = "Order #$order_id updated successfully!";
        $order['status'] = $status;
        $order['total_amount'] = $new_amount;
    } else {
        $error = "Failed to update order!";
    }
}
?>
<style>
    body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); min-height: 100vh; }
    .main-content { padding: 40px; }
    .update-card { background: #fff; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 550px; margin: 0 auto; overflow: hidden; }
    .card-header-custom { background: #1a233a; padding: 25px; color: #fff; text-align: center; }
    .form-label { font-weight: 700; color: #475569; font-size: 13px; text-transform: uppercase; margin-bottom: 8px; }
    .form-select, .form-control { border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; }
    .prescription-preview { background: #f8fbff; border: 2px dashed #0d6efd; border-radius: 15px; padding: 15px; text-align: center; margin-bottom: 20px; }
    .pres-img { max-width: 100%; border-radius: 10px; height: 150px; object-fit: cover; cursor: pointer; }
    .btn-update { background: #0d6efd; border: none; padding: 12px; border-radius: 12px; font-weight: 700; transition: 0.3s; color: white; }
    .btn-update:hover { background: #0b5ed7; transform: translateY(-2px); }
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
    <div class="update-card">
        <div class="card-header-custom">
            <h4 class="m-0">Manage Order #<?= $order_id ?></h4>
            <p class="text-white-50 small m-0 mt-1">Customer: <?= htmlspecialchars($order['customer_name']) ?></p>
        </div>
        <div class="p-4 p-md-5">
            <?php if ($success) { echo "<div class='alert alert-success border-0 small shadow-sm'>$success</div>"; } ?>
            <?php if ($error) { echo "<div class='alert alert-danger border-0 small shadow-sm'>$error</div>"; } ?>
            <?php if(!empty($order['prescription_file'])): ?>
            <div class="prescription-preview">
                <label class="form-label d-block text-primary">Attached Prescription</label>
                <a href="../uploads/prescriptions/<?= $order['prescription_file'] ?>" target="_blank">
                    <img src="../uploads/prescriptions/<?= $order['prescription_file'] ?>" class="pres-img shadow-sm" title="Click to view full image">
                </a>
                <small class="text-muted d-block mt-2">Click image to enlarge</small>
            </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Update Status</label>
                    <select name="status" class="form-select shadow-sm" required>
                        <option value="Awaiting Review" <?= ($order['status']=="Awaiting Review") ? "selected" : "" ?>>Awaiting Review</option>
                        <option value="Confirmed" <?= ($order['status']=="Confirmed") ? "selected" : "" ?>>Confirmed</option>
                        <option value="Processing" <?= ($order['status']=="Processing") ? "selected" : "" ?>>Processing</option>
                        <option value="Delivered" <?= ($order['status']=="Delivered") ? "selected" : "" ?>>Delivered</option>
                        <option value="Cancelled" <?= ($order['status']=="Cancelled") ? "selected" : "" ?>>Cancelled</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">Total Amount (৳)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">৳</span>
                        <input type="number" name="total_amount" class="form-control border-start-0 shadow-sm" 
                               value="<?= $order['total_amount'] ?>" step="0.01" required
                               style="font-weight: bold; color: #16a34a;">
                    </div>
                    <?php if($order['total_amount'] == 0): ?>
                        <small class="text-danger">* amount </small>
                    <?php endif; ?>
                </div>

                <button type="submit" name="update" class="btn btn-update w-100 mb-3 shadow">
                    Update Order Details
                </button> 
                <div class="text-center">
                    <a href="orders.php" class="text-decoration-none small text-muted">
                        <i class="bi bi-arrow-left"></i> Back to Orders List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>