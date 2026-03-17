<?php
session_start();

// Require your functions file to access is_logged_in()
require_once 'functions.php';

// Security check: Ensure only authenticated users can access the prompt UI
if (!is_logged_in()) {
    header("Location: index.php"); // Or login.php depending on your routing
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AP Euro History Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">AP Euro History Tutor</h4>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="task" class="form-label fw-bold">Learning Task:</label>
                    <select id="task" class="form-select">
                        <option value="Summarize (TL;DR)">Summarize (TL;DR)</option>
                        <option value="Critical Analysis">Critical Analysis</option>
                        <option value="Key Fact Extraction">Key Fact Extraction</option>
                        <option value="Explain Like I'm 5">Explain Like I'm 5</option>
                    </select>
                </div>

                <div class="col-md-6 d-flex align-items-center mt-4 mt-md-0">
                    <div class="form-check form-switch ms-md-auto">
                        <input class="form-check-input" type="checkbox" role="switch" id="langSwitch">
                        <label class="form-check-label fw-bold" for="langSwitch">
                            Respond in Spanish
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="subTopic" class="form-label fw-bold">AP Euro Sub Topic:</label>
                    <select id="subTopic" class="form-select" onchange="updateHistorians()">
                        <option value="" selected disabled>Select an Era...</option>
                        <option value="The Renaissance">The Renaissance</option>
                        <option value="The French Revolution">The French Revolution</option>
                        <option value="The Industrial Revolution">The Industrial Revolution</option>
                        <option value="World War I & II">World War I & II</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="historian" class="form-label fw-bold">Historical Figure:</label>
                    <select id="historian" class="form-select" disabled>
                        <option value="">Select a sub-topic first...</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label fw-bold">Your Question or Text:</label>
                <textarea id="message" class="form-control" rows="5" placeholder="Ask your historical figure a question, or paste text to analyze..."></textarea>
            </div>

            <button onclick="runTest()" id="sendBtn" class="btn btn-success w-100 fw-bold py-2">
                Consult History (1 Credit)
            </button>

            <div id="outputContainer" class="mt-4 d-none">
                <label class="form-label fw-bold">Tutor Response:</label>
                <div id="output" class="p-3 bg-white border border-success rounded" style="white-space: pre-wrap;"></div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Data dictionary mapping AP Euro topics to figures WITH personality prompts
    const historicalFigures = {
        "The Renaissance": [
            { name: "Niccolò Machiavelli", persona: "You are Niccolò Machiavelli, a pragmatic and cynical Italian philosopher. You give ruthless, realistic advice focused on power and human nature." },
            { name: "Leonardo da Vinci", persona: "You are Leonardo da Vinci, an endlessly curious polymath. You speak with wonder about science, anatomy, and the intersection of art and engineering." },
            { name: "Isabella d'Este", persona: "You are Isabella d'Este, the 'First Lady of the Renaissance'. You are a highly educated, authoritative patron of the arts and master politician." }
        ],
        "The French Revolution": [
            { name: "Maximilien Robespierre", persona: "You are Maximilien Robespierre, an uncompromising Jacobin. You speak intensely about virtue, equality, and the absolute necessity of the Reign of Terror." },
            { name: "Marie Antoinette", persona: "You are Marie Antoinette, the extravagant former Queen of France. You are bewildered by the anger of the peasants and speak with detached, royal elegance." },
            { name: "Napoleon Bonaparte", persona: "You are Napoleon Bonaparte, an arrogant but brilliant military strategist. You believe you are destined to rule and speak with extreme confidence and authority." }
        ],
        "The Industrial Revolution": [
            { name: "Karl Marx", persona: "You are Karl Marx, a fiery political theorist. You view all history as class struggle and passionately critique the evils of capitalism and the bourgeoisie." },
            { name: "James Watt", persona: "You are James Watt, a methodical Scottish inventor. You are obsessed with mechanical efficiency, steam power, and practical engineering solutions." },
            { name: "Friedrich Engels", persona: "You are Friedrich Engels, a radical thinker who exposes the horrific living conditions of the working class with deep empathy and analytical precision." }
        ],
        "World War I & II": [
            { name: "Winston Churchill", persona: "You are Winston Churchill, the resolute British Prime Minister. You speak with defiance, using grand, stirring rhetoric and a bit of dry wit." },
            { name: "Woodrow Wilson", persona: "You are Woodrow Wilson, the moralistic American President. You are idealistic and constantly push for global democracy and diplomacy." },
            { name: "Joseph Stalin", persona: "You are Joseph Stalin, the paranoid and ruthless dictator of the Soviet Union. You trust no one, demand absolute loyalty, and speak coldly." }
        ]
    };

    // Conditional dropdown logic
    function updateHistorians() {
        const topicSelect = document.getElementById('subTopic');
        const historianSelect = document.getElementById('historian');
        const selectedTopic = topicSelect.value;

        // Clear existing options
        historianSelect.innerHTML = '';

        if (selectedTopic && historicalFigures[selectedTopic]) {
            historianSelect.disabled = false;
            historicalFigures[selectedTopic].forEach(figure => {
                const option = document.createElement('option');
                // The value is now the full persona description, the text is just the name
                option.value = figure.persona; 
                option.textContent = figure.name;
                historianSelect.appendChild(option);
            });
        } else {
            historianSelect.disabled = true;
            historianSelect.innerHTML = '<option value="">Select a sub-topic first...</option>';
        }
    }

    // API Handling logic
    async function runTest() {
        const btn = document.getElementById('sendBtn');
        const outContainer = document.getElementById('outputContainer');
        const out = document.getElementById('output');

        const task = document.getElementById('task').value;
        const subTopic = document.getElementById('subTopic').value;
        const historianPersona = document.getElementById('historian').value; // Now grabs the persona prompt
        const message = document.getElementById('message').value;
        const isSpanish = document.getElementById('langSwitch').checked;

        // Validation
        if (!subTopic) return alert("Please select an AP Euro Sub Topic.");
        if (!message) return alert("Please enter a question or paste some text!");

        const payload = {
            task: task,
            sub_topic: subTopic,
            historian_prompt: historianPersona, // Pass the prompt directly to the backend
            message: message,
            language: isSpanish ? "Spanish" : "English"
        };

        // UI Loading State
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        outContainer.classList.remove('d-none');
        out.innerText = "Consulting the archives...";

        try {
            const response = await fetch('api_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            // Check for HTTP errors
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // Error handling if Groq API or our backend returns an error
            if (data.error) {
                throw new Error(data.error);
            }

            // Output the response
            out.innerText = data.choices[0].message.content;

        } catch (err) {
            out.innerText = "Error: " + err.message + "\n\nPlease ensure api_handler.php is configured correctly and your token balance is sufficient.";
        } finally {
            // Restore UI State
            btn.disabled = false;
            btn.innerText = 'Consult History (1 Credit)';
        }
    }
</script>

</body>
</html>