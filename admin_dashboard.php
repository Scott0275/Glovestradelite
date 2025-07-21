<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
  echo "Unauthorized";
  exit();
}
include 'db.php';
$users = $conn->query("SELECT * FROM users WHERE role='user'");
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
<h2>Admin Dashboard</h2>
<?php while ($user = $users->fetch_assoc()) { ?>
  <div>
    <p><?= htmlspecialchars($user['name']) ?> | <?= htmlspecialchars($user['email']) ?> | Earnings: $<?= htmlspecialchars($user['earnings']) ?></p>
    <form method="POST" action="update_earnings.php">
      <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
      <input type="number" name="earnings" value="<?= $user['earnings'] ?>" step="0.01">
      <button type="submit">Update</button>
    </form>
  </div>
<?php } ?>
<a href="logout.php">Logout</a>
</body>
</html>