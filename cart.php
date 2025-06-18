<?php
include("db.php");
session_start();

$user_id = $_SESSION['user_id'] ?? 1;

// Add to cart
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);

    if ($stmt->rowCount() > 0) {
        $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?")
             ->execute([$user_id, $product_id]);
    } else {
        $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)")
             ->execute([$user_id, $product_id]);
    }

    header("Location: cart.php");
    exit;
}

// Remove item
if (isset($_GET['remove'])) {
    $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?")
         ->execute([$_GET['remove'], $user_id]);
    header("Location: cart.php");
    exit;
}

// Fetch cart items
$stmt = $conn->prepare("SELECT cart.id, products.name, products.price, products.image, products.category, cart.quantity
                        FROM cart
                        JOIN products ON cart.product_id = products.id
                        WHERE cart.user_id = ?");
$stmt->execute([$user_id]);
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fffdf5;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #ff6600;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            transition: background 0.3s;
        }

        .navbar .nav-links a:hover {
            background-color: #e65c00;
        }

        h1 {
            text-align: center;
            color: #ff6600;
            margin-top: 30px;
        }

        .cart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }

        .cart-item {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
            width: 250px;
            overflow: hidden;
            text-align: center;
            transition: transform 0.2s;
        }

        .cart-item:hover {
            transform: translateY(-5px);
        }

        .cart-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .cart-item h3 {
            margin: 10px 0 5px;
            font-size: 1.2em;
            color: #333;
        }

        .cart-item p {
            margin: 5px;
            color: #555;
        }

        .cart-item .subtotal {
            font-weight: bold;
            color: #444;
            margin-top: 10px;
        }

        .cart-item .btn-remove {
            background-color: #d63031;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0;
        }

        .btn-remove:hover {
            background-color: #c0392b;
        }

        .total {
            text-align: center;
            font-size: 1.4em;
            font-weight: bold;
            color: #2d3436;
            margin-top: 30px;
        }

        .checkout {
            text-align: center;
            margin: 20px;
        }

        .checkout a {
            background-color: #ff6600;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1em;
        }

        .checkout a:hover {
            background-color: #ff6600;
        }

        .empty {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">Mini Grocery</div>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Cart Heading -->
<h1>Your Cart</h1>

<?php if (empty($cart)): ?>
    <div class="empty">Your cart is empty.</div>
<?php else: ?>
    <div class="cart-container">
        <?php foreach ($cart as $item): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            $imgPath = "images/" . strtolower($item['category']) . "/" . $item['image'];
        ?>
        <div class="cart-item">
            <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($item['name']) ?>">
            <h3><?= htmlspecialchars($item['name']) ?></h3>
            <p>Price: ₹<?= number_format($item['price'], 2) ?></p>
            <p>Quantity: <?= $item['quantity'] ?></p>
            <p class="subtotal">Subtotal: ₹<?= number_format($subtotal, 2) ?></p>
            <a href="cart.php?remove=<?= $item['id'] ?>" class="btn-remove">Remove</a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="total">Total: ₹<?= number_format($total, 2) ?></div>
    <div class="checkout">
        <a href="checkout.php">Place Order</a>
    </div>
<?php endif; ?>

</body>
</html>
