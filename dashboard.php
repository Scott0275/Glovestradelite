<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.html");
  exit();
}
include 'db.php';
$id = $_SESSION['user_id'];
$sql = "SELECT name, earnings FROM users WHERE id = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>User Dashboard</title></head>
<body>
<h2>Welcome, <?= htmlspecialchars($data['name']) ?></h2>
<p>Your earnings: $<?= htmlspecialchars($data['earnings']) ?></p>
<a href="logout.php">Logout</a>
</body>
</html>