<?php $currentPage = basename($_SERVER['SCRIPT_FILENAME']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Glove TradeLite</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.svg">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">

    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>
    <script src="assets/js/firebase-config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="main-wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="index.html"><img src="assets/img/logo/logo.svg" alt="Logo" class="logo"></a>
                <h3 id="user-welcome">Welcome!</h3>
            </div>
            <ul class="sidebar-nav">
                <li class="<?php if ($currentPage == 'dashboard.php') {echo 'active';} ?>"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="<?php if ($currentPage == 'deposit.php') {echo 'active';} ?>"><a href="deposit.php"><i class="fas fa-download"></i> Deposit</a></li>
                <li class="<?php if ($currentPage == 'withdraw.php') {echo 'active';} ?>"><a href="withdraw.php"><i class="fas fa-wallet"></i> Withdraw</a></li>
                <li class="<?php if ($currentPage == 'invest.php') {echo 'active';} ?>"><a href="invest.php"><i class="fas fa-chart-line"></i> Invest</a></li>
                <li class="<?php if ($currentPage == 'transactions.php') {echo 'active';} ?>"><a href="transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                <li class="<?php if ($currentPage == 'referrals.php') {echo 'active';} ?>"><a href="referrals.php"><i class="fas fa-users"></i> Referrals</a></li>
                <li class="<?php if ($currentPage == 'profile.php') {echo 'active';} ?>"><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li class="<?php if ($currentPage == 'kyc.php') {echo 'active';} ?>"><a href="kyc.php"><i class="fas fa-id-card"></i> KYC Verification</a></li>
                <li class="<?php if ($currentPage == 'notifications.php') {echo 'active';} ?>"><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content">
            <script>
                function logout() {
                    auth.signOut().catch(error => console.error("Logout Error:", error));
                }

                // Auth Guard
                auth.onAuthStateChanged(user => {
                    if (!user) {
                        window.location.href = 'auth/login.html';
                    } else {
                        const welcomeEl = document.getElementById('user-welcome');
                        db.collection('users').doc(user.uid).get().then(doc => {
                            if (doc.exists && welcomeEl) {
                                welcomeEl.textContent = `Welcome, ${doc.data().fullname.split(' ')[0]}`;
                            }
                        });
                    }
                });
            </script>