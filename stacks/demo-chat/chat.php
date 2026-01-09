<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$apiKey = getenv('OPENAI_API_KEY');
if (!$apiKey) {
  echo json_encode(['reply' => 'Server misconfigured: OPENAI_API_KEY is missing.']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$userMessage = $input['message'] ?? '';
if ($userMessage === '') {
  echo json_encode(['reply' => 'Message is required.']);
  exit;
}


$data =
    [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => "You are a Veterinary Behaviorist and you're helping users understand the behavior of their pets."],
            ['role' => 'user', 'content' => $userMessage]
        ]
    ];


$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER =>
    ['Content-Type: application/json',
     'Authorization: Bearer ' . $apiKey,],
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($data),
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$reply = $result['choices'][0]['message']['content'] ?? 'Sorry, I couldn’t process that.';

echo json_encode(['reply' => $reply]);
?>
