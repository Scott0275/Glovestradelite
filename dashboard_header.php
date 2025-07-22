<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Glove TradeLite</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/google-fonts.css">
</head>
<body>
    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-storage.js"></script>

    <!-- Firebase Config -->
    <script src="assets/js/firebase-config.js"></script>

    <div class="main-wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="assets/img/logo/logo.svg" alt="Logo" class="logo">
                <h3 id="user-welcome">Loading...</h3>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="invest.php" class="<?= $current_page == 'invest.php' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Invest</a></li>
                <li><a href="transactions.php" class="<?= $current_page == 'transactions.php' ? 'active' : '' ?>"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                <li><a href="withdraw.php" class="<?= $current_page == 'withdraw.php' ? 'active' : '' ?>"><i class="fas fa-wallet"></i> Withdraw</a></li>
                <li><a href="kyc.php" class="<?= $current_page == 'kyc.php' ? 'active' : '' ?>"><i class="fas fa-id-card"></i> KYC Verification</a></li>
                <li><a href="profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>"><i class="fas fa-user-circle"></i> Profile</a></li>
                <li><a href="referrals.php" class="<?= $current_page == 'referrals.php' ? 'active' : '' ?>"><i class="fas fa-users"></i> Referrals</a></li>
                <li><a href="notifications.php" class="<?= $current_page == 'notifications.php' ? 'active' : '' ?>"><i class="fas fa-bell"></i> Notifications</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content">
            <script>
                function logout() {
                    auth.signOut().catch((error) => {
                        console.error("Logout Error:", error);
                    });
                }

                auth.onAuthStateChanged(user => {
                    if (user) {
                        const welcomeEl = document.getElementById('user-welcome');
                        if (welcomeEl) {
                            // Assumes user's name is set during registration
                            welcomeEl.textContent = `Welcome, ${user.displayName || 'User'}`;
                        }
                    } else {
                        // User is signed out, redirect to login.
                        window.location.replace('auth/login.html');
                    }
                });
            </script>