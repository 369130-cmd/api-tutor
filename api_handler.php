<?php
// --- THE API LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $apiKey = $_ENV['GROQ_API_KEY'] ?? getenv('GROQ_API_KEY');
    $input = json_decode(file_get_contents("php://input"), true);
    $userMsg = $input['message'] ?? "Ping!";

    $data = [
        "model" => "llama-3.3-70b-versatile",
        "messages" => [["role" => "user", "content" => $userMsg]]
    ];

    $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey
    ]);

    echo curl_exec($ch);
    curl_close($ch);
    exit; // Stop here so the HTML doesn't leak into the JSON
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>API Tester</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.5; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        #output { background: #f4f4f4; padding: 15px; margin-top: 20px; border-radius: 5px; white-space: pre-wrap; word-break: break-all; }
    </style>
</head>
<body>
    <h2>Groq API Middleman Tester</h2>
    <p>Tap the button below to send a POST request to this script.</p>

    <button onclick="testApi()">Send "Hello AI" Request</button>

    <div id="output">Results will appear here...</div>

    <script>
        async function testApi() {
            const out = document.getElementById('output');
            out.innerText = "Sending request...";

            try {
                const response = await fetch('', { // Sends to itself
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: "hey" })
                });
                const data = await response.json();

                // Show only the answer to keep it clean on mobile
                out.innerText = "AI SAID: " + data.choices[0].message.content;
            } catch (err) {
                out.innerText = "Error: " + err;
            }
        }
    </script>
</body>
</html>