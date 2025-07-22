<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>Deposit Funds</h1>
</div>

<div class="card">
    <h2>How to Deposit</h2>
    <p>To fund your account, please send the desired amount of cryptocurrency to the corresponding wallet address below. After sending, your balance will be updated manually by an administrator once the transaction is confirmed on the network.</p>
    <p><strong>Important:</strong> Only send the correct cryptocurrency to its designated address. Sending any other currency may result in the permanent loss of your funds.</p>
</div>

<div class="deposit-grid" id="wallets-container">
    <!-- Wallets will be dynamically loaded here -->
    <div class="spinner-container">
        <div class="spinner"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const walletsContainer = document.getElementById('wallets-container');

    auth.onAuthStateChanged(user => {
        if (user) {
            db.collection("wallets").orderBy("name").get().then((querySnapshot) => {
                walletsContainer.innerHTML = ''; // Clear spinner
                if (querySnapshot.empty) {
                    walletsContainer.innerHTML = '<p>No deposit methods are available at the moment. Please check back later.</p>';
                    return;
                }

                querySnapshot.forEach((doc) => {
                    const wallet = doc.data();
                    const walletCard = `
                        <div class="deposit-card">
                            <div class="deposit-card-header">
                                <img src="${wallet.icon_url}" alt="${wallet.name} logo">
                                <h3>${wallet.name} <small>(${wallet.symbol})</small></h3>
                            </div>
                            <div class="wallet-address-wrapper">
                                <label><strong>${wallet.name} Address:</strong></label>
                                <div class="wallet-address">${wallet.address}</div>
                                <button class="btn" onclick="copyAddress('${wallet.address}', '${wallet.name}')">Copy Address</button>
                            </div>
                        </div>
                    `;
                    walletsContainer.innerHTML += walletCard;
                });
            }).catch((error) => {
                console.error("Error fetching wallets: ", error);
                walletsContainer.innerHTML = '<p>Could not load deposit information. Please try again later.</p>';
            });
        }
    });
});

function copyAddress(address, name) {
    navigator.clipboard.writeText(address).then(() => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: `${name} address copied!`,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    }).catch(err => {
        console.error(`Failed to copy ${name} address: `, err);
        Swal.fire('Error!', 'Failed to copy the address. Please copy it manually.', 'error');
    });
}
</script>

<?php include 'dashboard_footer.php'; ?>