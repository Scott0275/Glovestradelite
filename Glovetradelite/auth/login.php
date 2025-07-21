<?php
session_start();
include '../db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['role'] = $user['role'];
  if ($user['role'] === 'admin') {
    header("Location: ../admin_dashboard.php");
  } else {
    header("Location: ../dashboard.php");
  }
} else {
  echo "Invalid login credentials.";
}
?>