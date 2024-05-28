<?php
$host = 'localhost';
$user = 'root';
$pass = 'marasigan';
$db = 'ecommerce';

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}
?>