<?php
$reply = "";
$userMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["message"])) {
    $userMessage = trim($_POST["message"]);
    

    // Send the message to chat.php using cURL
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = rtrim(dirname($_SERVER['REQUEST_URI']), '/');
    $chatUrl = $scheme . '://' . $host . $path . '/chat.php';
    $ch = curl_init($chatUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $userMessage]));

    $response = curl_exec($ch);

    if ($response === false) {
        $curlError = curl_error($ch);
        echo "<pre>cURL error: " . htmlspecialchars($curlError) . "</pre>";
        exit;
    }

    curl_close($ch);

    // Parse reply from chat.php
    $result = json_decode($response, true);
    $reply = $result['reply'] ?? 'No response from AI.';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pet AI Chat Demo</title>
    <style>
        #chat-box { background: #f4f4f4; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; width: 80%; margin: 0 auto; }
        form { width: 80%; margin: 0 auto; text-align: center; }
        input[type="text"] { width: 70%; padding: 10px; }
        button { padding: 10px 20px; }
    </style>
</head>
<body>

<h2 style="text-align: center;">Chat with AI About Your Pet</h2>

<div id="chat-box">
    <?php if ($userMessage): ?>
        <p><b>You:</b> <?= htmlspecialchars($userMessage) ?></p>
        <p><b>AI:</b> <?= htmlspecialchars($reply) ?></p>
    <?php else: ?>
        <p><em>Describe your pet's behavior to start chatting.</em></p>
    <?php endif; ?>
</div>

<form method="POST" action="">
    <input type="text" name="message" placeholder="Describe your pet's behavior..." required>
    <button type="submit">Send</button>
</form>

</body>
</html>
