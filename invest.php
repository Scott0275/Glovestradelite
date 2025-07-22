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

<style>
.plan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}
.investment-plan-card {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 25px;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.investment-plan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.investment-plan-card h3 {
    color: #3498db;
    margin-top: 0;
}
.investment-plan-card .price {
    font-size: 1.2em;
    font-weight: bold;
    margin: 15px 0;
}
.investment-plan-card ul {
    list-style: none;
    padding: 0;
    margin: 20px 0;
    flex-grow: 1;
}
.investment-plan-card li {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}
.investment-plan-card li:last-child {
    border-bottom: none;
}
.invest-btn {
    background-color: #3498db;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    margin-top: auto;
    transition: background-color 0.3s;
}
.invest-btn:hover {
    background-color: #2980b9;
}

/* Spinner styles */
.spinner-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 200px;
}
.spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border-left-color: #3498db;
    animation: spin 1s ease infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const plansContainer = document.getElementById('investment-plans-container');

    auth.onAuthStateChanged(user => {
        if (user) {
            db.collection("investment_plans").orderBy("min_deposit", "asc").get().then((querySnapshot) => {
                plansContainer.innerHTML = ''; // Clear the spinner
                if (querySnapshot.empty) {
                    plansContainer.innerHTML = '<p>No investment plans are available at the moment.</p>';
                    return;
                }
                querySnapshot.forEach((doc) => {
                    const plan = doc.data();
                    const planId = doc.id;
                    const planCard = `
                        <div class="investment-plan-card">
                            <h3>${plan.name}</h3>
                            <p class="price">$${plan.min_deposit.toLocaleString()} - $${plan.max_deposit.toLocaleString()}</p>
                            <ul>
                                <li><strong>Return:</strong> ${plan.roi_percentage}%</li>
                                <li><strong>Duration:</strong> ${plan.duration_days} Days</li>
                                <li>Daily Profit</li>
                            </ul>
                            <button class="invest-btn" onclick="investNow('${planId}', '${plan.name}')">Invest Now</button>
                        </div>
                    `;
                    plansContainer.innerHTML += planCard;
                });
            }).catch((error) => {
                console.error("Error getting investment plans: ", error);
                plansContainer.innerHTML = '<p>Could not load investment plans. Please try again later.</p>';
            });
        }
    });
});

function investNow(planId, planName) {
    // This is a placeholder. In a real app, you would open a modal
    // to confirm the investment amount and process the transaction.
    alert(`You selected the "${planName}" plan (ID: ${planId}).`);
}
</script>

<?php include 'dashboard_footer.php'; ?>