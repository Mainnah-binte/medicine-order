<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
$medicines = mysqli_query($conn, "SELECT m.*, c.name AS category_name FROM medicines m LEFT JOIN categories c ON m.category_id = c.category_id ORDER BY m.medicine_id DESC");
?>
<style>
    body {
        background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
        min-height: 100vh;
    }
    .main-content {
        padding: 30px;
    }
    .table-card {
        background: #fff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .table thead {
        background: #f8f9fa;
    }
    .table thead th {
        border: none;
        font-size: 13px;
        text-transform: uppercase;
        color: #666;
        padding: 15px;
    }
    .table tbody td {
        vertical-align: middle;
        padding: 15px;
        border-color: #f1f1f1;
    }
    .medicine-img {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    .btn-add {
        background: #0d6efd;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-add:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
    }
    .badge-pill {
        border-radius: 50px;
        padding: 5px 12px;
        font-weight: 500;
    }
</style>
<div class="sidebar" style="width: 250px; height: 100vh; background: #1a233a; position: fixed; left: 0; top: 0;">
    <div class="p-4"><h3 style="color:#fff; font-family:cursive; font-weight:900;">MediCart</h3></div>
    <ul class="list-unstyled">
        <li class="px-4 py-2"><a href="dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
        <li class="px-4 py-2 bg-primary rounded-end m-2"><a href="manage_medicines.php" class="text-white text-decoration-none fw-bold">Medicines</a></li>
        <li class="px-4 py-2"><a href="orders.php" class="text-white-50 text-decoration-none">Orders</a></li>
        <li class="px-4 py-2"><a href="users.php" class="text-white-50 text-decoration-none">Users</a></li>
        <li class="px-4 py-2 mt-5"><a href="logout.php" class="text-danger text-decoration-none">Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="font-family: cursive; font-weight: 900; color: #333;">List of <span class="text-primary">Medicines</span></h3>
        <a href="add_medicine.php" class="btn btn-primary btn-add shadow-sm">+ Add New Medicine</a>
    </div>
    <div class="card table-card p-2">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name & Brand</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Type</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($m = mysqli_fetch_assoc($medicines)) { ?>
                    <tr>
                        <td>
                            <img src="../uploads/<?= $m['image'] ?: 'default.png' ?>" class="medicine-img" alt="medicine">
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= $m['name'] ?></div>
                            <small class="text-muted"><?= $m['brand'] ?></small>
                        </td>
                        <td><span class="text-muted"><?= $m['category_name'] ?: 'N/A' ?></span></td>
                        <td class="fw-bold">৳ <?= number_format($m['price'], 2) ?></td>
                        <td>
                            <?php if($m['stock'] <= 5) { ?>
                                <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i> <?= $m['stock'] ?> (Low)</span>
                            <?php } else { ?>
                                <span class="text-dark"><?= $m['stock'] ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?= $m['prescription_required'] 
                                ? "<span class='badge bg-light text-danger border border-danger badge-pill'>Rx Only</span>" 
                                : "<span class='badge bg-light text-success border border-success badge-pill'>OTC</span>" ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="edit_medicine.php?id=<?= $m['medicine_id'] ?>" class="btn btn-outline-primary btn-sm rounded-2 me-2">Edit</a>
                                <a href="delete_medicine.php?id=<?= $m['medicine_id'] ?>" 
                                   class="btn btn-outline-danger btn-sm rounded-2" 
                                   onclick="return confirm('Are you sure you want to delete this medicine?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
