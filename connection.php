<?php
$servername = "localhost";
$username = "root";
$password = "justine123";
$database = "motor_parts";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>