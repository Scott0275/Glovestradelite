<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>Referrals</h1>
</div>

<div class="card">
    <h2>Referral Program</h2>
    <p>Share your unique referral link with friends and earn rewards when they sign up and invest.</p>
    <div class="referral-link-wrapper">
        <input type="text" id="referralLink" class="form-control" readonly placeholder="Generating your link...">
        <button id="copyBtn" class="btn">Copy</button>
    </div>
</div>

<div class="card">
    <h2>Referral Statistics</h2>
    <p>You have <strong>0</strong> referrals.</p>
    <p>Total earnings from referrals: <strong>$0.00</strong></p>
    <!-- A table of referred users could go here in the future -->
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const referralLinkInput = document.getElementById('referralLink');
    const copyBtn = document.getElementById('copyBtn');

    auth.onAuthStateChanged(user => {
        if (user && referralLinkInput) {
            // Construct the referral link. It should point to your registration page.
            const referralLink = `${window.location.origin}/auth/register.html?ref=${user.uid}`;
            referralLinkInput.value = referralLink;
        }
    });

    if (copyBtn && referralLinkInput) {
        copyBtn.addEventListener('click', () => {
            if (!referralLinkInput.value) return;

            referralLinkInput.select();
            referralLinkInput.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(referralLinkInput.value).then(() => {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Link copied!', showConfirmButton: false, timer: 2000, timerProgressBar: true });
            }).catch(err => {
                console.error('Failed to copy: ', err);
                Swal.fire('Error!', 'Failed to copy the link. Please copy it manually.', 'error');
            });
        });
    }
});
</script>

<?php include 'dashboard_footer.php'; ?>