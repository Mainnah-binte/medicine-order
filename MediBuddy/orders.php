<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php');

$user_id = $_SESSION['user_id'];

// ইউজারের সব অর্ডার নিয়ে আসা (সবচেয়ে নতুনটি আগে দেখাবে)
$ordersQuery = mysqli_query($conn, 
    "SELECT * FROM orders 
     WHERE user_id = '$user_id' 
     ORDER BY order_id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History - MediBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); font-family: 'Segoe UI', sans-serif; }
        
        .sidebar { 
            width: 260px; height: 100vh; background: #1a233a; 
            position: fixed; left: 0; top: 0; color: #fff; padding-top: 20px;
        }
        .sidebar h3 { padding: 0 20px; font-weight: bold; color: #007bff; margin-bottom: 30px; }
        .sidebar a { 
            display: block; padding: 15px 20px; color: #adb5bd; 
            text-decoration: none; transition: 0.3s; border-left: 4px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #252d45; color: #fff; border-left: 4px solid #007bff; 
        }

        .main-content { margin-left: 260px; padding: 40px; }

        .order-card {
            background: #fff; border: none; border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;
        }

        .table thead { background: #f8f9fa; }
        .table th { border: none; padding: 15px; color: #4b5563; font-weight: 600; }
        .table td { vertical-align: middle; padding: 15px; border-bottom: 1px solid #f1f5f9; }

        .status-pill {
            padding: 6px 12px; border-radius: 50px; font-size: 12px; font-weight: 600;
        }
        .status-pending { background: #fff7ed; color: #c2410c; }
        .status-completed { background: #ecfdf5; color: #059669; }
        .status-cancelled { background: #fef2f2; color: #dc2626; }

        .empty-orders { text-align: center; padding: 60px 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3 style="font-family: cursive;">MediCart</h3>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="medicines.php"><i class="bi bi-capsule me-2"></i> Shop Medicines</a>
    <a href="cart.php"><i class="bi bi-cart3 me-2"></i> My Cart</a>
    <a href="orders.php" class="active"><i class="bi bi-clock-history me-2"></i> Order History</a>
    <a href="logout.php" class="mt-5 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0" style="font-family: cursive;">My Order History</h2>
        <a href="medicines.php" class="btn btn-outline-primary rounded-pill px-4">
            <i class="bi bi-plus-lg"></i> New Order
        </a>
    </div>
    <div class="order-card">
        <?php if(mysqli_num_rows($ordersQuery) > 0): ?>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = mysqli_fetch_assoc($ordersQuery)): ?>
                        <tr>
                            <td><span class="fw-bold">#MB-<?php echo $order['order_id']; ?></span></td>
                            <td class="text-secondary">
                                <?php echo date('d M, Y', strtotime($order['order_date'])); ?>
                            </td>
                            <td class="fw-bold text-dark">৳ <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <?php 
                                    $status = strtolower($order['status']);
                                    $statusClass = "status-pending"; // Default
                                    if($status == 'completed') $statusClass = "status-completed";
                                    if($status == 'cancelled') $statusClass = "status-cancelled";
                                ?>
                                <span class="status-pill <?php echo $statusClass; ?>">
                                    <i class="bi bi-dot"></i> <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="order_details.php?id=<?php echo $order['order_id']; ?>" 
                                   class="btn btn-light btn-sm rounded-pill px-3 border">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-orders">
                <i class="bi bi-bag-x display-4 text-muted"></i>
                <h4 class="text-muted mt-3">You haven't placed any orders yet.</h4>
                <a href="medicines.php" class="btn btn-primary mt-3 px-4 py-2 fw-bold">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>