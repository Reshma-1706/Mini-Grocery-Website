<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_FILES['image']['name'];
    $target = "../images/" . strtolower($category) . "/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, category, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $price, $category, $image]);
        $success = "Product added successfully!";
    } else {
        $error = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        form { max-width: 400px; margin: auto; text-align: left; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; }
        .btn { background: green; color: white; padding: 10px 20px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Add New Product</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" required>
        <select name="category" required>
            <option value="">Select Category</option>
            <option>Vegetables</option>
            <option>Fruits</option>
            <option>Dairy</option>
        </select>
        <input type="file" name="image" required>
        <button class="btn">Add Product</button>
    </form>
    <?php
    if (isset($success)) echo "<p style='color:green;text-align:center;'>$success</p>";
    if (isset($error)) echo "<p style='color:red;text-align:center;'>$error</p>";
    ?>
</body>
</html>
