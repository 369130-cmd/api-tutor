<?php
// functions.php

/**
 * Checks if a user is currently logged in.
 * Relies on a 'user_id' being set in the $_SESSION array during login.
 * * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Helper to securely redirect users
 * * @param string $url
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// We will add our Groq API helper functions here later!
?>