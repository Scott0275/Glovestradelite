<?php
$servername = "localhost";
$username = "glove_db";
$password = "your_db_password";
$dbname = "glove_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>