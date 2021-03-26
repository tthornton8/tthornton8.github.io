<?php
$servername = "localhost";
$username = "phpmyadmin";
$password = "CherryBlossom2021";
$dbname = "gradcherry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>