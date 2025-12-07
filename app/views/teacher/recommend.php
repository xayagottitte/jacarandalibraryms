<?php
$title = "Recommend Materials - Teacher Dashboard";
include '../app/views/shared/header.php';
include '../app/views/shared/layout-header.php';
?>

<style>
    .recommend-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .recommend-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .recommend-header {
        background: linear-gradient(135deg, #663399 0%, #522d7a 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .recommend-header h2 {
        margin: 0;
        font-weight: 700;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #663399;
        box-shadow: 0 0 0 0.2rem rgba(102,51,153,0.15);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #663399 0%, #522d7a 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102,51,153,0.4);
        color: white;
    }
</style>

<div class="recommend-container">
    <div class="container">
        <div class="recommend-header">
            <h2><i class="fas fa-lightbulb me-2"></i>Recommend Materials</h2>
            <p class="mb-0 mt-2">Suggest new books or digital resources for your students. Admin/librarian will review your recommendations.</p>
        </div>
        
        <div class="recommend-card">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="<?= BASE_PATH ?>/teacher/recommend" method="post" id="recommendForm">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                
                <div class="mb-4">
                    <label for="title" class="form-label">
                        <i class="fas fa-book me-2"></i>Title of Material <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="title" name="title" required 
                           placeholder="Enter the title of the recommended material">
                </div>
                
                <div class="mb-4">
                    <label for="author" class="form-label">
                        <i class="fas fa-user-edit me-2"></i>Author/Publisher
                    </label>
                    <input type="text" class="form-control" id="author" name="author" 
                           placeholder="Enter author or publisher name">
                </div>
                
                <div class="mb-4">
                    <label for="type" class="form-label">
                        <i class="fas fa-list me-2"></i>Type <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">-- Select Type --</option>
                        <option value="book">Physical Book</option>
                        <option value="ebook">E-Book</option>
                        <option value="digital">Digital Resource (Website/App)</option>
                        <option value="journal">Journal/Magazine</option>
                        <option value="multimedia">Multimedia (DVD/Video)</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="subject" class="form-label">
                        <i class="fas fa-graduation-cap me-2"></i>Subject Area
                    </label>
                    <input type="text" class="form-control" id="subject" name="subject" 
                           placeholder="e.g., Mathematics, Science, Literature">
                </div>
                
                <div class="mb-4">
                    <label for="target_grade" class="form-label">
                        <i class="fas fa-users me-2"></i>Target Grade Level
                    </label>
                    <input type="text" class="form-control" id="target_grade" name="target_grade" 
                           placeholder="e.g., Grade 9-12, All Grades">
                </div>
                
                <div class="mb-4">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-2"></i>Description/Justification <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="4" required
                              placeholder="Explain why this material would be valuable for students. Include educational benefits, curriculum alignment, etc."></textarea>
                    <small class="text-muted">Minimum 50 characters</small>
                </div>
                
                <div class="mb-4">
                    <label for="link" class="form-label">
                        <i class="fas fa-link me-2"></i>Link/ISBN (if available)
                    </label>
                    <input type="text" class="form-control" id="link" name="link" 
                           placeholder="Enter URL or ISBN number">
                </div>
                
                <div class="mb-4">
                    <label for="priority" class="form-label">
                        <i class="fas fa-exclamation-triangle me-2"></i>Priority
                    </label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                
                <div class="d-flex gap-3 justify-content-end">
                    <a href="<?= BASE_PATH ?>/teacher/dashboard" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>Submit Recommendation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../app/views/shared/layout-footer.php'; ?>

<script>
    // Form validation
    document.getElementById('recommendForm').addEventListener('submit', function(e) {
        const description = document.getElementById('description').value;
        if (description.length < 50) {
            e.preventDefault();
            alert('Description must be at least 50 characters long.');
            document.getElementById('description').focus();
        }
    });
</script>

<?php include '../app/views/shared/footer.php'; ?>
