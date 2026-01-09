<?php
session_start();
require_once "conn.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["purchase_id"]) && isset($_POST["animal"])) {
    $purchase_id = $_POST["purchase_id"];
    $animal = $_POST["animal"];
    $username = $_SESSION["username"];

    // If "Other" was selected, use the value from the custom input
    if ($animal === "Other" && !empty($_POST["other_animal"])) {
        $animal = $_POST["other_animal"];
    }

    $sql = "UPDATE animals SET animal = ? WHERE purchase_id = ? AND username = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("sis", $animal, $purchase_id, $username);

    if ($stmt->execute()) {
        $message = "Update successful for Purchase ID: $purchase_id.";
    } else {
        $message = "Update failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update a Purchase</title>
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

        .update-container {
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

        input[type="text"], select {
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

        #other-animal-box {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function checkOtherAnimal(select) {
            const otherBox = document.getElementById('other-animal-box');
            if (select.value === "Other") {
                otherBox.style.display = 'block';
            } else {
                otherBox.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="update-container">
        <h1>Update a Purchase</h1>
        <p>Choose a new animal for your purchase!</p>

        <form method="POST" action="updatePurchase.php">
            <input type="text" name="purchase_id" placeholder="Enter Purchase ID..." 
                value="<?php echo isset($_GET['purchase_id']) ? htmlspecialchars($_GET['purchase_id']) : ''; ?>" 
                required>
            <?php if (isset($_GET['purchase_id'])): ?>
                <p style="color: #187795; font-size: 14px; margin-top: 8px;">
                    Updating Purchase ID: <?php echo htmlspecialchars($_GET['purchase_id']); ?>
                </p>
            <?php endif; ?>
            <br><br>

            <select name="animal" onchange="checkOtherAnimal(this)" required>
                <option value="">-- Select Animal --</option>
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
                <option value="Bird">Bird</option>
                <option value="Rabbit">Rabbit</option>
                <option value="Other">Other</option>
            </select>

            <div id="other-animal-box">
                <input type="text" name="other_animal" placeholder="Enter Custom Animal Name...">
            </div>

            <br>
            <button type="submit" class="submit-btn">Submit Update</button>
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
