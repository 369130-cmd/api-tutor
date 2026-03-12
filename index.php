<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini Style Mockup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        body { display: flex; min-height: 100vh; overflow-x: hidden; }

        /* Sidebar Logic */
        #sidebar {
            width: 260px;
            transition: all 0.3s;
            background: #F4EFE6; /* Slightly darker cream for depth */
            border-right: 1px solid #E0D7C6;
            z-index: 1000;
        }

        #sidebar.collapsed { width: 70px; }

        .nav-link {
            color: #3E2723;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            white-space: nowrap;
        }

        .nav-link i { margin-right: 15px; color: #D4A017; }

        #sidebar.collapsed .link-text { display: none; }

        .main-content { flex-grow: 1; padding: 2rem; background-color: #FDFBF7; }

        .gemini-card {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s;
            background: white;
            box-shadow: 0 4px 12px rgba(62, 39, 35, 0.08);
        }

        .gemini-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<nav id="sidebar" class="d-flex flex-column">
    <div class="p-3">
        <button class="btn border-0" id="toggleBtn">
            <i class="material-icons">menu</i>
        </button>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="material-icons">home</i>
                <span class="link-text">Home</span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link">
                <i class="material-icons">person</i>
                <span class="link-text">Profile</span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link">
                <i class="material-icons">settings</i>
                <span class="link-text">Settings</span>
            </a>
        </li>
    </ul>
</nav>

<div class="main-content">
    <header class="mb-5">
        <h1 class="display-5 fw-bold">Hello, User</h1>
        <p class="text-muted">How can I help you build today?</p>
    </header>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card gemini-card h-100 p-3">
                <div class="card-body">
                    <i class="material-icons mb-3" style="color: #D4A017;">code</i>
                    <h5 class="card-title">SQL Generator</h5>
                    <p class="card-text text-muted">Clean up your database schemas with one click.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card gemini-card h-100 p-3">
                <div class="card-body">
                    <i class="material-icons mb-3" style="color: #D4A017;">palette</i>
                    <h5 class="card-title">Theme Engine</h5>
                    <p class="card-text text-muted">Customize your CSS variables for a unique look.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card gemini-card h-100 p-3">
                <div class="card-body">
                    <i class="material-icons mb-3" style="color: #D4A017;">security</i>
                    <h5 class="card-title">User Auth</h5>
                    <p class="card-text text-muted">Secure login system with PHP and MySQL.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });
</script>
</body>
</html>