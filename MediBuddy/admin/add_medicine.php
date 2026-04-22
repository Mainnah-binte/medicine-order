<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
$categories = mysqli_query($conn, "SELECT * FROM categories");
if(isset($_POST['add'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $prescription = isset($_POST['prescription']) ? 1 : 0;
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];  
    if($image){
        $new_image_name = time() . "_" . $image;
        move_uploaded_file($tmp, "../uploads/" . $new_image_name);
    } else {
        $new_image_name = "default.png";
    }
    $stmt = $conn->prepare("INSERT INTO medicines (name, brand, price, stock, category_id, prescription_required, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiiss", $name, $brand, $price, $stock, $category_id, $prescription, $new_image_name);
    if($stmt->execute()){
        header("Location: manage_medicines.php");
        exit();
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>
<style>
    body {
        background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
        min-height: 100vh;
    }
    .main-content {
        padding: 40px;
    }
    .medicine-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        max-width: 600px;
        margin: 0 auto;
    }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #e0e0e0;
        margin-bottom: 15px;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: none;
    }
    .btn-save {
        background: #0d6efd;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-save:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
    }
    .page-title {
        font-family: cursive;
        font-weight: 900;
        color: #333;
        text-align: center;
        margin-bottom: 25px;
    }
</style>
<div class="sidebar" style="width: 250px; height: 100vh; background: #1a233a; position: fixed; left: 0; top: 0;">
    <div class="p-4"><h3 style="color:#fff; font-family:cursive;">MediCart</h3></div>
    <ul class="list-unstyled">
        <li class="px-4 py-2"><a href="dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
        <li class="px-4 py-2"><a href="manage_medicines.php" class="text-white text-decoration-none fw-bold">Medicines</a></li>
        <li class="px-4 py-2"><a href="orders.php" class="text-white-50 text-decoration-none">Orders</a></li>
        <li class="px-4 py-2"><a href="logout.php" class="text-danger text-decoration-none">Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="card medicine-card p-4 p-md-5">
        <h3 class="page-title">Add New <span style="color: #0d6efd;">Medicine</span></h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-2">
                <label class="form-label small fw-bold">Medicine Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Napa Extra" required>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">Brand / Company</label>
                <input type="text" name="brand" class="form-control" placeholder="e.g. Beximco" required>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Price (৳)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Stock Quantity</label>
                    <input type="number" name="stock" class="form-control" placeholder="0" required>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php while($c = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?= $c['category_id'] ?>"><?= $c['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Medicine Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="form-check form-switch mb-4">
                <input type="checkbox" name="prescription" class="form-check-input" id="presSwitch">
                <label class="form-check-label fw-bold small" for="presSwitch">Prescription Required</label>
            </div>
            <button class="btn btn-primary btn-save w-100 shadow-sm" name="add">Save Medicine</button>
        </form>       
        <div class="text-center mt-3">
            <a href="manage_medicines.php" class="text-decoration-none small text-muted">← Back to List</a>
        </div>
    </div>
</div>