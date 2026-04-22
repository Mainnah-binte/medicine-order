<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php');
$user_id = $_SESSION['user_id'];
if(isset($_POST['address'], $_POST['phone'], $_POST['payment_method'])){
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
} else {
    header("Location: payment.php");
    exit();
}
$cartQuery = mysqli_query($conn, "SELECT cart_id FROM cart WHERE user_id='$user_id' LIMIT 1");
$cart = mysqli_fetch_assoc($cartQuery);
if(!$cart){
    header("Location: medicines.php");
    exit();
}
$cart_id = $cart['cart_id'];
$items = mysqli_query($conn,
    "SELECT ci.*, m.price 
     FROM cart_items ci
     JOIN medicines m ON ci.medicine_id = m.medicine_id
     WHERE ci.cart_id='$cart_id'"
);
if(mysqli_num_rows($items) == 0){
    header("Location: medicines.php");
    exit();
}
$total = 0;
$cart_data = [];
while($row = mysqli_fetch_assoc($items)){
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $cart_data[] = $row;
}
$insertOrder = "INSERT INTO orders (user_id, total_amount, status, address, phone, payment_method, order_date)
                VALUES ('$user_id', '$total', 'Pending', '$address', '$phone', '$payment_method', NOW())";
if(mysqli_query($conn, $insertOrder)){
    $order_id = mysqli_insert_id($conn);
    foreach($cart_data as $item){
        $medicine_id = $item['medicine_id'];
        $qty = $item['quantity'];
        $price = $item['price'];
        mysqli_query($conn,
            "INSERT INTO order_items (order_id, medicine_id, quantity, price)
             VALUES ('$order_id', '$medicine_id', '$qty', '$price')"
        );
    }
    mysqli_query($conn, "DELETE FROM cart_items WHERE cart_id='$cart_id'");
} else {
    die("Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success - MediBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); font-family: 'Segoe UI', sans-serif; }
        .sidebar { 
            width: 260px; height: 100vh; background: #1a233a; 
            position: fixed; left: 0; top: 0; color: #fff; padding-top: 20px;
        }
        .sidebar a { display: block; padding: 15px 20px; color: #adb5bd; text-decoration: none; }
        .sidebar a.active { background: #252d45; color: #fff; border-left: 4px solid #007bff; }
        .main-content { margin-left: 260px; padding: 60px 40px; }
        .success-card {
            max-width: 600px; margin: 0 auto; background: #fff;
            border-radius: 20px; padding: 40px; border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .success-icon {
            width: 80px; height: 80px; background: #d1e7dd;
            color: #0f5132; border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-size: 40px; margin: 0 auto 20px;
        }
        .order-id-badge {
            background: #f1f5f9; color: #475569; padding: 10px 20px;
            border-radius: 10px; font-weight: bold; font-size: 20px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h3 style="font-family: cursive; padding-left: 20px;">MediCart</h3>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="medicines.php"><i class="bi bi-capsule me-2"></i> Shop Medicines</a>
    <a href="cart.php"><i class="bi bi-cart3 me-2"></i> My Cart</a>
    <a href="orders.php"><i class="bi bi-clock-history me-2"></i> Order History</a>
    <a href="logout.php" class="mt-5 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
<div class="main-content text-center">
    <div class="success-card">
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h2 class="fw-bold text-dark" style="font-family: cursive;">Order Confirmed!</h2>
        <p class="text-muted">Thank you! Your order has been placed via <strong><?php echo $payment_method; ?></strong>.</p>
        <div class="my-4 text-start p-3 border rounded">
            <p class="mb-1 small text-muted text-uppercase fw-bold">Shipping Address:</p>
            <p class="mb-0 fw-bold"><?php echo $address; ?></p>
            <p class="mb-0 small fw-bold">Phone: <?php echo $phone; ?></p>
        </div>
        <div class="my-4">
            <span class="text-uppercase small fw-bold text-muted d-block mb-2">Order Tracking Number</span>
            <span class="order-id-badge">#MB-<?php echo $order_id; ?></span>
        </div>
        <div class="p-3 bg-light rounded-3 mb-4">
            <span class="text-muted">Total Amount</span>
            <h3 class="fw-bold text-primary mb-0">৳ <?php echo number_format($total, 2); ?></h3>
        </div>
        <div class="d-grid gap-2">
            <a href="orders.php" class="btn btn-primary btn-lg fw-bold rounded-pill">View My Orders</a>
            <a href="medicines.php" class="btn btn-outline-secondary border-0">Continue Shopping</a>
        </div>
    </div>
</div>
</body>
</html>