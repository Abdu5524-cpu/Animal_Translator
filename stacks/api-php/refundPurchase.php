<?php
session_start();
require_once "conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["purchase_id"])) {
    $purchase_id = $_POST["purchase_id"];
    $username = $_SESSION["username"];

    $sql = "DELETE FROM animals WHERE purchase_id = ? AND username = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("is", $purchase_id, $username);

    if ($stmt->execute()) {
        $message = "Refund successful for Purchase ID: $purchase_id.";
    } else {
        $message = "Refund failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Refund a Purchase</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('pictures/raccoon.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-y: auto;
        }

        .refund-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            color: #187795;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #444;
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 2px solid #187795;
            margin-top: 10px;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 10px 30px;
            font-size: 18px;
            background-color: #187795;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #145f75;
        }

        .links {
            margin-top: 30px;
        }

        .links a {
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
            color: #187795;
            font-weight: bold;
        }

        .message {
            margin-top: 20px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="refund-container">
        <h1>Refund a Purchase</h1>
        <p>What is the ID number of the purchase you'd like to refund?</p>

        <form method="POST" action="refundPurchase.php">
            <input type="text" name="purchase_id" placeholder="Enter Purchase ID..." 
                value="<?php echo isset($_GET['purchase_id']) ? htmlspecialchars($_GET['purchase_id']) : ''; ?>" 
                required>
            <?php if (isset($_GET['purchase_id'])): ?>
                <p style="color: #187795; font-size: 14px; margin-top: 8px;">
                    Refunding Purchase ID: <?php echo htmlspecialchars($_GET['purchase_id']); ?>
                </p>
            <?php endif; ?>
            <br>
            <button type="submit" class="submit-btn">Submit Refund</button>
        </form>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="links">
            <a href="getPurchase.php">📄 View My Purchases</a>
            <a href="index.php">🏠 Return Home</a>
        </div>
    </div>
</body>
</html>
