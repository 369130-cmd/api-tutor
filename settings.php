<?php
// Start the session at the very top to remember the user
session_start();

// If the form is submitted, update the name in the session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['display_name'])) {
    $_SESSION['display_name'] = htmlspecialchars(trim($_POST['display_name']));
    $message = "Profile updated successfully!";
}

// Get the current name, or default to "Scholar"
$display_name = $_SESSION['display_name'] ?? 'Scholar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - AI Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; overflow-x: hidden; background-color: #FDFBF7; }

        /* Fixed Sidebar */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            transition: width 0.3s ease-in-out;
            background: #F4EFE6;
            border-right: 1px solid #E0D7C6;
            z-index: 1000;
            overflow-x: hidden; /* THIS FIXES THE SLOPPY BAR */
        }
        #sidebar.collapsed { width: 75px; }
        .nav-link {
            color: #3E2723;
            display: flex;
            align-items: center;
            padding: 15px 22px;
            transition: background 0.2s;
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
        }
        .nav-link:hover { background-color: #EAD9C1; color: #3E2723; }
        .nav-link i { margin-right: 20px; color: #D4A017; font-size: 24px; }
        .link-text {
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            opacity: 1;
            visibility: visible;
            white-space: nowrap;
        }
        #sidebar.collapsed .link-text { opacity: 0; visibility: hidden; }
        .main-content { flex-grow: 1; padding: 2.5rem; }
        .gemini-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 4px 15px rgba(62, 39, 35, 0.06);
        }
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
        <li class="nav-item"><a href="index.php" class="nav-link"><i class="material-icons">explore</i><span class="link-text">Community Feed</span></a></li>
        <li><a href="prompt.php" class="nav-link"><i class="material-icons">auto_awesome</i><span class="link-text">AI Generator</span></a></li>
        <li><a href="profile.php" class="nav-link"><i class="material-icons">account_circle</i><span class="link-text">My Lessons</span></a></li>
        <li><a href="settings.php" class="nav-link active" style="background-color: #EAD9C1;"><i class="material-icons">settings</i><span class="link-text">Settings</span></a></li>
    </ul>
</nav>

<div class="main-content">
    <h2 class="mb-4">Account Settings</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-success col-lg-6" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="card gemini-card p-4 col-lg-6">
        <form method="POST" action="settings.php">
            <div class="mb-3">
                <label class="form-label fw-bold">Display Name</label>
                <input type="text" name="display_name" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($display_name); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Preferred Grade Level</label>
                <select class="form-select bg-light border-0">
                    <option>High School</option>
                    <option>College</option>
                    <option>Professional</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="background-color: #D4A017; border: none; color: #3E2723; font-weight: bold;">Save Changes</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('toggleBtn').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('collapsed');
    });
</script>
</body>
</html>