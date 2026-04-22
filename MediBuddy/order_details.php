<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php');
$user_id = $_SESSION['user_id'];
if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit();
}
$order_id = (int)$_GET['id'];
$orderQuery = mysqli_query($conn, 
    "SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id'"
);
if(mysqli_num_rows($orderQuery) == 0){
    header("Location: orders.php");
    exit();
}
$order = mysqli_fetch_assoc($orderQuery);
$itemsQuery = mysqli_query($conn, 
    "SELECT oi.*, m.name, m.image 
     FROM order_items oi
     JOIN medicines m ON oi.medicine_id = m.medicine_id
     WHERE oi.order_id = '$order_id'"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #MB-<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); font-family: 'Segoe UI', sans-serif; min-height: 100vh; }
        .sidebar { 
            width: 260px; height: 100vh; background: #1a233a; 
            position: fixed; left: 0; top: 0; color: #fff; padding-top: 20px; z-index: 1000;
        }
        .sidebar a { display: block; padding: 15px 20px; color: #adb5bd; text-decoration: none; }
        .sidebar a.active { background: #252d45; color: #fff; border-left: 4px solid #007bff; }
        .main-content { margin-left: 260px; padding: 40px; }
        .detail-card { background: #fff; border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .med-thumb { width: 50px; height: 50px; object-fit: contain; background: #f8f9fa; border-radius: 8px; padding: 5px; }
        @media print {
            .sidebar, .btn, .alert, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
            .detail-card { box-shadow: none !important; border: 1px solid #eee !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body>
<div class="sidebar no-print">
    <h3 style="font-family: cursive; padding-left: 20px;">MediCart</h3>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="medicines.php"><i class="bi bi-capsule me-2"></i> Shop Medicines</a>
    <a href="cart.php"><i class="bi bi-cart3 me-2"></i> My Cart</a>
    <a href="orders.php" class="active"><i class="bi bi-clock-history me-2"></i> Order History</a>
    <a href="logout.php" class="mt-5 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
<div class="main-content">
    <div class="mb-4 no-print">
        <a href="orders.php" class="text-decoration-none text-muted small fw-bold">
            <i class="bi bi-arrow-left"></i> BACK TO ORDERS
        </a>
        <h2 class="fw-bold mt-2" style="font-family: cursive;">Order Invoice</h2>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="detail-card overflow-hidden">
                <div class="bg-primary p-4 text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold">Order #MB-<?php echo $order['order_id']; ?></h4>
                        <small>Date: <?php echo date('d M, Y', strtotime($order['order_date'])); ?></small>
                    </div>
                    <span class="badge bg-white text-primary rounded-pill fw-bold px-3 py-2">
                        <?php echo strtoupper($order['status']); ?>
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Medicine Name</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $medicine_subtotal = 0;
                            while($item = mysqli_fetch_assoc($itemsQuery)): 
                                $row_total = $item['price'] * $item['quantity'];
                                $medicine_subtotal += $row_total;
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="uploads/<?php echo $item['image']; ?>" class="med-thumb me-3">
                                        <span class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td>৳ <?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td class="text-end pe-4 fw-bold">৳ <?php echo number_format($row_total, 2); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="detail-card p-4">
                <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-geo-alt me-2"></i> Shipping Details</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Delivery Address</p>
                        <p class="fw-bold text-dark"><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Contact Number</p>
                        <p class="fw-bold text-dark"><?php echo htmlspecialchars($order['phone']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="detail-card p-4">
                <h5 class="fw-bold mb-4" style="font-family: cursive;">Payment Summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment Method</span>
                    <span class="fw-bold"><?php echo $order['payment_method']; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Medicine Total</span>
                    <span class="fw-bold">৳ <?php echo number_format($medicine_subtotal, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Delivery Fee</span>
                    <span class="text-success fw-bold">+ ৳ 50.00</span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Grand Total</h5>
                    <h4 class="fw-bold text-primary mb-0">
                        ৳ <?php echo number_format($medicine_subtotal + 50, 2); ?>
                    </h4>
                </div>
                <div class="mt-4 no-print">
                    <button onclick="window.print()" class="btn btn-dark w-100 rounded-pill py-2 fw-bold">
                        <i class="bi bi-printer me-2"></i> PRINT INVOICE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>