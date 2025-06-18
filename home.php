<?php
include("db.php");
session_start();

function showCategory($conn, $category) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = :category");
    $stmt->execute(['category' => $category]);
    echo "<div class='category-section'>";
    echo "<h2>$category</h2><div class='product-row'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imgPath = "images/" . strtolower($category) . "/" . $row['image'];
        echo "<div class='product'>
                <img src='$imgPath' alt='{$row['name']}'>
                <h3>{$row['name']}</h3>
                <p>₹{$row['price']}</p>
                <a href='cart.php?add={$row['id']}' class='btn'>Add to Cart</a>
              </div>";
    }
    echo "</div></div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mini Grocery - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff5e6;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ff6600;
            color: white;
            padding: 15px 30px;
        }

        .navbar .logo {
            font-size: 1.8em;
            font-weight: bold;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1em;
            padding: 6px 12px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .navbar .nav-links a:hover {
            background-color: #e65c00;
        }

        .container {
            padding: 20px;
            width: 100%;
        }

        .intro-text {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .intro-text h1 {
            font-size: 2.8em;
            color: #ff6600;
        }

        .intro-text p {
            font-size: 1.2em;
            color: #444;
            margin-top: 10px;
        }

        h2 {
            color: #ff6600;
            margin: 40px 0 20px 20px;
            border-bottom: 2px solid #ffbb66;
            padding-bottom: 5px;
            text-align: left;
        }

        .category-section {
            margin-bottom: 40px;
        }

        .product-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .product {
            background-color: #fff;
            border: 2px solid #ffe0b3;
            border-radius: 10px;
            padding: 15px;
            width: 180px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
            text-align: center;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product h3 {
            font-size: 1.1em;
            margin: 10px 0 5px;
            color: #333;
        }

        .product p {
            font-size: 1em;
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #ff6600;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #ff6600;
        }

        @media screen and (max-width: 768px) {
            .product-row {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">Mini Grocery</div>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <div class="intro-text">
        <h1>Welcome to Mini Grocery 🛒</h1>
        <p>Shop fresh vegetables, fruits & dairy items from the comfort of your home.</p>
    </div>

    <?php
    showCategory($conn, "Vegetables");
    showCategory($conn, "Fruits");
    showCategory($conn, "Dairy");
    ?>
</div>

</body>
</html>
