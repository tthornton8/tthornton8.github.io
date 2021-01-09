<?php
$servername = "localhost";
$username = "root";
$password = "x3ydNbLgiRZduK";
$dbname = "the_gradz";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>