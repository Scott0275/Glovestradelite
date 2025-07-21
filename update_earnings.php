<?php
include 'db.php';
$user_id = $_POST['user_id'];
$earnings = $_POST['earnings'];
$sql = "UPDATE users SET earnings=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $earnings, $user_id);
$stmt->execute();
header("Location: admin_dashboard.php");
?>