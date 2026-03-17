<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function render_header() {
    // Check if user is logged in
    $is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    ?>
    <nav class="navbar navbar-expand-lg px-4 shadow-sm" style="background-color: #FDFBF7; border-bottom: 1px solid #E0D7C6; z-index: 1001;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" style="color: #3E2723;" href="index.php">
                <i class="material-icons align-middle" style="color: #D4A017;">school</i> AI Tutor
            </a>
            <div class="ms-auto d-flex align-items-center">
                <?php if (!$is_logged_in): ?>
                    <span class="text-muted me-3 small d-none d-md-inline">Welcome, Guest!</span>
                    <a href="login.php" class="btn btn-sm fw-bold me-2" style="color: #3E2723; border: 1px solid #E0D7C6; border-radius: 8px;">Log In</a>
                    <a href="register.php" class="btn btn-sm fw-bold shadow-sm" style="background-color: #D4A017; color: #3E2723; border: none; border-radius: 8px;">Register lowkey</a>
                <?php else: ?>
                    <a href="logout.php" class="btn btn-sm fw-bold text-danger">Log Out</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <?php
}

function render_footer() {
    ?>
    <footer class="mt-auto py-3 w-100" style="background-color: #F4EFE6; border-top: 1px solid #E0D7C6; z-index: 1000;">
        <div class="container text-center">
            <span class="text-muted small">
                &copy; <?php echo date("Y"); ?> AI Tutor. All rights reserved. | 
                <a href="contact.php" style="color: #D4A017; text-decoration: none; font-weight: bold;">Contact Us</a>
            </span>
        </div>
    </footer>
    <?php
}
?>