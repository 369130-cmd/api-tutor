<?php
session_start();
require_once 'functions.php';

// Security check: Deny access if not logged in
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access. Please log in.']);
    exit;
}

// --- 1. API MIDDLEMAN LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");

    // Fetch API Key securely from environment
    $apiKey = $_ENV['GROQ_API_KEY'] ?? getenv('GROQ_API_KEY') ?? trim(shell_exec("echo \$GROQ_API_KEY"));

    if (!$apiKey) {
        http_response_code(500);
        echo json_encode(["error" => "API Key missing. Please check your environment variables."]);
        exit;
    }

    // 2. Extract the new variables sent from prompt.php
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        echo json_encode(['error' => 'Invalid JSON payload received.']);
        exit;
    }

    $task = $input['task'] ?? 'General Discussion';
    $subTopic = $input['sub_topic'] ?? 'General History';
    $historianPrompt = $input['historian_prompt'] ?? 'You are a helpful history tutor.';
    $userMsg = $input['message'] ?? '';
    $language = $input['language'] ?? 'English';

    // 3. Construct the Strict System Prompt
    // This forces the AI into character BEFORE it sees the user's message
    $systemInstruction = "You are strictly roleplaying. " . $historianPrompt . "\n\n";
    $systemInstruction .= "The user has selected the AP Euro topic: " . $subTopic . ".\n";
    $systemInstruction .= "Your required learning task/format is: " . $task . ".\n";
    $systemInstruction .= "CRITICAL RULES:\n";
    $systemInstruction .= "- You MUST respond entirely in " . $language . ".\n";
    $systemInstruction .= "- Do NOT break character under any circumstances. Never refer to yourself as an AI or an assistant.\n";
    $systemInstruction .= "- Answer the user's prompt naturally as if you are the historical figure living in your era.";

    // 4. Prepare Groq API Payload
    $data = [
        "model" => "llama-3.3-70b-versatile", // Using your preferred model
        "messages" => [
            ["role" => "system", "content" => $systemInstruction],
            ["role" => "user", "content" => $userMsg]
        ],
        "temperature" => 0.7, // Adds a little personality to the historian
        "max_tokens" => 1000
    ];

    // 5. Execute cURL Request
    $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    // 6. API Error Handling (As requested in Co-Pilot rules)
    if ($err) {
        http_response_code(500);
        echo json_encode(['error' => 'cURL Error: ' . $err]);
        exit;
    }

    if ($httpCode !== 200) {
        http_response_code($httpCode);
        echo json_encode(['error' => 'Groq API Error (HTTP ' . $httpCode . '): ' . $response]);
        exit;
    }

    // 7. Send the successful response back to the frontend
    echo $response;
    exit;
}
?>