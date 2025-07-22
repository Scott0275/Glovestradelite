<?php
include 'dashboard_header.php'; 
?>

<div class="header">
    <h1>Dashboard</h1>
    <div class="user-info">
        <span id="user-email-display"></span>
    </div>
</div>

<div class="card">
    <h2>Account Summary</h2>
    <p>Your current earnings: <strong id="user-earnings-display">Loading...</strong></p>
</div>

<div class="card">
    <h2>Recent Activity</h2>
    <p>No recent activity to show.</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    auth.onAuthStateChanged(user => {
        if (user) {
            // User is signed in.
            const uid = user.uid;
            const emailDisplay = document.getElementById('user-email-display');
            const earningsDisplay = document.getElementById('user-earnings-display');

            if(emailDisplay) emailDisplay.textContent = user.email;

            // Fetch user data from Firestore 'users' collection using onSnapshot for real-time updates
            db.collection('users').doc(uid).onSnapshot((doc) => {
                if (doc.exists) {
                    const userData = doc.data();
                    const earnings = userData.earnings || 0;
                    if(earningsDisplay) earningsDisplay.textContent = `$${parseFloat(earnings).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                } else {
                    console.log("User document not found in Firestore!");
                    if(earningsDisplay) earningsDisplay.textContent = '$0.00';
                }
            }, (error) => {
                console.error("Error getting user data:", error);
                if(earningsDisplay) earningsDisplay.textContent = 'Error';
            });
        }
    });
});
</script>

<?php include 'dashboard_footer.php'; ?>