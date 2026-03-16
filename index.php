<?php
session_start();
$display_name = $_SESSION['display_name'] ?? 'Scholar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AI Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        /* SAME CSS AS SETTINGS PAGE ABOVE */
        body { display: flex; min-height: 100vh; overflow-x: hidden; background-color: #FDFBF7; }
        #sidebar {
            width: 260px; min-height: 100vh; transition: width 0.3s ease-in-out;
            background: #F4EFE6; border-right: 1px solid #E0D7C6; z-index: 1000;
            overflow-x: hidden;
        }
        #sidebar.collapsed { width: 75px; }
        .nav-link { color: #3E2723; display: flex; align-items: center; padding: 15px 22px; transition: background 0.2s; border-radius: 0 25px 25px 0; margin-right: 15px; }
        .nav-link:hover { background-color: #EAD9C1; color: #3E2723; }
        .nav-link i { margin-right: 20px; color: #D4A017; font-size: 24px; }
        .link-text { transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out; opacity: 1; visibility: visible; white-space: nowrap; }
        #sidebar.collapsed .link-text { opacity: 0; visibility: hidden; }
        .main-content { flex-grow: 1; padding: 2.5rem; }
        .gemini-card { border: none; border-radius: 20px; background: white; box-shadow: 0 4px 15px rgba(62, 39, 35, 0.06); transition: transform 0.2s; }
        .gemini-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>

<nav id="sidebar" class="d-flex flex-column">
    <div class="p-3 mb-2">
        <button class="btn border-0 d-flex align-items-center" id="toggleBtn">
            <i class="material-icons" style="color: #3E2723;">menu</i>
        </button>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item"><a href="index.php" class="nav-link active" style="background-color: #EAD9C1;"><i class="material-icons">explore</i><span class="link-text">Community Feed</span></a></li>
        <li><a href="prompt.php" class="nav-link"><i class="material-icons">auto_awesome</i><span class="link-text">AI Generator</span></a></li>
        <li><a href="profile.php" class="nav-link"><i class="material-icons">account_circle</i><span class="link-text">My Lessons</span></a></li>
        <li><a href="settings.php" class="nav-link"><i class="material-icons">settings</i><span class="link-text">Settings</span></a></li>
    </ul>
</nav>

<div class="main-content">
    <header class="mb-5">
        <h1 class="display-6 fw-bold">Welcome back, <?php echo htmlspecialchars($display_name); ?></h1>
        <p class="text-muted">You have <strong>50 tokens</strong> remaining.</p>
    </header>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card gemini-card p-4 mb-4">
                <h5>Generate New Lesson</h5>
                [cite_start]<p class="text-muted">Unlike standard chatbots, you must force structured interaction[cite: 4]. Start a prompt below.</p>
                <a href="prompt.php" class="btn btn-primary px-4 mt-2" style="background-color: #D4A017; border: none; color: #3E2723; font-weight: bold;">Start Prompting</a>
            </div>

            <div class="card gemini-card p-4 text-center">
                <h2 class="fw-bold" style="color: #D4A017;">50</h2>
                <span class="text-muted text-uppercase small fw-bold">Token Balance</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card gemini-card p-4 h-100" style="background-color: #F4EFE6;">
                <h5 class="fw-bold mb-3"><i class="material-icons align-middle me-2" style="color: #D4A017;">info</i>How It Works</h5>

                <div class="mb-3">
                    <strong class="d-block text-dark">1. Select Constraints</strong>
                    [cite_start]<span class="text-muted small">Choose your specific context, task, and format from the dropdowns[cite: 4].</span>
                </div>

                <div class="mb-3">
                    <strong class="d-block text-dark">2. Provide Input</strong>
                    [cite_start]<span class="text-muted small">Write a targeted text input to receive a strictly formatted JSON lesson plan[cite: 4].</span>
                </div>

                <div>
                    <strong class="d-block text-dark">3. Earn Rebates</strong>
                    [cite_start]<span class="text-muted small">You spend virtual currency to generate content, but you can earn rebates for high-quality prompts[cite: 5].</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleBtn').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('collapsed');
    });
</script>
</body>
</html>