<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
</head>
<body>
    <div class="main-wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="assets/img/logo/logo.svg" alt="Logo" class="logo">
                <h3 id="user-welcome">Admin Panel</h3>
            </div>
            <div class="sidebar-footer" style="margin-top: auto;">
                <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content" id="admin-content" style="display: none;">
            <div class="header">
                <h1>Admin Dashboard</h1>
            </div>

            <div class="admin-tabs">
                <button class="tab-link active" onclick="openTab(event, 'users')">Users</button>
                <button class="tab-link" onclick="openTab(event, 'kyc')">KYC Requests</button>
                <button class="tab-link" onclick="openTab(event, 'withdrawals')">Withdrawal Requests</button>
            </div>

            <!-- Users Tab -->
            <div id="users" class="tab-content active">
                <h2>User Management</h2>
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

            <!-- KYC Tab -->
            <div id="kyc" class="tab-content">
                <h2>Pending KYC Submissions</h2>
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

            <!-- Withdrawals Tab -->
            <div id="withdrawals" class="tab-content">
                <h2>Pending Withdrawal Requests</h2>
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
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
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

        async function rejectKyc(uid) {
            const { value: reason } = await Swal.fire({
                title: 'Enter rejection reason',
                input: 'text',
                inputLabel: 'Reason',
                inputPlaceholder: 'e.g., Blurry ID photo',
                showCancelButton: true
            });

            if (reason) {
                db.collection('users').doc(uid).update({ kycStatus: 'rejected', kycRejectReason: reason })
                    .then(() => Swal.fire('Rejected', 'KYC has been rejected.', 'success'))
                    .catch(err => Swal.fire('Error', err.message, 'error'));
            }
        }

        // --- Withdrawal Management ---
        function fetchWithdrawalRequests() {
            const wTable = document.getElementById('withdrawals-table').getElementsByTagName('tbody')[0];
            db.collection('withdrawal_requests').where('status', '==', 'pending').onSnapshot(snapshot => {
                let wHtml = '';
                snapshot.forEach(doc => {
                    const req = doc.data();
                    const requestedAt = req.requestedAt ? req.requestedAt.toDate().toLocaleString() : 'N/A';
                    wHtml += `
                        <tr>
                            <td>${req.email}</td>
                            <td>$${req.amount.toFixed(2)}</td>
                            <td>${req.method}</td>
                            <td>${req.details}</td>
                            <td>${requestedAt}</td>
                            <td>
                                <button class="btn btn-success" onclick="approveWithdrawal('${doc.id}', '${req.userId}', ${req.amount})">Approve</button>
                                <button class="btn btn-danger" onclick="rejectWithdrawal('${doc.id}')">Reject</button>
                            </td>
                        </tr>
                    `;
                });
                wTable.innerHTML = wHtml || '<tr><td colspan="6">No pending withdrawal requests.</td></tr>';
            });
        }

        function approveWithdrawal(reqId, userId, amount) {
            const userRef = db.collection('users').doc(userId);
            const withdrawalRef = db.collection('withdrawal_requests').doc(reqId);

            db.runTransaction(transaction => {
                return transaction.get(userRef).then(userDoc => {
                    if (!userDoc.exists) {
                        throw "User not found!";
                    }
                    const newEarnings = (userDoc.data().earnings || 0) - amount;
                    if (newEarnings < 0) {
                        throw "User has insufficient funds!";
                    }
                    transaction.update(userRef, { earnings: newEarnings });
                    transaction.update(withdrawalRef, { status: 'approved' });
                });
            }).then(() => {
                Swal.fire('Approved!', 'Withdrawal approved and earnings updated.', 'success');
            }).catch(err => {
                Swal.fire('Transaction Failed', err.toString(), 'error');
            });
        }

        function rejectWithdrawal(reqId) {
            db.collection('withdrawal_requests').doc(reqId).update({ status: 'rejected' })
                .then(() => Swal.fire('Rejected', 'Withdrawal request has been rejected.', 'success'))
                .catch(err => Swal.fire('Error', err.message, 'error'));
        }

    </script>
</body>
</html>