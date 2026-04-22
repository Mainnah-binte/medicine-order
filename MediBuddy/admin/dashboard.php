<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
?>
<style>
    body {
        background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
        min-height: 100vh;
        overflow-x: hidden;
    }
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #1a233a;
        position: fixed;
        left: 0;
        top: 0;
        padding-top: 20px;
        transition: all 0.3s;
    }
    .sidebar-brand {
        padding: 10px 25px;
        margin-bottom: 30px;
    }
    .sidebar-brand h3 {
        color: #fff;
        font-family: cursive;
        font-weight: 900;
        font-size: 24px;
    }
    .sidebar-menu {
        list-style: none;
        padding: 0;
    }
    .sidebar-menu li {
        padding: 5px 20px;
    }
    .sidebar-menu li a {
        color: #adb5bd;
        text-decoration: none;
        display: block;
        padding: 12px 15px;
        border-radius: 10px;
        font-weight: 500;
        transition: 0.3s;
    }
    .sidebar-menu li a:hover, .sidebar-menu li a.active {
        background: #0d6efd;
        color: #fff;
    }
    .main-content {
        padding: 30px;
    }
    .top-header {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        padding: 15px 25px;
        border-radius: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .stat-card {
        background: #fff;
        border-radius: 15px;
        border: none;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        text-align: center;
        transition: 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .chart-container {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        height: 320px;
    }
</style>
<div class="sidebar">
    <div class="sidebar-brand">
        <h3>MediCart</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="manage_medicines.php">Medicines</a></li>
        <li><a href="orders.php">Orders</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="logout.php" style="color: #ff6b6b;">Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="top-header">
        <h5 class="m-0 fw-bold">Admin Panel</h5>
        <span class="badge bg-primary rounded-pill px-3 py-2">Hello, <?= $_SESSION['user_name'] ?></span>
    </div>
    <?php
    $revenue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(quantity * price) AS total FROM order_items"))['total'] ?? 0;
    $meds = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM medicines"))['total'] ?? 0;
    $orders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM orders"))['total'] ?? 0;
    $labels = []; $data = [];
    $chart_query = mysqli_query($conn,"SELECT m.name, SUM(oi.quantity) AS qty FROM order_items oi JOIN medicines m ON oi.medicine_id = m.medicine_id GROUP BY oi.medicine_id ORDER BY qty DESC LIMIT 5");
    while($c = mysqli_fetch_assoc($chart_query)) {
        $labels[] = $c['name'];
        $data[] = $c['qty'];
    }
    if(empty($labels)) { $labels = ['No Data']; $data = [0]; }
    ?>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Revenue</small>
                <h3 class="text-success fw-bold m-0 mt-1">৳ <?= number_format($revenue) ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Medicines</small>
                <h3 class="text-primary fw-bold m-0 mt-1"><?= $meds ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Orders</small>
                <h3 class="text-warning fw-bold m-0 mt-1"><?= $orders ?></h3>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-8">
            <div class="chart-container">
                <p class="fw-bold mb-3" style="font-size: 14px;">Sales Analysis (Quantity)</p>
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="chart-container">
                <p class="fw-bold mb-3" style="font-size: 14px;">Distribution</p>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
const ctx1 = document.getElementById('salesChart');
const ctx2 = document.getElementById('pieChart');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Sales',
            data: <?= json_encode($data) ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f8f9fa' } },
            x: { grid: { display: false } }
        }
    }
});
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            data: <?= json_encode($data) ?>,
            backgroundColor: ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6f42c1'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
        },
        cutout: '75%'
    }
});
</script>