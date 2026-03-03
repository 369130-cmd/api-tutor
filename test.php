<?php
// --- 1. API MIDDLEMAN LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");

    $apiKey = $_ENV['GROQ_API_KEY'] ?? getenv('GROQ_API_KEY') ?? trim(shell_exec("echo \$GROQ_API_KEY"));

    if (!$apiKey) {
        http_response_code(500);
        echo json_encode(["error" => "API Key missing"]);
        exit;
    }

    $input = json_decode(file_get_contents("php://input"), true);
    $userMsg = $input['message'] ?? "";
    $persona = $input['persona'] ?? "General Assistant";
    $language = $input['language'] ?? "English";

    // Combine persona and language into one clear system instruction
    $systemPrompt = "You are an expert in $persona. Please provide your entire response in $language.";

    $data = [
        "model" => "llama-3.3-70b-versatile",
        "messages" => [
            ["role" => "system", "content" => $systemPrompt],
            ["role" => "user", "content" => $userMsg]
        ]
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
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Comp API Lab</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f7f6; display: flex; justify-content: center; }
        .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }

        /* Language Switch Styling */
        .switch-container { display: flex; align-items: center; margin-top: 15px; gap: 10px; }
        .switch { position: relative; display: inline-block; width: 50px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
        .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #00a67e; }
        input:checked + .slider:before { transform: translateX(26px); }

        button { width: 100%; padding: 14px; background: #00a67e; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 20px; }
        #output { margin-top: 20px; padding: 15px; background: #fdfdfd; border-radius: 8px; border-left: 4px solid #00a67e; white-space: pre-wrap; }
    </style>
</head>
<body>

<div class="container">
    <h2>Reading Comp Lab</h2>

    <label>Reading Persona:</label>
    <select id="persona">
        <option value="Reading Comprehension: Summary Mode (TL;DR)">Summarizer</option>
        <option value="Reading Comprehension: Critical Analysis">Critical Thinker</option>
        <option value="Reading Comprehension: Key Fact Extraction">Fact Finder</option>
        <option value="Reading Comprehension: Simple Explanation for Kids">Explain Like I'm 5</option>
    </select>

    <label>Language:</label>
    <div class="switch-container">
        <span>English</span>
        <label class="switch">
            <input type="checkbox" id="langSwitch">
            <span class="slider"></span>
        </label>
        <span>Spanish</span>
    </div>

    <label>Text to Analyze:</label>
    <textarea id="message" rows="6" placeholder="Paste your article or text here..."></textarea>

    <button onclick="runTest()" id="sendBtn">Analyze Text</button>

    <div id="output">Results will appear here...</div>
</div>

<script>
async function runTest() {
    const btn = document.getElementById('sendBtn');
    const out = document.getElementById('output');

    const isSpanish = document.getElementById('langSwitch').checked;
    const payload = {
        persona: document.getElementById('persona').value,
        message: document.getElementById('message').value,
        language: isSpanish ? "Spanish" : "English"
    };

    if (!payload.message) return alert("Please paste some text first!");

    btn.disabled = true;
    out.innerText = "Processing...";

    try {
        const response = await fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        out.innerText = data.choices[0].message.content;
    } catch (err) {
        out.innerText = "Error: " + err.message;
    } finally {
        btn.disabled = false;
    }
}
</script>

</body>
</html>