<?php
// Database connection settings
$host = "localhost";      // usually 'localhost'
$dbname = "wswc";         // your database name
$username = "root";       // your MySQL username
$password = "";           // your MySQL password (set it if you have one)

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
INSERT INTO users (username, password_hash) VALUES ('admin', 'the_hash_here');