<?php
include('../includes/config.php');
include('../includes/db.php');
if(!isset($_GET['id'])){
    header("Location: admin_dashboard.php");
    exit();
}
$order_id = (int)$_GET['id'];
if(isset($_POST['update_bill'])){
    $bill_amount = mysqli_real_escape_string($conn, $_POST['bill_amount']);
    $status = "Confirmed";
    $updateQuery = "UPDATE orders SET total_amount = '$bill_amount', status = '$status' WHERE order_id = '$order_id'";
    if(mysqli_query($conn, $updateQuery)){
        echo "<script>alert('Bill Updated & Order Confirmed!'); window.location='orders.php';</script>";
    }
}
$orderQuery = mysqli_query($conn, "SELECT o.*, u.name FROM orders o JOIN users u ON o.user_id = u.user_id WHERE o.order_id = '$order_id'");
$order = mysqli_fetch_assoc($orderQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Prescription - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); }
        .prescription-img { max-width: 100%; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); cursor: zoom-in; }
        .admin-card { border: none; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-7">
            <div class="card admin-card p-4">
                <h5 class="fw-bold mb-3" style="font-family: cursive;"><i class="bi bi-eye"></i> View Prescription</h5>
                <?php if($order['prescription_file']): ?>
                    <a href="../uploads/prescriptions/<?php echo $order['prescription_file']; ?>" target="_blank">
                        <img src="../uploads/prescriptions/<?php echo $order['prescription_file']; ?>" class="prescription-img" alt="Prescription">
                    </a>
                    <p class="text-muted mt-2 small text-center"><i class="bi bi-info-circle"></i> Click on the image to view full size</p>
                <?php else: ?>
                    <div class="alert alert-warning">No prescription uploaded for this order.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card admin-card p-4">
                <h4 class="fw-bold mb-4" style="color: #007bff; font-family:cursive">Order Review</h4>
                <div class="mb-3">
                    <p class="mb-1 text-muted small">Customer Name</p>
                    <p class="fw-bold"><?php echo htmlspecialchars($order['name']); ?></p>
                </div>
                <div class="mb-3">
                    <p class="mb-1 text-muted small">Delivery Address</p>
                    <p class="fw-bold text-dark"><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                </div>
                <hr>
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Calculate & Enter Total Bill (৳)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-primary text-white border-0">৳</span>
                            <input type="number" name="bill_amount" class="form-control border-primary" placeholder="0.00" step="0.01" required>
                        </div>
                        <small class="text-muted">Include medicine price + delivery fee (80 TK).</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="update_bill" class="btn btn-success btn-lg rounded-pill">
                            <i class="bi bi-check2-circle"></i> Confirm & Send Bill
                        </button>
                        <a href="orders.php" class="btn btn-light rounded-pill">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>