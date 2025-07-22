<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>Your Profile</h1>
</div>

<div class="card">
    <h2>Profile Details</h2>
    <form id="profileForm">
        <div class="form-group">
            <label for="displayName">Full Name</label>
            <input type="text" id="displayName" name="displayName" class="form-control" placeholder="Enter your full name" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" disabled>
            <small>Email address cannot be changed.</small>
        </div>
        <button type="submit" id="updateProfileBtn" class="btn">Update Profile</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const profileForm = document.getElementById('profileForm');
    const displayNameInput = document.getElementById('displayName');
    const emailInput = document.getElementById('email');
    const updateBtn = document.getElementById('updateProfileBtn');

    auth.onAuthStateChanged(user => {
        if (user) {
            // Populate form with user data
            displayNameInput.value = user.displayName || '';
            emailInput.value = user.email || '';

            profileForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const newName = displayNameInput.value.trim();

                if (!newName) {
                    Swal.fire('Error!', 'Name cannot be empty.', 'error');
                    return;
                }

                updateBtn.disabled = true;
                updateBtn.textContent = 'Updating...';

                user.updateProfile({
                    displayName: newName
                }).then(() => {
                    Swal.fire('Success!', 'Your profile has been updated.', 'success');
                    document.getElementById('user-welcome').textContent = `Welcome, ${newName}`;
                }).catch((error) => {
                    Swal.fire('Error!', 'Failed to update profile: ' + error.message, 'error');
                }).finally(() => {
                    updateBtn.disabled = false;
                    updateBtn.textContent = 'Update Profile';
                });
            });
        }
    });
});
</script>

<?php include 'dashboard_footer.php'; ?>