<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM orders ORDER BY id DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - View Orders</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f8ff;
            margin: 0;
            padding: 20px;
        }

        h1.page-title {
            text-align: center;
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px #ccc;
        }

        .back-home {
            text-align: center;
            margin-bottom: 30px;
        }

        .back-home a {
            text-decoration: none;
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-home a:hover {
            background-color: #1e8449;
        }

        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #2ecc71;
            color: white;
            font-size: 16px;
            padding: 14px;
        }

        td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 10px;
            border: 1px solid #ccc;
        }

        @media screen and (max-width: 768px) {
            table {
                font-size: 14px;
            }

            img {
                width: 40px;
                height: 40px;
            }

            .back-home a {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <h1 class="page-title">Welcome Admin - View Customer Orders</h1>

    <div class="back-home">
        <a href="../home.php">← Go to Home Page</a>
    </div>

    <table>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Price</th>
            <th>Order Items</th>
        </tr>

        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['user_id'] ?></td>
                <td>₹<?= $order['total_price'] ?></td>
                <td>
                    <ul>
                        <?php
                        $stmt2 = $conn->prepare("SELECT products.name, products.category, products.image, order_items.quantity
                                                 FROM order_items
                                                 JOIN products ON order_items.product_id = products.id
                                                 WHERE order_items.order_id = ?");
                        $stmt2->execute([$order['id']]);
                        $items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($items as $item):
                            $imagePath = "../images/" . strtolower($item['category']) . "/" . $item['image'];
                            if (!file_exists($imagePath) || empty($item['image'])) {
                                $imagePath = "../images/placeholder.png"; // fallback
                            }
                        ?>
                            <li>
                                <img src="<?= $imagePath ?>" alt="Product Image">
                                <?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
