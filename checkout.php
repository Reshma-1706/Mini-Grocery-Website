<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You need to be logged in to place an order.");
}

$conn = new mysqli("127.0.0.1:3307", "root", "", "mini_grocery");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$cart_items = [];
$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

if (empty($cart_items)) {
    die("Your cart is empty. Please add products to your cart before proceeding.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $total_price = 0;

    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, address, phone, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssd", $user_id, $customer_name, $address, $phone, $total_price);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        $price_query = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $price_query->bind_param("i", $product_id);
        $price_query->execute();
        $price_result = $price_query->get_result();
        $price_row = $price_result->fetch_assoc();
        $price = $price_row['price'];
        $subtotal = $price * $quantity;
        $total_price += $subtotal;
        $price_query->close();

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
    $stmt->bind_param("di", $total_price, $order_id);
    $stmt->execute();
    $stmt->close();

    $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $delete_cart->bind_param("i", $user_id);
    $delete_cart->execute();
    $delete_cart->close();
    echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('images/hero-grocery.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background-color: rgba(46, 125, 50, 0.95); /* Green with slight transparency */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            padding: 20px;
        }
        .tick-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        p {
            font-size: 20px;
            margin-bottom: 25px;
        }
        a {
            padding: 12px 24px;
            background-color: #ff6f00; /* Orange */
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        a:hover {
            background-color: #e65100;
        }
    </style>
</head>
<body>
    <div class='card'>
        <svg class='tick-icon' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
            <circle cx='12' cy='12' r='10' fill='white' opacity='0.1'/>
            <path d='M5 13L9 17L19 7' stroke='white' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'/>
        </svg>
        <h2>Order Placed Successfully!</h2>
        <p>Your order ID is <strong>$order_id</strong></p>
        <a href='home.php'>Continue Shopping</a>
    </div>
</body>
</html>";
exit();




    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('images/hero-grocery.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkout-container {
            background-color: rgba(255, 255, 255, 0.3);
            padding: 40px;
            border-radius: 15px;
            width: 420px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        h1 {
            text-align: center;
            color: rgb(255, 98, 0);
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            background-color: #ffa500;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h1>Checkout</h1>
    <form method="POST" action="">
        <label for="customer_name">Full Name</label>
        <input type="text" name="customer_name" id="customer_name" required>

        <label for="address">Shipping Address</label>
        <textarea name="address" id="address" required></textarea>

        <label for="phone">Phone Number</label>
        <input type="tel" name="phone" id="phone" required>

        <button type="submit">Place Order</button>
    </form>
</div>

</body>
</html>
