<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { text-align: center; font-family: sans-serif; padding: 40px; }
        .btn { margin: 10px; padding: 12px 20px; background: green; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Welcome, Admin</h1>
    <a href="add_product.php" class="btn">Add Product</a>
    <a href="view_products.php" class="btn">View Products</a>
    <a href="view_orders.php" class="btn">View Orders</a>
    <a href="logout.php" class="btn" style="background:red;">Logout</a>
</body>
</html>
