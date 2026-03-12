<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];
    $bio = $_POST['bio'];
    $avatar_url = $_POST['avatar_url'];

    try {
        // Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        $user_id = $pdo->lastInsertId();

        // Insert into profiles table
        $stmt = $pdo->prepare("INSERT INTO profiles (user_id, full_name, bio, avatar_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $full_name, $bio, $avatar_url]);

        echo "Registration successful!";
    } catch (\PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br><br>
        <label for="bio">Bio:</label>
        <input type="text" id="bio" name="bio"><br><br>
        <label for="avatar_url">Avatar URL:</label>
        <input type="text" id="avatar_url" name="avatar_url"><br><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>