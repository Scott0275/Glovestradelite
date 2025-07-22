<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>Notifications</h1>
</div>

<div id="notifications-list">
    <!-- Loading or no notifications message will be shown here -->
    <div class="card">
        <p>Loading notifications...</p>
    </div>
</div>

<style>
.notification-item {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    border-left: 5px solid #3498db;
}
.notification-item p {
    margin: 0 0 10px 0;
    font-size: 16px;
    line-height: 1.5;
}
.notification-item .date {
    font-size: 12px;
    color: #777;
    text-align: right;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const notificationsList = document.getElementById('notifications-list');

    auth.onAuthStateChanged(user => {
        if (user) {
            db.collection("notifications").orderBy("sentAt", "desc").onSnapshot((querySnapshot) => {
                if (querySnapshot.empty) {
                    notificationsList.innerHTML = `
                        <div class="card">
                            <h2>Your Notifications</h2>
                            <p>You have no new notifications.</p>
                        </div>`;
                    return;
                }

                let notificationsHtml = '';
                querySnapshot.forEach((doc) => {
                    const notification = doc.data();
                    const sentAt = notification.sentAt ? notification.sentAt.toDate().toLocaleString() : 'Just now';
                    notificationsHtml += `
                        <div class="notification-item">
                            <p>${notification.message.replace(/\n/g, '<br>')}</p>
                            <div class="date">${sentAt}</div>
                        </div>
                    `;
                });
                notificationsList.innerHTML = notificationsHtml;

            }, (error) => {
                console.error("Error getting notifications: ", error);
                notificationsList.innerHTML = '<div class="card"><p>Could not load notifications. Please try again later.</p></div>';
            });
        }
    });
});
</script>

<?php include 'dashboard_footer.php'; ?>