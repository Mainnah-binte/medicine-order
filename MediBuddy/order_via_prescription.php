<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php');
$user_id = $_SESSION['user_id'];
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    if (isset($_FILES['prescription']) && $_FILES['prescription']['error'] == 0) {
        $target_dir = "uploads/prescriptions/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_ext = pathinfo($_FILES["prescription"]["name"], PATHINFO_EXTENSION);
        $file_name = "DIRECT-PRES-" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["prescription"]["tmp_name"], $target_file)) {
            $query = "INSERT INTO orders (user_id, address, phone, total_amount, status, prescription_file, payment_method, order_date) 
                      VALUES ('$user_id', '$address', '$phone', '0', 'Awaiting Review', '$file_name', 'COD', NOW())";
            if (mysqli_query($conn, $query)) {
                $message = "<div class='alert alert-success'>Prescription uploaded successfully! Admin will review and update your bill soon.</div>";
            }
        }
    } else {
        $message = "<div class='alert alert-danger'>Please select a valid prescription file.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order via Prescription - MediCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {background:linear-gradient(135deg, #ddfdfd, #f5f7fb); font-family: 'Segoe UI', sans-serif; }
        .upload-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: #fff; }
        .upload-area { border: 2px dashed #dee2e6; border-radius: 15px; padding: 30px; text-align: center; transition: 0.3s; background: #fafafa; }
        .upload-area:hover { border-color: #007bff; background: #f0f7ff; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="mb-4 text-center">
                <h2 class="fw-bold" style="font-family: cursive;">MediCart</h2>
                <p class="text-muted">Upload your prescription and we'll handle the rest!</p>
            </div>
            <div class="card upload-card p-4">
                <?php echo $message; ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Delivery Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Where should we deliver?" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Any specific note? (Optional)</label>
                        <input type="text" name="note" class="form-control" placeholder="e.g. Call before delivery">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Prescription</label>
                        <div class="upload-area">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                            <input type="file" name="prescription" class="form-control mt-2" accept="image/*,.pdf" required>
                            <small class="text-muted d-block mt-2">Accepted formats: JPG, PNG, PDF</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                        PLACE ORDER NOW
                    </button>
                </form>
            </div>
            <div class="text-center mt-4">
                <a href="dashboard.php" class="text-decoration-none text-muted small"><i class="bi bi-house"></i> Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>