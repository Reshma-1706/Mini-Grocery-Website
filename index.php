<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Mini Grocery</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            height: 100vh;
            background: url('images/hero-grocery.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
            padding: 20px;
        }

        h1 {
            font-size: 64px;
            color:rgba(255, 55, 0, 0.89); /* Deep Orange */
            font-weight: 800;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }

        p {
            font-size: 28px;
            color:rgb(0, 191, 16); /* Strong Teal */
            font-weight: 600;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.15);
            margin-bottom: 40px;
        }

        .btn {
            padding: 16px 38px;
            font-size: 20px;
            font-weight: bold;
            background-color:rgba(255, 55, 0, 0.89); /* Vibrant Orange-Red */
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        .btn:hover {
            background-color:rgba(255, 55, 0, 0.89);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <h1>Welcome to Mini Grocery</h1>
    <p>Fresh Vegetables, Juicy Fruits & Daily Dairy Essentials</p>
    <a href="login.php" class="btn">Start Shopping</a>
</body>
</html>
