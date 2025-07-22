<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>Investment Plans</h1>
</div>

<div class="plan-grid" id="investment-plans-container">
    <!-- Loading spinner -->
    <div class="spinner-container">
        <div class="spinner"></div>
    </div>
    <!-- Investment plans will be injected here by JavaScript -->
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const plansContainer = document.getElementById('investment-plans-container');
    plansContainer.innerHTML = `<div class="spinner-container"><div class="spinner"></div></div>`;

    auth.onAuthStateChanged(user => {
        if (user) {
            db.collection("investment_plans").orderBy("min_deposit", "asc").get().then((querySnapshot) => {
                plansContainer.innerHTML = ''; // Clear the spinner
                if (querySnapshot.empty) {
                    plansContainer.innerHTML = '<div class="card"><p>No investment plans are available at the moment.</p></div>';
                    return;
                }
                querySnapshot.forEach((doc) => {
                    const plan = doc.data();
                    const planId = doc.id;
                    const planCard = document.createElement('div');
                    planCard.className = 'investment-plan-card';
                    planCard.innerHTML = `
                        <div class="plan-header">
                            <h3>${plan.name}</h3>
                        </div>
                        <p class="plan-price">$${plan.min_deposit.toLocaleString()} - $${plan.max_deposit.toLocaleString()}</p>
                        <ul class="plan-features">
                                <li><strong>Return:</strong> ${plan.roi_percentage}%</li>
                                <li><strong>Duration:</strong> ${plan.duration_days} Days</li>
                                <li>Daily Profit</li>
                            </ul>
                        <div class="plan-footer">
                            <button class="invest-btn" onclick="investNow('${planId}', '${plan.name}', ${plan.min_deposit}, ${plan.max_deposit})">Invest Now</button>
                        </div>
                    `;
                    plansContainer.appendChild(planCard);
                });
            }).catch((error) => {
                console.error("Error getting investment plans: ", error);
                plansContainer.innerHTML = '<p>Could not load investment plans. Please try again later.</p>';
            });
        }
    });
});

async function investNow(planId, planName, minDeposit, maxDeposit) {
    const currentUser = auth.currentUser;
    if (!currentUser) {
        return Swal.fire('Error', 'You must be logged in to invest.', 'error');
    }

    const userDocRef = db.collection('users').doc(currentUser.uid);

    try {
        const userDoc = await userDocRef.get();
        if (!userDoc.exists) {
            throw new Error("User data not found. Please contact support.");
        }

        const userData = userDoc.data();
        const currentBalance = userData.earnings || 0;

        // Check if balance is sufficient for the minimum deposit of the plan
        if (currentBalance < minDeposit) {
            return Swal.fire({
                title: 'Insufficient Balance',
                text: `You need at least $${minDeposit.toLocaleString()} to invest in this plan. Your current balance is $${currentBalance.toLocaleString()}. Would you like to deposit funds?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Deposit Now',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'deposit.php';
                }
            });
        }

        // If balance is sufficient, proceed with the investment modal
        const { value: amount } = await Swal.fire({
            title: `Invest in ${planName}`,
            html: `
                <p>Your available balance: <strong>$${currentBalance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong></p>
                <p>Enter amount to invest.</p>
                <p>Min: <strong>$${minDeposit.toLocaleString()}</strong> | Max: <strong>$${maxDeposit.toLocaleString()}</strong></p>
            `,
            input: 'number',
            inputPlaceholder: 'e.g., 500',
            showCancelButton: true,
            confirmButtonText: 'Invest Now',
            inputValidator: (value) => {
                if (!value || value <= 0) {
                    return 'Please enter a valid amount!'
                }
                if (value < minDeposit || value > maxDeposit) {
                    return `Amount must be between $${minDeposit.toLocaleString()} and $${maxDeposit.toLocaleString()}.`
                }
                if (parseFloat(value) > currentBalance) {
                    return `You cannot invest more than your available balance.`
                }
            }
        });

        if (amount) {
            const investAmount = parseFloat(amount);

            Swal.fire({
                title: 'Processing Investment...',
                text: 'Please wait while we set up your investment.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            await db.runTransaction(async (transaction) => {
                const freshUserDoc = await transaction.get(userDocRef);
                if (!freshUserDoc.exists) { throw new Error("User data not found."); }

                const freshUserData = freshUserDoc.data();
                const freshBalance = freshUserData.earnings || 0;

                if (investAmount > freshBalance) { throw new Error("Your balance is no longer sufficient for this investment."); }

                const newBalance = freshBalance - investAmount;
                const updateData = { earnings: newBalance };

                const hasInvested = freshUserData.hasInvested || false;
                const referredBy = freshUserData.referred_by || null;

                if (referredBy && !hasInvested) {
                    const bonusAmount = investAmount * 0.05;
                    const referrerDocRef = db.collection('users').doc(referredBy);
                    transaction.update(referrerDocRef, { earnings: firebase.firestore.FieldValue.increment(bonusAmount) });
                    const referrerTransactionRef = db.collection('transactions').doc();
                    transaction.set(referrerTransactionRef, {
                        userId: referredBy, date: firebase.firestore.FieldValue.serverTimestamp(), type: 'bonus',
                        amount: bonusAmount, description: `Referral bonus from ${currentUser.email}`, status: 'completed'
                    });
                    updateData.hasInvested = true;
                }
                
                transaction.update(userDocRef, updateData);

                const userTransactionRef = db.collection('transactions').doc();
                transaction.set(userTransactionRef, {
                    userId: currentUser.uid, date: firebase.firestore.FieldValue.serverTimestamp(), type: 'investment',
                    amount: -investAmount, description: `Investment in ${planName}`, status: 'completed'
                });
            });

            Swal.fire('Success!', 'Your investment has been processed successfully.', 'success');
        }
    } catch (error) {
        console.error("Investment Error: ", error);
        Swal.fire('Investment Failed', error.message, 'error');
    }
}
</script>

<?php include 'dashboard_footer.php'; ?>