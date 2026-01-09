<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "conn.php";

if (!isset($_SESSION["username"])) {
    die("No user logged in. Session username missing.");
}

$success_message = "";
$animal_bought = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["animal"])) {
    $animal = $_POST["animal"];
    if ($animal === "Other" && isset($_POST["other_animal"])) {
        $animal = $_POST["other_animal"];
    }
    $username = $_SESSION["username"];

    $sql = "INSERT INTO animals (username, animal, time_date) VALUES (?, ?, NOW())";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ss", $username, $animal);

    if ($stmt->execute()) {
        $success_message = "Success! You selected translator software for <strong>" . htmlspecialchars($animal) . "</strong>!";
        $animal_bought = htmlspecialchars($animal);
    } else {
        $success_message = "❌ Error saving your purchase. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy an Animal</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            height: 100%;
            background: white url('pictures/Dog_Paw_Print.png') repeat-y left center, white url('pictures/Dog_Paw_Print.png') repeat-y right center;
            background-size: 60px;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }

        .background {
            flex: 1;
            background: url('pictures/cow.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        .purchase-container {
            background: rgba(255, 255, 255, 0.92);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 20px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
            z-index: 2;
            position: relative;
            overflow: hidden;
        }

        /* ✅ Updated Walking Paw Prints */
        .walking-paws {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: calc(100% - 100px); /* stop before footer */
            pointer-events: none;
            z-index: 0;
        }

        .walk {
            position: absolute;
            width: 35px;
            opacity: 0;
            animation: stepWalk 6s linear infinite;
        }

        .paw-left-1 { bottom: 120px; left: 30px; animation-delay: 0s; transform: rotate(-15deg); }
        .paw-left-2 { bottom: 200px; left: 100px; animation-delay: 0.8s; transform: rotate(10deg); }
        .paw-left-3 { bottom: 280px; left: 170px; animation-delay: 1.6s; transform: rotate(-10deg); }

        .paw-right-1 { bottom: 120px; right: 30px; animation-delay: 3s; transform: rotate(15deg); }
        .paw-right-2 { bottom: 200px; right: 100px; animation-delay: 3.8s; transform: rotate(-10deg); }
        .paw-right-3 { bottom: 280px; right: 170px; animation-delay: 4.6s; transform: rotate(10deg); }

        @keyframes stepWalk {
            0%   { opacity: 0; transform: translateY(20px) scale(0.8) rotate(0deg); }
            10%  { opacity: 1; transform: translateY(0) scale(1) rotate(0deg); }
            40%  { opacity: 1; }
            60%  { opacity: 0.8; }
            100% { opacity: 0; transform: translateY(-100px) scale(1) rotate(0deg); }
        }

        h1 {
            color: #1d3557;
            font-size: 32px;
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            color: #187795;
            display: block;
            margin-bottom: 10px;
        }

        select, input[type="text"] {
            padding: 10px;
            width: 100%;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .cta-button {
            margin-top: 10px;
            padding: 12px 25px;
            background-color: #187795;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .cta-button:hover {
            background-color: #145f75;
        }

        .back-home {
            margin-top: 20px;
            display: inline-block;
            color: #4b0082;
            text-decoration: underline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 150px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            max-width: 500px;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content h2 {
            color: #187795;
        }

        .modal-content button, .modal-content a {
            margin: 10px;
            padding: 12px 24px;
            background-color: #187795;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
        }

        .speech-bubble {
            position: relative;
            background: #f0f8ff;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 16px;
            margin-top: 10px;
            display: inline-block;
            color: #333;
            max-width: 100%;
            animation: popIn 0.3s ease-out;
            z-index: 2;
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 40px;
            width: 0;
            height: 0;
            border: 10px solid transparent;
            border-top-color: #f0f8ff;
        }

        @keyframes popIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        footer {
            background-color: #145f75;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            width: 100%;
        }
    </style>
</head>

<body>
<!-- ✅ Animated paw prints outside white box and above footer -->
<div class="walking-paws">
    <img src="pictures/print.png" class="walk paw-left-1">
    <img src="pictures/print.png" class="walk paw-left-2">
    <img src="pictures/print.png" class="walk paw-left-3">
    <img src="pictures/print.png" class="walk paw-right-1">
    <img src="pictures/print.png" class="walk paw-right-2">
    <img src="pictures/print.png" class="walk paw-right-3">
</div>

<div class="background">
    <div class="purchase-container">
        <h1>Buy a New Animal Translating Software</h1>
        <form action="makePurchase.php" method="POST" id="buyForm">
            <label for="animal">What animal do you want to buy software for?</label>
            <select id="animal" name="animal" required onchange="toggleOtherInput()">
                <option value="">-- Select an Animal --</option>
                <option value="Dog">🐶 Dog</option>
                <option value="Cat">🐱 Cat</option>
                <option value="Horse">🐴 Horse</option>
                <option value="Bird">🥜 Bird</option>
                <option value="Pig">🐷 Pig</option>
                <option value="Monkey">🐵 Monkey</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" id="other_animal" name="other_animal" placeholder="Please specify..." style="display:none;">
            <div id="translation-preview" class="speech-bubble" style="display:none;"></div>
            <button type="submit" class="cta-button">Submit</button>
        </form>
        <a href="index.php" class="back-home">Return Home</a>
    </div>
</div>

<div id="successModal" class="modal">
  <div class="modal-content">
    <h2 id="successText"></h2>
    <a href="getPurchase.php">Complete My Purchase</a>
    <button onclick="closeModal()">Buy More Software?</button>
  </div>
</div>

<script>
function closeModal() {
    document.getElementById('successModal').style.display = "none";
    document.getElementById('buyForm').style.display = "block";
    document.getElementById('animal').selectedIndex = 0;
    document.getElementById('animal').focus();
    document.getElementById('other_animal').style.display = 'none';
    document.getElementById('translation-preview').style.display = 'none';
}

function toggleOtherInput() {
    const animalSelect = document.getElementById('animal');
    const otherInput = document.getElementById('other_animal');
    const previewBox = document.getElementById('translation-preview');
    const animal = animalSelect.value.toLowerCase();

    const translations = {
        "dog": "🐶 Dog says: \"Woof! Let's talk about squirrels.\"",
        "cat": "🐱 Cat says: \"Meow. I demand treats immediately.\"",
        "horse": "🐴 Horse says: \"Neigh! The open fields await.\"",
        "bird": "🥜 Bird says: \"Chirp chirp! Secrets of the sky.\"",
        "pig": "🐷 Pig says: \"Oink! Did someone say snacks?\"",
        "monkey": "🐵 Monkey says: \"Oo oo ah ah! Time for mischief!\"",
        "other": "🦄 Your unique animal awaits translation!"
    };

    if (animal === 'other') {
        otherInput.style.display = 'block';
        otherInput.required = true;
    } else {
        otherInput.style.display = 'none';
        otherInput.required = false;
    }

    if (translations[animal]) {
        previewBox.innerText = translations[animal];
        previewBox.style.display = 'inline-block';
        previewBox.style.animation = 'none';
        void previewBox.offsetWidth;
        previewBox.style.animation = 'popIn 0.3s ease-out';
    } else {
        previewBox.style.display = 'none';
        previewBox.innerText = '';
    }
}
<?php if (!empty($success_message) && !empty($animal_bought)): ?>
    window.onload = function() {
        document.getElementById('successText').innerHTML = "✅ Success! You selected translator software for <strong><?php echo $animal_bought; ?></strong>!";
        document.getElementById('successModal').style.display = "block";
        document.getElementById('buyForm').style.display = "none";

        confetti({
            particleCount: 200,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
<?php endif; ?>
</script>

<footer>
    <p>© 2025 Animal Translator Co. All Rights Reserved.</p>
    <p>Translator software not guaranteed to function properly. Absolutely no refunds. All sales are final. Thank you for your interest in slightly reliable technology. </p>
</footer>

</body>
</html>
