<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php');
$user_id = $_SESSION['user_id'];
$cartQuery = mysqli_query($conn, "SELECT cart_id FROM cart WHERE user_id='$user_id' LIMIT 1");
$cart = mysqli_fetch_assoc($cartQuery);
if (!$cart) {
    header("Location: medicines.php");
    exit();
}
$cart_id = $cart['cart_id'];
$cart_items = mysqli_query($conn,
    "SELECT ci.*, m.name, m.price, m.is_prescription_required 
     FROM cart_items ci
     JOIN medicines m ON ci.medicine_id = m.medicine_id
     WHERE ci.cart_id='$cart_id'"
);
$total = 0;
$items_list = [];
$prescription_needed = false;
while($row = mysqli_fetch_assoc($cart_items)) {
    $total += ($row['price'] * $row['quantity']);
    $items_list[] = $row;
    if($row['is_prescription_required'] == 1) {
        $prescription_needed = true;
    }
}
if ($total == 0) {
    header("Location: medicines.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - MediBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); font-family: 'Segoe UI', sans-serif; }
        .payment-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .method-box {
            border: 2px solid #f1f5f9; border-radius: 12px; padding: 15px;
            cursor: pointer; transition: 0.3s; position: relative;
        }
        .method-box:hover { border-color: #007bff; background: #f8fbff; }
        .method-box input { position: absolute; opacity: 0; }
        .method-box input:checked ~ .check-icon { display: block; }
        .check-icon { 
            position: absolute; top: 10px; right: 10px; 
            color: #007bff; display: none; 
        }
        .selected-method { border-color: #007bff; background: #f8fbff; }
        .prescription-alert { border-radius: 12px; border: none; background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-4">
                <a href="cart.php" class="text-decoration-none text-muted fw-bold small">
                    <i class="bi bi-arrow-left"></i> BACK TO CART
                </a>
                <h2 class="fw-bold mt-2" style="font-family: cursive;">Checkout & Payment</h2>
            </div>
            <form action="checkout.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-7">
                        <?php if($prescription_needed): ?>
                        <div class="alert prescription-alert d-flex align-items-center mb-4 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <strong>Prescription Required!</strong><br>
                                <small>Your cart contains medicines that need a valid doctor's prescription.</small>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="card payment-card p-4 mb-4">
                            <h5 class="fw-bold mb-3">Shipping & Prescription</h5>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Delivery Location</label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Enter your full address..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Contact Number</label>
                                <input type="text" class="form-control" name="phone" placeholder="01XXXXXXXXX" required>
                            </div>
                            <div class="mt-4 p-3 border rounded-3 bg-light">
                                <label class="form-label fw-bold small">
                                    <i class="bi bi-file-earmark-medical me-1"></i> 
                                    Upload Prescription <?php echo $prescription_needed ? '<span class="text-danger">*</span>' : '(Optional)'; ?>
                                </label>
                                <input type="file" name="prescription" class="form-control" accept="image/*,.pdf" <?php echo $prescription_needed ? 'required' : ''; ?>>
                                <small class="text-muted mt-1 d-block" style="font-size: 11px;">Max file size: 2MB (JPG, PNG, PDF)</small>
                            </div>
                        </div>
                        <div class="card payment-card p-4">
                            <h5 class="fw-bold mb-3">Select Payment Method</h5>                          
                            <div class="method-box mb-3 shadow-sm selected-method" id="cod_box">
                                <input type="radio" name="payment_method" id="cod" value="COD" checked>
                                <label for="cod" class="d-flex align-items-center w-100 cursor-pointer">
                                    <i class="bi bi-truck fs-3 me-3"></i>
                                    <div>
                                        <span class="fw-bold d-block">Cash on Delivery</span>
                                        <small class="text-muted">Pay when you receive the medicines</small>
                                    </div>
                                </label>
                                <i class="bi bi-check-circle-fill check-icon" style="display: block;"></i>
                            </div>
                            <div class="method-box shadow-sm" id="bkash_box">
                                <input type="radio" name="payment_method" id="bkash" value="bKash">
                                <label for="bkash" class="d-flex align-items-center w-100">
                                    <i class="bi bi-wallet2 fs-3 me-3 text-danger"></i>
                                    <div>
                                        <span class="fw-bold d-block">Mobile Banking (bKash/Nagad)</span>
                                        <small class="text-muted">Pay online for faster delivery</small>
                                    </div>
                                </label>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card payment-card p-4">
                            <h5 class="fw-bold mb-4">Order Summary</h5>
                            <?php foreach($items_list as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted"><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                                <span class="fw-bold">৳ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </div>
                            <?php endforeach; ?>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span>৳ <?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Delivery Fee</span>
                                <span class="text-success fw-bold">৳ 80.00</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                <h5 class="fw-bold">Grand Total</h5>
                                <h5 class="fw-bold text-primary">৳ <?php echo number_format($total + 80, 2); ?></h5>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold mt-4">
                                CONFIRM ORDER <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.method-box').forEach(box => {
        box.addEventListener('click', () => {
            document.querySelectorAll('.method-box').forEach(b => {
                b.classList.remove('selected-method');
                b.querySelector('.check-icon').style.display = 'none';
            });
            box.classList.add('selected-method');
            box.querySelector('input').checked = true;
            box.querySelector('.check-icon').style.display = 'block';
        });
    });
</script>
</body>
</html>