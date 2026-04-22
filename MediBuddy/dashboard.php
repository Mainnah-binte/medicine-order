<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - MediBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {font-family: 'Segoe UI', sans-serif; overflow-x: hidden; background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
        min-height: 100vh;}
        .sidebar { 
            width: 260px; height: 100vh; background: #1a233a; 
            position: fixed; left: 0; top: 0; color: #fff; padding-top: 20px;
            z-index: 1000;
        }
        .sidebar h3 { padding: 0 20px; font-weight: bold; color: #007bff; margin-bottom: 30px; }
        .sidebar a { 
            display: block; padding: 15px 20px; color: #adb5bd; 
            text-decoration: none; transition: 0.3s; border-left: 4px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #252d45; color: #fff; border-left: 4px solid #007bff; 
        }
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        .dashboard-card {
            background: #fff; border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 30px; margin-bottom: 30px;
            border: none;
        }
        .stat-box { background: #f8f9fa; border-radius: 10px; padding: 20px; border: 1px solid #eee; }
        .table thead { background: #f8f9fa; }
        .table th { border: none; color: #666; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
        .badge-pending { background-color: #fff4e5; color: #ff9800; border-radius: 20px; padding: 5px 12px; }
        .badge-delivered { background-color: #e8f5e9; color: #4caf50; border-radius: 20px; padding: 5px 12px; }
        .badge-cancelled { background-color: #ffebee; color: #f44336; border-radius: 20px; padding: 5px 12px; }
        .badge-processing { background-color: #e3f2fd; color: #2196f3; border-radius: 20px; padding: 5px 12px; }
        .pres-badge { 
            font-size: 10px; color: #d32f2f; font-weight: bold; 
            display: block; margin-top: 5px; 
        }
    </style>
</head>
<body>
<?php
$user_id = intval($_SESSION['user_id']);
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($user_query);
$orders_query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_id DESC");
$spent_query = mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM orders WHERE user_id = $user_id AND status = 'Delivered'");
$spent_data = mysqli_fetch_assoc($spent_query);
$totalSpent = $spent_data['total'] ?? 0;
?>
<div class="sidebar">
    <h3 style="font-family: cursive;">MediCart</h3>
    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="medicines.php"><i class="bi bi-capsule me-2"></i> Shop Medicines</a>
    <a href="cart.php"><i class="bi bi-cart3 me-2"></i> My Cart</a>
    <a href="orders.php"><i class="bi bi-clock-history me-2"></i> Order History</a>
    <a href="logout.php" class="mt-5 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-lg-4">
            <div class="dashboard-card text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                    <?= strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user['name']); ?></h4>
                <p class="text-muted small mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="text-start mb-4">
                    <div class="mb-2">
                        <small class="text-muted d-block">Phone Number</small>
                        <span class="fw-semibold"><?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></span>
                    </div>
                </div>
                <div class="stat-box mb-4">
                    <small class="text-muted d-block mb-1">Total Spend</small>
                    <h3 class="fw-bold text-success mb-0">৳ <?php echo number_format($totalSpent, 2); ?></h3>
                </div>
                <div class="d-grid gap-2">
                    <a href="medicines.php" class="btn btn-primary py-2 fw-bold">Order New Medicine</a>
                    <a href="order_via_prescription.php" class="btn btn-info px-4 fw-bold">Upload Prescription</a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0">Recent Purchase History</h5>
                    <span class="badge bg-light text-dark border rounded-pill px-3"><?php echo mysqli_num_rows($orders_query); ?> Orders</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Order Details</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($orders_query) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($orders_query)): 
                                    $status = strtolower($row['status']);
                                    $badgeClass = "badge-pending";
                                    if($status == 'delivered') $badgeClass = "badge-delivered";
                                    if($status == 'cancelled') $badgeClass = "badge-cancelled";
                                    if($status == 'processing') $badgeClass = "badge-processing";
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark d-block">#ORD-<?php echo $row['order_id']; ?></span>
                                        <?php if(isset($row['prescription_required']) && $row['prescription_required'] == 1): ?>
                                            <span class="pres-badge"><i class="bi bi-file-earmark-medical"></i> PRESCRIPTION REQ.</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-dark">৳ <?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo date('d M, Y', strtotime($row['order_date'])); ?></small>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <p class="text-muted">No orders found in your history.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>