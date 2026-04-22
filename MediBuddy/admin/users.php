<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
include('../includes/ad_header.php');
$users = mysqli_query($conn, "SELECT user_id, name, email FROM users WHERE role = 'user'ORDER BY user_id DESC");
?>
<style>
    body {
        background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
        min-height: 100vh;
    }
    .main-content {
        padding: 40px;
    }
    .search-box {
        width: 320px;
        border-radius: 12px;
        padding: 12px 20px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        outline: none;
        transition: 0.3s;
    }
    .search-box:focus {
        border-color: #0d6efd;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.1);
    }
    .user-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    .user-card {
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        transition: 0.3s;
        text-align: center;
        position: relative;
    }
    .user-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px-30px rgba(0,0,0,0.1);
    }
    .avatar-circle {
        width: 65px;
        height: 65px;
        background: #eef2ff;
        color: #0d6efd;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        font-weight: 800;
        margin: 0 auto 15px;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .user-name {
        font-size: 19px;
        font-weight: 700;
        color: #1a233a;
        margin-bottom: 5px;
    }
    .user-email {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 20px;
    }
    .btn-view {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        border-radius: 12px;
        padding: 10px;
        width: 100%;
        transition: 0.3s;
    }
    .btn-view:hover {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .user-id-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 11px;
        background: #f1f5f9;
        color: #94a3b8;
        padding: 4px 10px;
        border-radius: 8px;
    }
</style>
<div class="sidebar" style="width: 250px; height: 100vh; background: #1a233a; position: fixed; left: 0; top: 0;">
    <div class="p-4"><h3 style="color:#fff; font-family:cursive; font-weight:900;">MediCart</h3></div>
    <ul class="list-unstyled">
        <li class="px-4 py-2"><a href="dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
        <li class="px-4 py-2"><a href="manage_medicines.php" class="text-white-50 text-decoration-none">Medicines</a></li>
        <li class="px-4 py-2"><a href="orders.php" class="text-white-50 text-decoration-none">Orders</a></li>
        <li class="px-4 py-2 bg-primary rounded-end m-2"><a href="users.php" class="text-white text-decoration-none fw-bold">Users</a></li>
        <li class="px-4 py-2 mt-5"><a href="logout.php" class="text-danger text-decoration-none">Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-end mb-2">
        <div>
            <h2 style="font-weight: 900; color: #1a233a; margin:0; font-family: cursive">Customer <span class="text-primary">Info</span></h2>
            <p class="text-muted m-0">View registered customers and their purchase history</p>
        </div>
        <input type="text" id="search" class="search-box" placeholder=" Search Name or Email...">
    </div>
    <div class="user-grid" id="userGrid">
        <?php while($u = mysqli_fetch_assoc($users)) { ?>
        <div class="user-card shadow-sm">
            <span class="user-id-badge">ID #<?= $u['user_id'] ?></span>
            <div class="avatar-circle">
                <?= strtoupper(substr($u['name'], 0, 1)) ?>
            </div>
            <div class="user-name"><?= htmlspecialchars($u['name']) ?></div>
            <a href="user_orders.php?id=<?= $u['user_id'] ?>" class="btn btn-view text-decoration-none d-block"><i class="bi bi-eye"></i> View Activity</a>
        </div>
        <?php } ?>
    </div>
</div>
<script>
document.getElementById("search").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let cards = document.querySelectorAll(".user-card");
    cards.forEach(card => {
        let name = card.querySelector(".user-name").innerText.toLowerCase();
        let email = card.querySelector(".user-email").innerText.toLowerCase();
        
        if (name.includes(value) || email.includes(value)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
});
</script>
