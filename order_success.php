<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Elegant Interiors</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://source.unsplash.com/1600x900/?luxury,interior') no-repeat center center/cover;
            text-align: center;
            padding: 50px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: #4A2D2D;
            font-size: 28px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin: 15px 0;
        }

        .icon {
            font-size: 50px;
            color: #27ae60;
            animation: pop 0.8s ease-in-out infinite alternate;
        }

        @keyframes pop {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #4A2D2D;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #9C5A5A;
        }

        .list {
            text-align: left;
            margin: 20px auto;
            max-width: 500px;
            padding-left: 0;
        }

        .list li {
            font-size: 16px;
            color: #444;
            margin-bottom: 10px;
            list-style: none;
            position: relative;
            padding-left: 30px;
        }

        .list li::before {
            content: "✔";
            color: #27ae60;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="icon">🎉</div>
    <h2>Thank You for Your Purchase!</h2>
    <p>Your order has been successfully placed. Our premium interior design pieces will soon elevate your living space.</p>
    
    <h3>What’s Next?</h3>
    <ul class="list">
        <li>Your order is being carefully packed with elegance.</li>
        <li>We will notify you once your items are dispatched.</li>
        <li>Estimated delivery: <strong>3-7 business days</strong>.</li>
        <li>Need assistance? <a href="contact.php">Contact our design specialists</a>.</li>
    </ul>

    <p>Explore more <a href="new_arrivals.php">luxury interior collections</a> while you wait!</p>

    <a href="index.php" class="btn">Return to Home</a>
</div>

</body>
</html>
