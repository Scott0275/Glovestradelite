<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Glove TradeLite</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.svg">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
</head>
<body>
    <div class="main-wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="index.html"><img src="assets/img/logo/logo.svg" alt="Logo" class="logo"></a>
                <h3 id="user-welcome">Admin Panel</h3>
            </div>
            <ul class="sidebar-nav">
                <li><a href="#" class="tab-link active" onclick="openTab(event, 'users')"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="#" class="tab-link" onclick="openTab(event, 'kyc')"><i class="fas fa-id-card"></i> KYC Requests</a></li>
                <li><a href="#" class="tab-link" onclick="openTab(event, 'withdrawals')"><i class="fas fa-wallet"></i> Withdrawals</a></li>
                <li><a href="#" class="tab-link" onclick="openTab(event, 'notifications')"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="#" class="tab-link" onclick="openTab(event, 'plans')"><i class="fas fa-chart-line"></i> Investment Plans</a></li>
                <li><a href="#" class="tab-link" onclick="openTab(event, 'wallets')"><i class="fab fa-bitcoin"></i> Wallets</a></li>
            </ul>
            <div class="sidebar-footer" style="margin-top: auto;">
                <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content" id="admin-content" style="display: none;">
            <div class="header">
                <h1>Admin Dashboard</h1>
            </div>

            <!-- Users Tab -->
            <div id="users" class="tab-content active">
                <h2>User Management</h2>
                <div class="card">
                    <table class="admin-table" id="users-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Earnings (USD)</th>
                                <th>Referred By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody><!-- User data will be inserted here --></tbody>
                    </table>
                </div>
            </div>

            <!-- KYC Tab -->
            <div id="kyc" class="tab-content">
                <h2>Pending KYC Submissions</h2>
                <div class="card">
                    <table class="admin-table" id="kyc-table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Submitted At</th>
                                <th>Documents</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody><!-- KYC requests will be inserted here --></tbody>
                    </table>
                </div>
            </div>

            <!-- Withdrawals Tab -->
            <div id="withdrawals" class="tab-content">
                <h2>Pending Withdrawal Requests</h2>
                <div class="card">
                    <table class="admin-table" id="withdrawals-table">
                         <thead>
                            <tr>
                                <th>Email</th>
                                <th>Amount (USD)</th>
                                <th>Method</th>
                                <th>Details</th>
                                <th>Requested At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody><!-- Withdrawal requests will be inserted here --></tbody>
                    </table>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div id="notifications" class="tab-content">
                <h2>Send Notification to All Users</h2>
                <div class="card">
                    <form id="notificationForm">
                        <div class="form-group">
                            <label for="notificationMessage">Message</label>
                            <textarea id="notificationMessage" class="form-control" rows="4" placeholder="Enter your notification message here..." required></textarea>
                        </div>
                        <button type="submit" class="btn">Send Notification</button>
                    </form>
                </div>

                <h2 style="margin-top: 30px;">Sent Notifications</h2>
                <div class="card">
                    <table class="admin-table" id="notifications-table">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Message</th>
                                <th>Sent At</th>
                            </tr>
                        </thead>
                        <tbody><!-- Sent notifications will be inserted here --></tbody>
                    </table>
                </div>
            </div>

            <!-- Investment Plans Tab -->
            <div id="plans" class="tab-content">
                <h2>Manage Investment Plans</h2>
                <div class="card">
                    <h3 id="plan-form-title">Add/Edit Plan</h3>
                    <form id="planForm">
                        <input type="hidden" id="planId">
                        <div class="form-group">
                            <label for="planName">Plan Name</label>
                            <input type="text" id="planName" class="form-control" required>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label for="minDeposit">Min Deposit (USD)</label>
                                <input type="number" id="minDeposit" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="maxDeposit">Max Deposit (USD)</label>
                                <input type="number" id="maxDeposit" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="roiPercentage">ROI (%)</label>
                                <input type="number" id="roiPercentage" class="form-control" step="0.1" required>
                            </div>
                            <div class="form-group">
                                <label for="durationDays">Duration (Days)</label>
                                <input type="number" id="durationDays" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn">Save Plan</button>
                        <button type="button" class="btn btn-secondary" onclick="clearPlanForm()">New Plan</button>
                    </form>
                </div>

                <h2 style="margin-top: 30px;">Existing Plans</h2>
                <div class="card">
                    <table class="admin-table" id="plans-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Min Deposit</th>
                                <th>Max Deposit</th>
                                <th>ROI (%)</th>
                                <th>Duration (Days)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody><!-- Plans will be inserted here --></tbody>
                    </table>
                </div>
            </div>

            <!-- Wallets Tab -->
            <div id="wallets" class="tab-content">
                <h2>Manage Deposit Wallets</h2>
                <div class="card">
                    <h3 id="wallet-form-title">Add/Edit Wallet</h3>
                    <form id="walletForm">
                        <input type="hidden" id="walletId">
                        <div class="form-group">
                            <label for="walletName">Wallet Name (e.g., Bitcoin)</label>
                            <input type="text" id="walletName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="walletSymbol">Symbol (e.g., BTC)</label>
                            <input type="text" id="walletSymbol" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="walletAddress">Wallet Address</label>
                            <input type="text" id="walletAddress" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="walletIconUrl">Icon URL</label>
                            <input type="url" id="walletIconUrl" class="form-control" placeholder="https://..." required>
                        </div>
                        <button type="submit" class="btn">Save Wallet</button>
                        <button type="button" class="btn btn-secondary" onclick="clearWalletForm()">New Wallet</button>
                    </form>
                </div>

                <h2 style="margin-top: 30px;">Existing Wallets</h2>
                <div class="card">
                    <table class="admin-table" id="wallets-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Symbol</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody><!-- Wallets will be inserted here --></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>
    <script src="assets/js/firebase-config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function logout() {
            auth.signOut().catch(error => console.error("Logout Error:", error));
        }

        function openTab(evt, tabName) {
            let i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.querySelectorAll(".sidebar-nav .tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // --- Auth Guard ---
        auth.onAuthStateChanged(user => {
            if (user) {
                const adminEmails = ['oscarscott2411@gmail.com', 'admin@glovestrade.com'];
                if (user.email && adminEmails.includes(user.email)) {
                    document.getElementById('admin-content').style.display = 'block';
                    loadAllData();
                } else {
                    alert('Access Denied. You are not an administrator.');
                    window.location.href = 'auth/login.html';
                }
            } else {
                window.location.href = 'auth/login.html';
            }
        });

        function loadAllData() {
            fetchAllUsers();
            fetchKycRequests();
            fetchWithdrawalRequests();
            fetchSentNotifications();
            fetchInvestmentPlans();
            fetchWallets();
        }

        // --- User Management ---
        async function fetchAllUsers() {
            const usersTable = document.getElementById('users-table').getElementsByTagName('tbody')[0];
            usersTable.innerHTML = '<tr><td colspan="5">Loading users...</td></tr>';
            const usersSnapshot = await db.collection('users').get();
            
            // Create a map of UID to email for referral lookup
            const userEmailMap = new Map();
            usersSnapshot.forEach(doc => userEmailMap.set(doc.id, doc.data().email));

            let usersHtml = '';
            usersSnapshot.forEach(doc => {
                const user = doc.data();
                const referredByEmail = user.referred_by ? (userEmailMap.get(user.referred_by) || 'Unknown') : 'N/A';
                usersHtml += `
                    <tr>
                        <td>${user.fullname || 'N/A'}</td>
                        <td>${user.email}</td>
                        <td>
                            <input type="number" id="earnings-${doc.id}" value="${user.earnings || 0}" step="0.01" class="form-control" style="width: 120px; display: inline-block;">
                            <button class="btn" onclick="updateUserEarnings('${doc.id}')">Update</button>
                        </td>
                        <td>${referredByEmail}</td>
                        <td>
                            <button class="btn" onclick="resetUserEarnings('${doc.id}')">Reset Earnings</button>
                            <button class="btn btn-danger" onclick="deleteUser('${doc.id}', '${user.email}')">Delete</button>
                        </td>
                    </tr>
                `;
            });
            usersTable.innerHTML = usersHtml || '<tr><td colspan="5">No users found.</td></tr>';
        }

        function updateUserEarnings(uid) {
            const newEarnings = parseFloat(document.getElementById(`earnings-${uid}`).value);
            if (isNaN(newEarnings)) {
                Swal.fire('Error', 'Invalid earnings amount.', 'error');
                return;
            }
            db.collection('users').doc(uid).update({ earnings: newEarnings })
                .then(() => Swal.fire('Success', 'Earnings updated successfully.', 'success'))
                .catch(err => Swal.fire('Error', 'Failed to update earnings: ' + err.message, 'error'));
        }

        function resetUserEarnings(uid) {
             Swal.fire({
                title: 'Are you sure?',
                text: "This will reset the user's earnings to $0. This cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reset it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    db.collection('users').doc(uid).update({ earnings: 0 })
                        .then(() => {
                            Swal.fire('Reset!', 'User earnings have been reset.', 'success');
                            document.getElementById(`earnings-${uid}`).value = 0;
                        })
                        .catch(err => Swal.fire('Error', 'Failed to reset earnings: ' + err.message, 'error'));
                }
            });
        }

        function deleteUser(uid, email) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete the user ${email}. This action is irreversible!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Note: Deleting from Firestore does not delete from Firebase Auth.
                    // This should ideally be handled by a Cloud Function for security.
                    db.collection('users').doc(uid).delete()
                        .then(() => {
                            Swal.fire('Deleted!', 'User has been deleted from Firestore.', 'success');
                            fetchAllUsers(); // Refresh the list
                        })
                        .catch(err => Swal.fire('Error', 'Failed to delete user: ' + err.message, 'error'));
                }
            });
        }

        // --- KYC Management ---
        function fetchKycRequests() {
            const kycTable = document.getElementById('kyc-table').getElementsByTagName('tbody')[0];
            db.collection('users').where('kycStatus', '==', 'pending').onSnapshot(snapshot => {
                let kycHtml = '';
                snapshot.forEach(doc => {
                    const user = doc.data();
                    const submittedAt = user.kycSubmittedAt ? user.kycSubmittedAt.toDate().toLocaleString() : 'N/A';
                    kycHtml += `
                        <tr>
                            <td>${user.email}</td>
                            <td>${submittedAt}</td>
                            <td class="document-links">
                                <a href="${user.kycDocs.id_front}" target="_blank">ID Front</a>
                                <a href="${user.kycDocs.id_back}" target="_blank">ID Back</a>
                                <a href="${user.kycDocs.selfie}" target="_blank">Selfie</a>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="approveKyc('${doc.id}')">Approve</button>
                                <button class="btn btn-danger" onclick="rejectKyc('${doc.id}')">Reject</button>
                            </td>
                        </tr>
                    `;
                });
                kycTable.innerHTML = kycHtml || '<tr><td colspan="4">No pending KYC requests.</td></tr>';
            });
        }

        function approveKyc(uid) {
            db.collection('users').doc(uid).update({ kycStatus: 'verified' })
                .then(() => Swal.fire('Approved', 'KYC has been approved.', 'success'))
                .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        // --- Notification Management ---
        function fetchSentNotifications() {
            const notificationsTable = document.getElementById('notifications-table').getElementsByTagName('tbody')[0];
            db.collection('notifications').orderBy('sentAt', 'desc').onSnapshot(snapshot => {
                let notificationsHtml = '';
                snapshot.forEach(doc => {
                    const notification = doc.data();
                    const sentAt = notification.sentAt ? notification.sentAt.toDate().toLocaleString() : 'N/A';
                    notificationsHtml += `
                        <tr>
                            <td>${notification.message}</td>
                            <td>${sentAt}</td>
                        </tr>
                    `;
                });
                notificationsTable.innerHTML = notificationsHtml || '<tr><td colspan="2">No notifications sent yet.</td></tr>';
            });
        }

        document.getElementById('notificationForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const messageInput = document.getElementById('notificationMessage');
            const message = messageInput.value.trim();

            if (!message) {
                Swal.fire('Error', 'Notification message cannot be empty.', 'error');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "This will send a notification to all users.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    db.collection('notifications').add({
                        message: message,
                        sentAt: firebase.firestore.FieldValue.serverTimestamp()
                    }).then(() => {
                        Swal.fire('Sent!', 'The notification has been sent to all users.', 'success');
                        messageInput.value = '';
                    }).catch(err => Swal.fire('Error', 'Failed to send notification: ' + err.message, 'error'));
                }
            });
        });

        // --- Investment Plan Management ---
        function fetchInvestmentPlans() {
            const plansTable = document.getElementById('plans-table').getElementsByTagName('tbody')[0];
            db.collection('investment_plans').orderBy('min_deposit', 'asc').onSnapshot(snapshot => {
                let plansHtml = '';
                snapshot.forEach(doc => {
                    const plan = doc.data();
                    plansHtml += `
                        <tr>
                            <td>${plan.name}</td>
                            <td>$${plan.min_deposit.toLocaleString()}</td>
                            <td>$${plan.max_deposit.toLocaleString()}</td>
                            <td>${plan.roi_percentage}%</td>
                            <td>${plan.duration_days}</td>
                            <td>
                                <button class="btn" onclick="editPlan('${doc.id}')">Edit</button>
                                <button class="btn btn-danger" onclick="deletePlan('${doc.id}')">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                plansTable.innerHTML = plansHtml || '<tr><td colspan="6" style="text-align:center;">No investment plans found.</td></tr>';
            });
        }

        async function editPlan(id) {
            try {
                const docRef = db.collection('investment_plans').doc(id);
                const doc = await docRef.get();
                if (!doc.exists) {
                    return Swal.fire('Error', 'Plan not found.', 'error');
                }
                const plan = doc.data();
                document.getElementById('planId').value = id;
                document.getElementById('planName').value = plan.name;
                document.getElementById('minDeposit').value = plan.min_deposit;
                document.getElementById('maxDeposit').value = plan.max_deposit;
                document.getElementById('roiPercentage').value = plan.roi_percentage;
                document.getElementById('durationDays').value = plan.duration_days;
            document.getElementById('plan-form-title').textContent = 'Edit Plan';
                document.getElementById('planName').focus();
            } catch (err) {
                Swal.fire('Error', 'Failed to fetch plan details: ' + err.message, 'error');
            }
        }

        function clearPlanForm() {
            document.getElementById('planForm').reset();
            document.getElementById('planId').value = '';
            document.getElementById('plan-form-title').textContent = 'Add New Plan';
        }

        function deletePlan(id) {
            Swal.fire({
                title: 'Are you sure?', text: "This will delete the investment plan.", icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    db.collection('investment_plans').doc(id).delete()
                        .then(() => Swal.fire('Deleted!', 'Plan has been deleted.', 'success'))
                        .catch(err => Swal.fire('Error', 'Failed to delete plan: ' + err.message, 'error'));
                }
            });
        }

        // --- Wallet Management ---
        function fetchWallets() {
            const walletsTable = document.getElementById('wallets-table').getElementsByTagName('tbody')[0];
            db.collection('wallets').orderBy('name').onSnapshot(snapshot => {
                let walletsHtml = '';
                snapshot.forEach(doc => {
                    const wallet = doc.data();
                    walletsHtml += `
                        <tr>
                            <td>${wallet.name}</td>
                            <td>${wallet.symbol}</td>
                            <td>${wallet.address}</td>
                            <td>
                                <button class="btn" onclick="editWallet('${doc.id}')">Edit</button>
                                <button class="btn btn-danger" onclick="deleteWallet('${doc.id}')">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                walletsTable.innerHTML = walletsHtml || '<tr><td colspan="4" style="text-align:center;">No wallets found.</td></tr>';
            });
        }

        async function editWallet(id) {
            try {
                const doc = await db.collection('wallets').doc(id).get();
                if (!doc.exists) return Swal.fire('Error', 'Wallet not found.', 'error');
                const wallet = doc.data();
                document.getElementById('walletId').value = id;
                document.getElementById('walletName').value = wallet.name;
                document.getElementById('walletSymbol').value = wallet.symbol;
                document.getElementById('walletAddress').value = wallet.address;
                document.getElementById('walletIconUrl').value = wallet.icon_url;
                document.getElementById('wallet-form-title').textContent = 'Edit Wallet';
                document.getElementById('walletName').focus();
            } catch (err) {
                Swal.fire('Error', 'Failed to fetch wallet details: ' + err.message, 'error');
            }
        }

        function clearWalletForm() {
            document.getElementById('walletForm').reset();
            document.getElementById('walletId').value = '';
            document.getElementById('wallet-form-title').textContent = 'Add New Wallet';
        }

        function deleteWallet(id) {
            Swal.fire({
                title: 'Are you sure?', text: "This will delete the wallet.", icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    db.collection('wallets').doc(id).delete()
                        .then(() => Swal.fire('Deleted!', 'Wallet has been deleted.', 'success'))
                        .catch(err => Swal.fire('Error', 'Failed to delete wallet: ' + err.message, 'error'));
                }
            });
        }


        // --- Event Listeners (attached once) ---
        document.addEventListener('DOMContentLoaded', () => {
            // Notification Form
            document.getElementById('notificationForm').addEventListener('submit', (e) => {
                e.preventDefault();
                const messageInput = document.getElementById('notificationMessage');
                const message = messageInput.value.trim();
                if (!message) return Swal.fire('Error', 'Notification message cannot be empty.', 'error');

                Swal.fire({
                    title: 'Are you sure?', text: "This will send a notification to all users.", icon: 'warning',
                    showCancelButton: true, confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        db.collection('notifications').add({
                            message: message,
                            sentAt: firebase.firestore.FieldValue.serverTimestamp()
                        }).then(() => {
                            Swal.fire('Sent!', 'The notification has been sent.', 'success');
                            messageInput.value = '';
                        }).catch(err => Swal.fire('Error', 'Failed to send notification: ' + err.message, 'error'));
                    }
                });
            });

            // Investment Plan Form
            document.getElementById('planForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const planId = document.getElementById('planId').value;
                const plan = {
                    name: document.getElementById('planName').value,
                    min_deposit: parseFloat(document.getElementById('minDeposit').value),
                    max_deposit: parseFloat(document.getElementById('maxDeposit').value),
                    roi_percentage: parseFloat(document.getElementById('roiPercentage').value),
                    duration_days: parseInt(document.getElementById('durationDays').value)
                };

                if (isNaN(plan.min_deposit) || isNaN(plan.max_deposit) || isNaN(plan.roi_percentage) || isNaN(plan.duration_days)) {
                    return Swal.fire('Error', 'Please enter valid numbers for plan details.', 'error');
                }

                try {
                    if (planId) {
                        await db.collection('investment_plans').doc(planId).update(plan);
                        Swal.fire('Success', 'Plan updated successfully.', 'success');
                    } else {
                        await db.collection('investment_plans').add(plan);
                        Swal.fire('Success', 'New plan added successfully.', 'success');
                    }
                    clearPlanForm();
                } catch (err) {
                    Swal.fire('Error', 'Failed to save plan: ' + err.message, 'error');
                }
            });

            // Wallet Form
            document.getElementById('walletForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const walletId = document.getElementById('walletId').value;
                const wallet = {
                    name: document.getElementById('walletName').value,
                    symbol: document.getElementById('walletSymbol').value,
                    address: document.getElementById('walletAddress').value,
                    icon_url: document.getElementById('walletIconUrl').value
                };

                try {
                    if (walletId) {
                        await db.collection('wallets').doc(walletId).update(wallet);
                        Swal.fire('Success', 'Wallet updated successfully.', 'success');
                    } else {
                        await db.collection('wallets').add(wallet);
                        Swal.fire('Success', 'New wallet added successfully.', 'success');
                    }
                    clearWalletForm();
                } catch (err) {
                    Swal.fire('Error', 'Failed to save wallet: ' + err.message, 'error');
                }
            });
        });
    </script>
</body>
</html>