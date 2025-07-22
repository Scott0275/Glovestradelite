<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>Withdraw Funds</h1>
</div>

<div class="card">
    <div id="withdraw-container">
        <!-- Content will be dynamically inserted here based on KYC status -->
        <p>Loading your account details...</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('withdraw-container');

    auth.onAuthStateChanged(user => {
        if (user) {
            const userDocRef = db.collection('users').doc(user.uid);

            userDocRef.onSnapshot(doc => {
                if (doc.exists) {
                    const userData = doc.data();
                    const kycStatus = userData.kycStatus;
                    const earnings = userData.earnings || 0;

                    if (kycStatus === 'verified') {
                        renderWithdrawalForm(container, earnings);
                    } else {
                        renderKycNotice(container, kycStatus);
                    }
                } else {
                    container.innerHTML = '<p>Could not find user data.</p>';
                }
            }, error => {
                console.error("Error fetching user data:", error);
                container.innerHTML = '<p>Error loading account details. Please try again.</p>';
            });
        }
    });
});

function renderKycNotice(container, status) {
    let noticeHtml = '';
    if (status === 'pending') {
        noticeHtml = `
            <div class="alert alert-info">
                <strong>Your documents are under review.</strong>
                <p>You will be able to make withdrawals once your account is verified. This usually takes 24-48 hours.</p>
            </div>`;
    } else {
        noticeHtml = `
            <div class="alert alert-warning">
                <strong>KYC Verification Required</strong>
                <p>You must complete KYC verification before you can make a withdrawal. Please submit your documents.</p>
                <a href="kyc.php" class="btn">Go to KYC Page</a>
            </div>`;
    }
    container.innerHTML = noticeHtml;
}

function renderWithdrawalForm(container, earnings) {
    container.innerHTML = `
        <p>Your available balance: <strong>$${earnings.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong></p>
        <hr style="margin: 20px 0;">
        <h2>Withdrawal Form</h2>
        <form id="withdrawalForm">
            <div class="form-group">
                <label for="amount">Amount (USD)</label>
                <input type="number" id="amount" class="form-control" placeholder="0.00" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="method">Withdrawal Method</label>
                <select id="method" class="form-control" required>
                    <option value="">-- Select Method --</option>
                    <option value="Bitcoin">Bitcoin</option>
                    <option value="Ethereum">Ethereum</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="details">Wallet Address / Bank Details</label>
                <textarea id="details" class="form-control" rows="3" placeholder="Enter your wallet address or bank account details here" required></textarea>
            </div>
            <button type="submit" id="submitWithdrawalBtn" class="btn">Submit Request</button>
        </form>
    `;

    const withdrawalForm = document.getElementById('withdrawalForm');
    withdrawalForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const submitBtn = document.getElementById('submitWithdrawalBtn');
        const amount = parseFloat(document.getElementById('amount').value);
        const method = document.getElementById('method').value;
        const details = document.getElementById('details').value.trim();

        if (isNaN(amount) || amount <= 0) {
            Swal.fire('Invalid Amount', 'Please enter a valid amount to withdraw.', 'error');
            return;
        }
        if (amount > earnings) {
            Swal.fire('Insufficient Funds', 'You cannot withdraw more than your available balance.', 'error');
            return;
        }
        if (!method || !details) {
            Swal.fire('Missing Information', 'Please select a method and provide your details.', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        const currentUser = auth.currentUser;
        if (currentUser) {
            db.collection('withdrawal_requests').add({
                userId: currentUser.uid,
                email: currentUser.email,
                amount: amount,
                method: method,
                details: details,
                status: 'pending', // pending, approved, rejected
                requestedAt: firebase.firestore.FieldValue.serverTimestamp()
            }).then(() => {
                Swal.fire('Success', 'Your withdrawal request has been submitted successfully.', 'success');
                withdrawalForm.reset();
            }).catch(error => {
                console.error("Error submitting withdrawal: ", error);
                Swal.fire('Error', 'There was a problem submitting your request. Please try again.', 'error');
            }).finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Request';
            });
        }
    });
}
</script>

<?php include 'dashboard_footer.php'; ?>