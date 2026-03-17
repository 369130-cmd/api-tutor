<?php
$host = 'auth-db941.hstgr.io';
$db   = 'u237055794_team07';
$user = 'u237055794_team07';
$pass = 'S~3iYi5hr|h';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (\PDOException $e) { // Fixed: Single backslash
    throw new \PDOException($e->getMessage(), (int)$e->getCode()); // Fixed: Single backslash
}