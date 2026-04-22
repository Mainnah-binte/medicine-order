<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php'); 
$where = [];
if(!empty($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where[] = "m.name LIKE '%$search%'";
}
if(!empty($_GET['category'])){
    $cat = (int) $_GET['category'];
    $where[] = "m.category_id = '$cat'";
}
$whereSQL = "";
if(count($where) > 0){
    $whereSQL = "WHERE " . implode(" AND ", $where);
}
$medicines = mysqli_query($conn, 
    "SELECT m.*, c.name AS category_name 
     FROM medicines m 
     LEFT JOIN categories c ON m.category_id = c.category_id 
     $whereSQL"
);
$catQuery = mysqli_query($conn, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicines - MediBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #ddfdfd, #f5f7fb); font-family: 'Segoe UI', sans-serif; }
        .sidebar { 
            width: 260px; height: 100vh; background: #1a233a; 
            position: fixed; left: 0; top: 0; color: #fff; padding-top: 20px;
            z-index: 1000;
        }
        .sidebar h3 { padding: 0 20px; font-weight: bold; color: #007bff; margin-bottom: 30px; }
        .sidebar a { 
            display: block; padding: 15px 20px; color: #adb5bd; 
            text-decoration: none; transition: 0.3s; border-left: 4px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #252d45; color: #fff; border-left: 4px solid #007bff; 
        }
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        .search-bar {
            background: #fff; padding: 20px; border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px;
        }
        .medicine-card {
            background: #fff; border: none; border-radius: 16px;
            transition: all 0.3s ease; padding: 15px; height: 100%;
            display: flex; flex-direction: column;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .medicine-card:hover { transform: translateY(-8px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .img-container {
            width: 100%; height: 180px; display: flex; align-items: center;
            justify-content: center; background: #fff; margin-bottom: 12px;
            border-radius: 10px; overflow: hidden;
        }
        .medicine-img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .status-badge {
            font-size: 11px; font-weight: 600; padding: 4px 10px;
            border-radius: 6px; display: inline-block; margin-bottom: 8px;
        }
        .badge-otc { background: #e8f5e9; color: #2e7d32; }
        .badge-req { background: #ffebee; color: #c62828; }
        .card-info { flex-grow: 1; }
        .med-name { font-size: 18px; font-weight: 700; color: #1a233a; margin: 5px 0 2px 0; line-height: 1.2; }
        .brand-info { font-size: 13px; color: #7d8492; margin-bottom: 10px; }
        .price-tag { font-size: 20px; font-weight: 800; color: #1a233a; margin-bottom: 15px; display: block; }
        .qty-box {
            display: flex; align-items: center; border: 1px solid #e2e8f0;
            border-radius: 8px; margin-bottom: 10px; overflow: hidden;
        }
        .qty-label { background: #f1f5f9; padding: 8px 12px; font-size: 13px; color: #475569; border-right: 1px solid #e2e8f0; }
        .qty-input { border: none; width: 100%; text-align: center; padding: 6px; font-weight: 600; outline: none; }
        .btn-cart {
            background: #007bff; color: #fff; border: none; width: 100%;
            padding: 12px; border-radius: 8px; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            gap: 8px; transition: 0.2s;
        }
        .btn-cart:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="sidebar">
    <h3 style="font-family: cursive;">MediCart</h3>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="medicines.php" class="active"><i class="bi bi-capsule me-2"></i> Shop Medicines</a>
    <a href="cart.php"><i class="bi bi-cart3 me-2"></i> My Cart</a>
    <a href="orders.php"><i class="bi bi-clock-history me-2"></i> Order History</a>
    <a href="logout.php" class="mt-5 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0" style="font-family: cursive;">Available <span class="text-primary">Medicines</span></h2>
        <span class="badge bg-white text-dark shadow-sm px-3 py-2 border rounded-pill">Total: <?php echo mysqli_num_rows($medicines); ?></span>
    </div>
    <div class="search-bar">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 bg-light" 
                           placeholder="Search medicines..." value="<?php echo $_GET['search'] ?? ''; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select bg-light">
                    <option value="">All Categories</option>
                    <?php 
                    mysqli_data_seek($catQuery, 0);
                    while($c = mysqli_fetch_assoc($catQuery)) { ?>
                        <option value="<?php echo $c['category_id']; ?>"
                            <?php if(isset($_GET['category']) && $_GET['category'] == $c['category_id']) echo "selected"; ?>>
                            <?php echo $c['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100 fw-bold shadow-sm">Apply Filter</button>
            </div>
        </form>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php if(mysqli_num_rows($medicines) == 0): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <h4 class="text-muted mt-3">No medicines found matching your criteria.</h4>
            </div>
        <?php endif; ?>
        <?php while($row = mysqli_fetch_assoc($medicines)): ?>
        <div class="col">
            <div class="medicine-card">
                <div class="img-container">
                    <img src="uploads/<?php echo $row['image']; ?>" class="medicine-img" alt="medicine">
                </div>

                <div class="card-info">
                    <?php if($row['prescription_required']): ?>
                        <span class="status-badge badge-req"><i class="bi bi-file-earmark-medical"></i> Required</span>
                    <?php else: ?>
                        <span class="status-badge badge-otc">OTC (No Presc.)</span>
                    <?php endif; ?>

                    <h5 class="med-name" title="<?php echo htmlspecialchars($row['name']); ?>">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </h5>
                    <p class="brand-info">
                        <?php echo htmlspecialchars($row['brand'] ?? 'Generic'); ?> | <?php echo $row['category_name'] ?? 'General'; ?>
                    </p>
                    <span class="price-tag">৳ <?php echo number_format($row['price'], 2); ?></span>
                </div>
                <div class="cart-controls">
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="medicine_id" value="<?php echo $row['medicine_id']; ?>">
                        <div class="qty-box">
                            <span class="qty-label">Qty</span>
                            <input type="number" name="qty" value="1" min="1" class="qty-input">
                        </div>
                        <button type="submit" class="btn-cart">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>