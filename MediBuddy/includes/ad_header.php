<?php
if(!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>MediCart Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
 html, body {
    height: 100%;
    margin: 0;
}
body {
    background: #f4f6f9;
    display: flex;
    flex-direction: column;
}
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #0f172a, #1e293b);
    color: #fff;
    padding: 20px;
    overflow-y: auto;
}
.sidebar h4 {
    margin-bottom: 25px;
    font-weight: bold;
}
.sidebar a {
    display: block;
    padding: 12px;
    margin-bottom: 8px;
    color: #cbd5e1;
    text-decoration: none;
    border-radius: 10px;
    transition: 0.2s;
}
.sidebar a:hover {
    background: #334155;
    color: #fff;
    transform: translateX(4px);
}
.main {
    margin-left: 270px;
    padding: 20px;
    flex: 1;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
.card {
    border: none;
    border-radius: 15px;
}

footer {
    margin-top: auto;
    padding: 15px;
    text-align: center;
    color: #6b7280;
}
    </style>
</head>
<body>
<div class="sidebar">
    <h4> MediCart</h4>
    <a href="dashboard.php"> Dashboard</a>
    <a href="manage_medicines.php"> Medicines</a>
    <a href="orders.php"> Orders</a>
    <a href="users.php"> Users</a>
    <a href="logout.php"> Logout</a>
</div>
<div class="main">