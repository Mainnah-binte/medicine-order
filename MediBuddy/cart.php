<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php'); 
$user_id = $_SESSION['user_id'];
$cartQuery = mysqli_query($conn, "SELECT cart_id FROM cart WHERE user_id='$user_id' LIMIT 1");
$cart = mysqli_fetch_assoc($cartQuery);
if(!$cart){
    $cart_items = [];
    $cart_id = 0;
} else {
    $cart_id = $cart['cart_id'];
    $cart_items = mysqli_query($conn,
        "SELECT ci.*, m.name, m.price, m.image 
         FROM cart_items ci
         JOIN medicines m ON ci.medicine_id = m.medicine_id
         WHERE ci.cart_id='$cart_id'"
    );
}
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart - MediBuddy</title>
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
        .cart-card {
            background: #fff; border: none; border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 25px;
        }
        .table thead { background: #f8f9fa; }
        .table th { border: none; padding: 15px; font-weight: 600; color: #4b5563; }
        .table td { vertical-align: middle; padding: 15px; border-bottom: 1px solid #f1f5f9; }
        .med-img { 
            width: 60px; height: 60px; object-fit: contain; 
            background: #f8f9fa; border-radius: 8px; padding: 5px;
        }
        .empty-cart-icon { font-size: 80px; color: #cbd5e1; }
        .summary-box {
            background: #f8f9fa; padding: 20px; border-radius: 12px;
            margin-top: 20px; text-align: right;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h3 style="font-family: cursive;">MediCart</h3>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="medicines.php"><i class="bi bi-capsule me-2"></i> Shop Medicines</a>
    <a href="cart.php" class="active"><i class="bi bi-cart3 me-2"></i> My Cart</a>
    <a href="orders.php"><i class="bi bi-clock-history me-2"></i> Order History</a>
    <a href="logout.php" class="mt-5 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
<div class="main-content">
    <h2 class="fw-bold mb-4" style="font-family: cursive;">MY Cart</h2>
    <div class="cart-card">
        <?php if($cart_id == 0 || mysqli_num_rows($cart_items) == 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-cart-x empty-cart-icon"></i>
                <h4 class="text-muted mt-3">Your cart is empty!</h4>
                <p class="text-secondary">Looks like you haven't added any medicines yet.</p>
                <a href="medicines.php" class="btn btn-primary px-4 py-2 mt-2 fw-bold">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($item = mysqli_fetch_assoc($cart_items)): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="uploads/<?php echo $item['image']; ?>" class="med-img me-3">
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></span>
                                </div>
                            </td>
                            <td>৳ <?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <?php echo $item['quantity']; ?>
                                </span>
                            </td>
                            <td class="fw-bold">৳ <?php echo number_format($subtotal, 2); ?></td>
                            <td class="text-center">
                                <a href="remove_cart.php?id=<?php echo $item['id']; ?>" 
                                   class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                   onclick="return confirm('Remove this item?')">
                                    <i class="bi bi-trash me-1"></i> Remove
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="summary-box">
                <h5 class="text-muted mb-1">Total Payable</h5>
                <h2 class="fw-bold text-primary">৳ <?php echo number_format($total, 2); ?></h2>
                <hr>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="medicines.php" class="btn btn-link text-decoration-none text-secondary">
                        <i class="bi bi-arrow-left"></i> Add More Items
                    </a>
                    <a href="checkout.php" class="btn btn-success btn-lg px-5 fw-bold shadow-sm">
                        Check Out <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>