
<?php
$title = "Teacher Dashboard - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>
<style>
    .teacher-dashboard-header {
        background: white;
        border-radius: 15px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-left: 5px solid #663399;
    }
    .teacher-dashboard-header h2 {
        background: linear-gradient(135deg, #663399 0%, #8a4baf 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        margin: 0;
        font-size: 2rem;
    }
    .dashboard-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .dashboard-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        flex: 1 1 300px;
        min-width: 300px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        transition: box-shadow 0.3s;
    }
    .dashboard-card:hover {
        box-shadow: 0 8px 30px rgba(102,51,153,0.15);
    }
    .dashboard-card h4 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #663399;
    }
    .dashboard-card p {
        color: #4a5568;
        margin-bottom: 1rem;
    }
    .dashboard-card .btn {
        border-radius: 8px;
        font-weight: 600;
    }
    .dashboard-stats {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 1.25rem 2rem;
        min-width: 180px;
        box-shadow: 0 4px 15px rgba(102,51,153,0.12);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .stat-card .stat-label {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }
</style>

<main class="container py-4">
    <div class="teacher-dashboard-header mb-4">
        <h2><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Dashboard</h2>
        <p class="text-muted mb-0">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? '') ?>!</p>
    </div>

    <!-- Quick Stats -->
    <div class="dashboard-stats mb-4">
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-book-reader"></i> Books Borrowed</span>
            <span class="stat-value">--</span>
        </div>
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-lightbulb"></i> Recommendations Made</span>
            <span class="stat-value">--</span>
        </div>
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-users"></i> Students Tracked</span>
            <span class="stat-value">--</span>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <h4><i class="fas fa-book"></i> Borrow Books</h4>
            <p>View and borrow available books for your class. Borrow limits and rules are tailored for teachers.</p>
            <a href="<?= BASE_PATH ?>/teacher/borrow" class="btn btn-primary">Borrow Books</a>
        </div>
        <div class="dashboard-card">
            <h4><i class="fas fa-lightbulb"></i> Recommend Materials</h4>
            <p>Suggest new books or digital resources for your students. Admin/librarian will review your recommendations.</p>
            <a href="<?= BASE_PATH ?>/teacher/recommend" class="btn btn-secondary">Recommend Materials</a>
        </div>
        <div class="dashboard-card">
            <h4><i class="fas fa-users"></i> Track Student Borrowing</h4>
            <p>Monitor borrowing history of students in your classes/subjects. View analytics and trends.</p>
            <a href="<?= BASE_PATH ?>/teacher/borrowing" class="btn btn-info">Track Borrowing</a>
        </div>
        <div class="dashboard-card">
            <h4><i class="fas fa-tablet-alt"></i> Digital Resources</h4>
            <p>Access e-books, online journals, and other digital resources for teaching.</p>
            <a href="<?= BASE_PATH ?>/teacher/resources" class="btn btn-success">Access Resources</a>
        </div>
        <div class="dashboard-card">
            <h4><i class="fas fa-chart-bar"></i> Analytics</h4>
            <p>See most borrowed books by your students, popular subjects, and reading trends.</p>
            <a href="<?= BASE_PATH ?>/teacher/analytics" class="btn btn-warning">View Analytics</a>
        </div>
        <div class="dashboard-card">
            <h4><i class="fas fa-calendar-check"></i> Reserve Books for Class</h4>
            <p>Reserve books for upcoming lessons or class use. Ensure availability for your students.</p>
            <a href="<?= BASE_PATH ?>/teacher/reserve" class="btn btn-outline-primary">Reserve Books</a>
        </div>
        <div class="dashboard-card">
            <h4><i class="fas fa-upload"></i> Upload & Share Materials</h4>
            <p>Upload and share teaching materials and resources with your students.</p>
            <a href="<?= BASE_PATH ?>/teacher/upload" class="btn btn-outline-success">Upload Materials</a>
        </div>
    </div>
</main>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>
