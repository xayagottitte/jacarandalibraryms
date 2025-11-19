<!-- Password Confirmation Modal for Critical Actions -->
<div class="modal fade" id="passwordConfirmModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-shield-alt me-2"></i>Confirm Your Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Security Check Required</strong>
                    <p class="mb-0 mt-2">This action requires password confirmation to ensure account security.</p>
                </div>
                
                <form id="passwordConfirmForm">
                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                    <input type="hidden" id="pendingAction" name="pending_action">
                    <input type="hidden" id="pendingActionData" name="pending_action_data">
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Your Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required 
                                   placeholder="Enter your password"
                                   autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Your password will not be stored. This confirmation is valid for 15 minutes.</small>
                    </div>
                    
                    <div id="passwordConfirmError" class="alert alert-danger d-none">
                        <i class="fas fa-times-circle me-2"></i>
                        <span id="passwordConfirmErrorMessage"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirmPasswordBtn">
                    <i class="fas fa-check me-2"></i>Confirm & Continue
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('passwordConfirmModal');
    const form = document.getElementById('passwordConfirmForm');
    const confirmBtn = document.getElementById('confirmPasswordBtn');
    const passwordInput = document.getElementById('confirm_password');
    const toggleBtn = document.getElementById('toggleConfirmPassword');
    const errorDiv = document.getElementById('passwordConfirmError');
    const errorMsg = document.getElementById('passwordConfirmErrorMessage');
    
    // Toggle password visibility
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Handle confirm button click
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            const password = passwordInput.value.trim();
            
            if (!password) {
                showError('Please enter your password.');
                return;
            }
            
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
            
            // Send password verification request
            fetch('<?= BASE_PATH ?>/admin/verify-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    csrf_token: form.querySelector('[name="csrf_token"]').value,
                    confirm_password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Password confirmed, proceed with original action
                    errorDiv.classList.add('d-none');
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    bsModal.hide();
                    
                    // Execute the pending action
                    const actionData = document.getElementById('pendingActionData').value;
                    if (actionData) {
                        const parsedData = JSON.parse(actionData);
                        executePendingAction(parsedData);
                    }
                } else {
                    showError(data.message || 'Incorrect password. Please try again.');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Confirm & Continue';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Confirm & Continue';
            });
        });
    }
    
    // Handle form submission with Enter key
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            confirmBtn.click();
        });
    }
    
    // Clear form when modal is hidden
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            form.reset();
            errorDiv.classList.add('d-none');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Confirm & Continue';
            passwordInput.type = 'password';
            toggleBtn.querySelector('i').classList.remove('fa-eye-slash');
            toggleBtn.querySelector('i').classList.add('fa-eye');
        });
    }
    
    function showError(message) {
        errorMsg.textContent = message;
        errorDiv.classList.remove('d-none');
    }
    
    function executePendingAction(data) {
        // Submit the original form or execute the original action
        if (data.formId) {
            const targetForm = document.getElementById(data.formId);
            if (targetForm) {
                targetForm.submit();
            }
        } else if (data.action === 'delete') {
            // Handle delete actions
            window.location.href = data.url;
        }
    }
});

// Global function to require password confirmation before action
window.requirePasswordConfirmation = function(actionData) {
    const modal = new bootstrap.Modal(document.getElementById('passwordConfirmModal'));
    document.getElementById('pendingActionData').value = JSON.stringify(actionData);
    modal.show();
};
</script>

<style>
#passwordConfirmModal .modal-header {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
}

#passwordConfirmModal .input-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

#passwordConfirmModal .form-control:focus {
    border-color: #ff9800;
    box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.25);
}

#passwordConfirmModal .btn-warning {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    border: none;
    color: white;
    font-weight: 600;
}

#passwordConfirmModal .btn-warning:hover {
    background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
