<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Delete product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: view_products.php");
    exit;
}

// Fetch products
$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products</title>
    <style>
        table { width: 90%; margin: auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        img { width: 80px; height: 80px; object-fit: cover; }
        .btn { padding: 6px 12px; color: white; text-decoration: none; border-radius: 4px; }
        .delete { background-color: red; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Product List</h2>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Image</th><th>Action</th>
        </tr>
        <?php foreach ($products as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td>₹<?= $row['price'] ?></td>
                <td><?= $row['category'] ?></td>
                <td><img src="../images/<?= strtolower($row['category']) ?>/<?= $row['image'] ?>" alt=""></td>
                <td><a href="?delete=<?= $row['id'] ?>" class="btn delete">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
