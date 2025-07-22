<?php
include 'dashboard_header.php'; 
?>

<div class="header">
    <h1>Dashboard</h1>
    <div class="user-info">
        <span id="user-email-display">Loading...</span>
    </div>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="icon"><i class="fas fa-wallet"></i></div>
        <div class="title">Total Balance</div>
        <div class="value" id="user-earnings-display">$0.00</div>
    </div>
    <div class="summary-card">
        <div class="icon"><i class="fas fa-download"></i></div>
        <div class="title">Deposit Funds</div>
        <div class="value" style="font-size: 1rem; margin-top: 10px;">
            <a href="deposit.php" class="btn">Deposit Now</a>
        </div>
    </div>
    <div class="summary-card">
        <div class="icon"><i class="fas fa-id-card"></i></div>
        <div class="title">KYC Status</div>
        <div class="value"><span id="kyc-status-display" class="kyc-status not_submitted">Not Submitted</span></div>
    </div>
    <div class="summary-card">
        <div class="icon"><i class="fas fa-users"></i></div>
        <div class="title">Referral Link</div>
        <div class="value" style="font-size: 1rem; margin-top: 10px;">
            <a href="referrals.php" class="btn">Get Link</a>
        </div>
    </div>
</div>

<div class="card">
    <h2>Market Overview</h2>
    <!-- TradingView Widget BEGIN -->
    <div class="tradingview-widget-container" style="height:400px; width:100%;">
      <div id="tradingview_c89c3" style="height:calc(100% - 32px); width:100%;"></div>
      <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
      <script type="text/javascript">
      new TradingView.widget(
      {
      "autosize": true,
      "symbol": "NASDAQ:AAPL",
      "interval": "D",
      "timezone": "Etc/UTC",
      "theme": "light",
      "style": "1",
      "locale": "en",
      "enable_publishing": false,
      "allow_symbol_change": true,
      "container_id": "tradingview_c89c3"
    }
      );
      </script>
    </div>
    <!-- TradingView Widget END -->
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    auth.onAuthStateChanged(user => {
        if (user) {
            const uid = user.uid;
            const emailDisplay = document.getElementById('user-email-display');
            const earningsDisplay = document.getElementById('user-earnings-display');
            const kycStatusDisplay = document.getElementById('kyc-status-display');

            if(emailDisplay) emailDisplay.textContent = user.email;

            db.collection('users').doc(uid).onSnapshot((doc) => {
                if (doc.exists) {
                    const userData = doc.data();
                    
                    // Update Earnings
                    const earnings = userData.earnings || 0;
                    if(earningsDisplay) {
                        earningsDisplay.textContent = `$${parseFloat(earnings).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }

                    // Update KYC Status
                    const kycStatus = userData.kycStatus || 'not_submitted';
                    if (kycStatusDisplay) {
                        kycStatusDisplay.textContent = kycStatus.replace('_', ' ');
                        kycStatusDisplay.className = `kyc-status ${kycStatus}`;
                    }

                } else {
                    console.log("User document not found in Firestore!");
                    if(earningsDisplay) earningsDisplay.textContent = '$0.00';
                    if(kycStatusDisplay) {
                        kycStatusDisplay.textContent = 'Not Submitted';
                        kycStatusDisplay.className = 'kyc-status not_submitted';
                    }
                }
            }, (error) => {
                console.error("Error getting user data:", error);
                if(earningsDisplay) earningsDisplay.textContent = 'Error';
                if(kycStatusDisplay) kycStatusDisplay.textContent = 'Error';
            });
        }
    });
});
</script>

<?php include 'dashboard_footer.php'; ?>