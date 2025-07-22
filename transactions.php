<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>Transactions</h1>
</div>

<div class="card">
    <h2>Transaction History</h2>
    <div class="data-table-wrapper">
        <table class="data-table" id="transactions-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Transactions will be loaded here by JavaScript -->
                <tr><td colspan="5" style="text-align: center;">Loading transaction history...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const transactionsTableBody = document.querySelector('#transactions-table tbody');

    auth.onAuthStateChanged(user => {
        if (user) {
            // Listen for real-time updates on transactions
            db.collection('transactions')
              .where('userId', '==', user.uid)
              .orderBy('date', 'desc')
              .onSnapshot((querySnapshot) => {
                if (querySnapshot.empty) {
                    transactionsTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">You have no transactions yet.</td></tr>';
                    return;
                }

                let transactionsHtml = '';
                querySnapshot.forEach((doc) => {
                    const tx = doc.data();
                    const date = tx.date ? tx.date.toDate().toLocaleString() : 'N/A';
                    const amountClass = tx.amount >= 0 ? 'amount-positive' : 'amount-negative';
                    const amountPrefix = tx.amount >= 0 ? '+' : '-';
                    const formattedAmount = Math.abs(tx.amount).toLocaleString('en-US', { style: 'currency', currency: 'USD' });

                    transactionsHtml += `
                        <tr>
                            <td>${date}</td>
                            <td style="text-transform: capitalize;">${tx.type || 'N/A'}</td>
                            <td class="${amountClass}">${amountPrefix} ${formattedAmount}</td>
                            <td>${tx.description || 'N/A'}</td>
                            <td><span class="status-badge status-${tx.status || 'unknown'}">${tx.status || 'N/A'}</span></td>
                        </tr>
                    `;
                });
                transactionsTableBody.innerHTML = transactionsHtml;

            }, (error) => {
                console.error("Error getting transactions: ", error);
                transactionsTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Could not load transactions. Please try again.</td></tr>';
            });
        }
    });
});
</script>

<?php include 'dashboard_footer.php'; ?>