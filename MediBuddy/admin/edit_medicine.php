<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location='manage_medicines.php';</script>";
    exit();
}
$id = intval($_GET['id']);
$success = "";
$error = "";
$query = mysqli_query($conn, "SELECT * FROM medicines WHERE medicine_id = $id");
$medicine = mysqli_fetch_assoc($query);
if (!$medicine) {
    die("<div class='container mt-5 alert alert-danger'>Medicine not found!</div>");
}
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']); 
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $image_name = $medicine['image'];
    $prescription = isset($_POST['prescription']) ? 1 : 0;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;  
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if ($medicine['image'] != 'default.png' && file_exists($target_dir . $medicine['image'])) {
                unlink($target_dir . $medicine['image']);
            }
        }
    }
    $sql = "UPDATE medicines SET 
            name = '$name', 
            category_id = '$category_id', 
            price = '$price', 
            stock = '$stock', 
            image = '$image_name',
            prescription_required = '$prescription'
            WHERE medicine_id = $id";
    if (mysqli_query($conn, $sql)) {
        $success = "Medicine updated successfully!";
        $query = mysqli_query($conn, "SELECT * FROM medicines WHERE medicine_id = $id");
        $medicine = mysqli_fetch_assoc($query);
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}
?>
<style>
    body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); min-height: 100vh; }
    .main-content {padding: 40px; }
    .edit-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        max-width: 800px;
        margin: auto;
        overflow: hidden;
    }
    .card-header-custom { background: #1a233a; color: #fff; padding: 25px; text-align: center; }
    .form-label { font-weight: 700; color: #475569; font-size: 13px; text-transform: uppercase; }
    .form-control { border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; }
    .current-img { width: 120px; height: 120px; object-fit: cover; border-radius: 15px; border: 3px solid #f1f5f9; margin-bottom: 10px; }
    .btn-update { background: #0d6efd; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; transition: 0.3s; }
    .btn-update:hover { background: #0b5ed7; transform: translateY(-2px); }
</style>
<div class="sidebar" style="width: 250px; height: 100vh; background: #1a233a; position: fixed; left: 0; top: 0;">
    <div class="p-4"><h3 style="color:#fff; font-family:cursive; font-weight:900;">MediCart</h3></div>
    <ul class="list-unstyled text-white-50">
        <li class="px-4 py-2"><a href="dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
        <li class="px-4 py-2 bg-primary rounded-end m-2 text-white"><a href="manage_medicines.php" class="text-white text-decoration-none fw-bold">Medicines</a></li>
        <li class="px-4 py-2"><a href="orders.php" class="text-white-50 text-decoration-none">Orders</a></li>
        <li class="px-4 py-2"><a href="users.php" class="text-white-50 text-decoration-none">Users</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="edit-card">
        <div class="card-header-custom">
            <h4 class="m-0">Edit Medicine: <?= htmlspecialchars($medicine['name']) ?></h4>
        </div>
        <div class="p-4 p-md-5">
            <?php if($success): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4">✅ <?= $success ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4">❌ <?= $error ?></div>
            <?php endif; ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Medicine Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($medicine['name']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category ID</label>
                        <input type="number" name="category_id" class="form-control" value="<?= $medicine['category_id'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (৳)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?= $medicine['price'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" value="<?= $medicine['stock'] ?>" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label d-block">Current Image</label>
                    <img src="../uploads/<?= !empty($medicine['image']) ? $medicine['image'] : 'default.png' ?>" class="current-img" id="preview">
                    <input type="file" name="image" class="form-control mt-2" onchange="previewImage(event)">
                </div>
                 <div class="form-check form-switch mb-4">
                <input type="checkbox" name="prescription" class="form-check-input" id="presSwitch">
                <label class="form-check-label fw-bold small" for="presSwitch">Prescription Required</label>
            </div>
                <div class="d-flex gap-3">
                    <button type="submit" name="update" class="btn btn-primary btn-update px-5 shadow">Update Medicine</button>
                    <a href="manage_medicines.php" class="btn btn-light px-4 border" style="border-radius:12px; padding:12px;">Cancel</a>
                </div>
            </form>
            <div class="text-center mt-3">
            <a href="manage_medicines.php" class="text-decoration-none small text-muted">← Back to List<a>
        </div>
        </div>
    </div>
</div>
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>