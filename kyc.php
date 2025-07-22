<?php include 'dashboard_header.php'; ?>

<div class="header">
    <h1>KYC Verification</h1>
</div>

<div class="card" id="kyc-card">
    <h2 id="kyc-card-title">Submit Your Documents</h2>
    <p>To comply with regulations and ensure the security of your account, please upload the following documents. Your documents will be reviewed by our team within 24-48 hours.</p>
    
    <div id="kyc-status-view" style="display: none;">
        <!-- Status will be shown here -->
    </div>

    <form id="kycForm" style="display: none;">
        <div class="form-group">
            <label for="id_front">ID Card (Front Side)</label>
            <input type="file" id="id_front" class="form-control" accept="image/png, image/jpeg" required>
        </div>
        <div class="form-group">
            <label for="id_back">ID Card (Back Side)</label>
            <input type="file" id="id_back" class="form-control" accept="image/png, image/jpeg" required>
        </div>
        <div class="form-group">
            <label for="selfie">Photo of Yourself (Selfie)</label>
            <input type="file" id="selfie" class="form-control" accept="image/png, image/jpeg" required>
        </div>
        <button type="submit" id="submitKycBtn" class="btn">Submit for Verification</button>
        <div id="upload-progress" style="display: none;">
            <progress id="progress-bar" value="0" max="100"></progress>
            <p id="upload-status"></p>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const kycForm = document.getElementById('kycForm');
    const kycStatusView = document.getElementById('kyc-status-view');
    const kycCardTitle = document.getElementById('kyc-card-title');

    auth.onAuthStateChanged(user => {
        if (user) {
            const userDocRef = db.collection('users').doc(user.uid);

            userDocRef.onSnapshot(doc => {
                if (doc.exists) {
                    const userData = doc.data();
                    const kycStatus = userData.kycStatus; // e.g., 'pending', 'verified', 'rejected'

                    kycStatusView.style.display = 'block';

                    if (kycStatus === 'verified') {
                        kycCardTitle.innerHTML = '<i class="fas fa-check-circle" style="color: #27ae60;"></i> Account Verified';
                        kycStatusView.innerHTML = '<p>Your account is verified. You can now access all features, including withdrawals.</p>';
                        kycForm.style.display = 'none';
                    } else if (kycStatus === 'pending') {
                        kycCardTitle.innerHTML = '<i class="fas fa-hourglass-half" style="color: #f39c12;"></i> Documents Under Review';
                        kycStatusView.innerHTML = '<p>Your documents have been submitted and are currently under review. This usually takes 24-48 hours.</p>';
                        kycForm.style.display = 'none';
                    } else {
                        if (kycStatus === 'rejected') {
                            kycStatusView.innerHTML = `<p style="color: #c0392b;">Your previous submission was rejected. Reason: ${userData.kycRejectReason || 'Not provided'}. Please re-submit your documents.</p>`;
                        } else {
                            kycStatusView.style.display = 'none';
                        }
                        kycCardTitle.innerHTML = 'Submit Your Documents';
                        kycForm.style.display = 'block';
                    }
                }
            });

            kycForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = document.getElementById('submitKycBtn');
                const progressDiv = document.getElementById('upload-progress');
                const progressBar = document.getElementById('progress-bar');
                const uploadStatus = document.getElementById('upload-status');

                const idFrontFile = document.getElementById('id_front').files[0];
                const idBackFile = document.getElementById('id_back').files[0];
                const selfieFile = document.getElementById('selfie').files[0];

                if (!idFrontFile || !idBackFile || !selfieFile) {
                    Swal.fire('Error', 'Please select all three files.', 'error');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'Uploading...';
                progressDiv.style.display = 'block';

                try {
                    const uploadTasks = [
                        uploadFile(user.uid, 'id_front', idFrontFile, progressBar, uploadStatus, 0, 33),
                        uploadFile(user.uid, 'id_back', idBackFile, progressBar, uploadStatus, 33, 66),
                        uploadFile(user.uid, 'selfie', selfieFile, progressBar, uploadStatus, 66, 100)
                    ];

                    const downloadURLs = await Promise.all(uploadTasks);

                    await userDocRef.update({
                        kycStatus: 'pending',
                        kycDocs: {
                            id_front: downloadURLs[0],
                            id_back: downloadURLs[1],
                            selfie: downloadURLs[2]
                        },
                        kycSubmittedAt: firebase.firestore.FieldValue.serverTimestamp()
                    });

                    Swal.fire('Success', 'Your documents have been submitted for review.', 'success');

                } catch (error) {
                    console.error("KYC Submission Error: ", error);
                    Swal.fire('Upload Failed', error.message, 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit for Verification';
                    progressDiv.style.display = 'none';
                }
            });
        }
    });
});

function uploadFile(uid, fileName, file, progressBar, statusElement, progressStart, progressEnd) {
    return new Promise((resolve, reject) => {
        const filePath = `kyc_documents/${uid}/${fileName}_${Date.now()}`;
        const storageRef = storage.ref(filePath);
        const uploadTask = storageRef.put(file);

        uploadTask.on('state_changed',
            (snapshot) => {
                const progress = (snapshot.bytesTransferred / snapshot.totalBytes);
                const overallProgress = progressStart + (progress * (progressEnd - progressStart));
                progressBar.value = overallProgress;
                statusElement.textContent = `Uploading ${fileName}... ${Math.round(overallProgress)}%`;
            },
            (error) => {
                reject(error);
            },
            () => {
                uploadTask.snapshot.ref.getDownloadURL().then((downloadURL) => {
                    resolve(downloadURL);
                });
            }
        );
    });
}
</script>

<?php include 'dashboard_footer.php'; ?>