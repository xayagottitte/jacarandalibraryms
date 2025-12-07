<?php 
$title = "Teacher Recommendations - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/navbar.php';
include '../app/views/shared/layout-header.php'; 
?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --shadow-light: 0 4px 20px rgba(0,0,0,0.1);
        --shadow-hover: 0 8px 30px rgba(0,0,0,0.15);
    }

    .modern-dashboard {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 1.5rem 0;
    }

    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-light);
        border-left: 4px solid var(--jacaranda-primary);
    }

    .page-header h1 {
        background: var(--jacaranda-primary);
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 600;
        margin: 0;
    }

    .recommendation-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-light);
        transition: all 0.3s ease;
        border-left: 4px solid var(--jacaranda-primary);
    }

    .recommendation-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }

    .recommendation-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .recommendation-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--jacaranda-primary);
        margin: 0;
    }

    .recommendation-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .meta-box {
        background: #f8f9fa;
        border-left: 3px solid #6c757d;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .meta-box.teacher {
        border-left-color: #663399;
        background: #f3e5f5;
    }

    .meta-box.school {
        border-left-color: #17a2b8;
        background: #e0f7fa;
    }

    .meta-box.email {
        border-left-color: #28a745;
        background: #e8f5e9;
    }

    .meta-box i {
        margin-right: 0.4rem;
        font-size: 0.9rem;
    }

    .meta-label {
        font-weight: 600;
        color: #495057;
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
    }

    .meta-value {
        color: #212529;
        font-weight: 500;
    }

    .justification-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid var(--jacaranda-primary);
        padding: 0.85rem;
        margin: 0.75rem 0;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .justification-title {
        color: var(--jacaranda-primary);
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .justification-text {
        margin: 0;
        color: #2d3748;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    .recommendation-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .detail-box {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 0.6rem 0.75rem;
        transition: all 0.2s ease;
    }

    .detail-box:hover {
        border-color: var(--jacaranda-primary);
        box-shadow: 0 2px 6px rgba(102, 51, 153, 0.1);
    }

    .detail-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .detail-label i {
        font-size: 0.8rem;
    }

    .detail-value {
        color: #212529;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .status-badge-pending {
        background: #ffc107 !important;
        color: #000 !important;
    }

    .status-badge-approved {
        background: #28a745 !important;
        color: white !important;
    }

    .status-badge-in_progress {
        background: #17a2b8 !important;
        color: white !important;
    }

    .status-badge-completed {
        background: #28a745 !important;
        color: white !important;
    }

    .status-badge-rejected {
        background: #dc3545 !important;
        color: white !important;
    }

    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-light);
        text-align: center;
        border-top: 4px solid var(--jacaranda-primary);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--jacaranda-primary);
        margin: 0;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-light);
    }

    .no-recommendations {
        background: white;
        border-radius: 12px;
        padding: 3rem;
        text-align: center;
        box-shadow: var(--shadow-light);
    }

    .no-recommendations i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 1rem;
    }
</style>

<div class="main-content modern-dashboard">
    <div class="container-fluid px-4">
        <div class="page-header">
            <div>
                <h1 class="h3 mb-2">
                    <i class="fas fa-lightbulb me-2"></i>Teacher Recommendations
                </h1>
                <p class="mb-0 text-muted">Review book and resource recommendations submitted by teachers</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="stat-card">
                    <p class="stat-number"><?= count($recommendations) ?></p>
                    <p class="stat-label">Total Recommendations</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="border-top-color: #28a745;">
                    <p class="stat-number" style="color: #28a745;">
                        <?= count(array_unique(array_column($recommendations, 'teacher_id'))) ?>
                    </p>
                    <p class="stat-label">Contributing Teachers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="border-top-color: #17a2b8;">
                    <p class="stat-number" style="color: #17a2b8;">
                        <?= count(array_unique(array_column($recommendations, 'library_id'))) ?>
                    </p>
                    <p class="stat-label">Schools Represented</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="border-top-color: #ffc107;">
                    <p class="stat-number" style="color: #ffc107;">
                        <?php
                        $thisMonth = date('Y-m');
                        $thisMonthCount = count(array_filter($recommendations, function($r) use ($thisMonth) {
                            return strpos($r['created_at'], $thisMonth) === 0;
                        }));
                        echo $thisMonthCount;
                        ?>
                    </p>
                    <p class="stat-label">This Month</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchRecommendations" 
                           placeholder="Search by title, teacher, or book...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterLibrary">
                        <option value="">All Schools</option>
                        <?php
                        $libraries = array_unique(array_map(function($r) {
                            return ['id' => $r['library_id'], 'name' => $r['library_name']];
                        }, $recommendations), SORT_REGULAR);
                        foreach ($libraries as $lib):
                        ?>
                            <option value="<?= $lib['id'] ?>"><?= htmlspecialchars($lib['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="sortBy">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="teacher">By Teacher</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" id="clearFilters">
                        <i class="fas fa-redo"></i> Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Recommendations List -->
        <div id="recommendationsList">
            <?php if (empty($recommendations)): ?>
                <div class="no-recommendations">
                    <i class="fas fa-lightbulb"></i>
                    <h4>No Recommendations Yet</h4>
                    <p class="text-muted">Teachers haven't submitted any book recommendations yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($recommendations as $rec): 
                    // Decode HTML entities first, then JSON decode
                    $filtersString = html_entity_decode($rec['filters'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $filters = json_decode($filtersString, true);
                    
                    if (!is_array($filters)) {
                        $filters = [];
                    }
                ?>
                    <div class="recommendation-card" 
                         data-library="<?= $rec['library_id'] ?>"
                         data-teacher="<?= strtolower($rec['teacher_name']) ?>"
                         data-title="<?= strtolower($rec['title']) ?>"
                         data-author="<?= strtolower($filters['author'] ?? '') ?>"
                         data-subject="<?= strtolower($filters['subject'] ?? '') ?>"
                         data-date="<?= strtotime($rec['created_at']) ?>">
                        <div class="recommendation-header">
                            <div>
                                <h3 class="recommendation-title">
                                    <i class="fas fa-book me-2"></i><?= htmlspecialchars($rec['title']) ?>
                                </h3>
                            </div>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <span class="badge badge-custom" style="background: var(--gradient-primary); color: white;">
                                    <i class="fas fa-clock me-1"></i>
                                    <?= date('M j, Y', strtotime($rec['created_at'])) ?>
                                </span>
                            </div>
                        </div>

                        <div class="recommendation-meta">
                            <div class="meta-box teacher">
                                <span class="meta-label"><i class="fas fa-user-tie"></i> Teacher</span>
                                <span class="meta-value"><?= htmlspecialchars($rec['teacher_name']) ?></span>
                            </div>
                            <div class="meta-box school">
                                <span class="meta-label"><i class="fas fa-school"></i> School</span>
                                <span class="meta-value"><?= htmlspecialchars($rec['library_name']) ?></span>
                            </div>
                            <div class="meta-box email">
                                <span class="meta-label"><i class="fas fa-envelope"></i> Email</span>
                                <span class="meta-value"><?= htmlspecialchars($rec['teacher_email']) ?></span>
                            </div>
                        </div>

                        <?php if (isset($filters['description']) && trim($filters['description']) !== ''): ?>
                            <div class="justification-box">
                                <div class="justification-title">
                                    <i class="fas fa-comment-dots"></i>
                                    Teacher's Justification
                                </div>
                                <p class="justification-text"><?= nl2br(htmlspecialchars($filters['description'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="recommendation-details">
                            <?php if (!empty($filters['material_type'])): ?>
                                <div class="detail-box">
                                    <div class="detail-label">
                                        <i class="fas fa-tag"></i> Type
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge bg-info" style="font-size: 0.8rem;">
                                            <?= ucfirst(htmlspecialchars($filters['material_type'])) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($filters['author'])): ?>
                                <div class="detail-box">
                                    <div class="detail-label">
                                        <i class="fas fa-pen"></i> Author/Publisher
                                    </div>
                                    <div class="detail-value"><?= htmlspecialchars($filters['author']) ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($filters['subject'])): ?>
                                <div class="detail-box">
                                    <div class="detail-label">
                                        <i class="fas fa-graduation-cap"></i> Subject
                                    </div>
                                    <div class="detail-value"><?= htmlspecialchars($filters['subject']) ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($filters['target_grade'])): ?>
                                <div class="detail-box">
                                    <div class="detail-label">
                                        <i class="fas fa-layer-group"></i> Grade
                                    </div>
                                    <div class="detail-value"><?= htmlspecialchars($filters['target_grade']) ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($filters['link'])): ?>
                                <div class="detail-box">
                                    <div class="detail-label">
                                        <i class="fas fa-link"></i> Link/ISBN
                                    </div>
                                    <div class="detail-value">
                                        <a href="<?= htmlspecialchars($filters['link']) ?>" target="_blank" style="color: #663399; text-decoration: none; word-break: break-all;">
                                            <?= htmlspecialchars($filters['link']) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($filters['priority'])): ?>
                                <div class="detail-box">
                                    <div class="detail-label">
                                        <i class="fas fa-flag"></i> Priority
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge" style="background: <?= 
                                            $filters['priority'] === 'urgent' ? '#dc3545' : 
                                            ($filters['priority'] === 'high' ? '#ffc107' : '#28a745') 
                                        ?>; color: <?= $filters['priority'] === 'high' ? '#000' : 'white' ?>; font-size: 0.8rem;">
                                            <?= ucfirst($filters['priority']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($filters['status'])): ?>
                                <div class="detail-box">
                                    <div class="detail-label">
                                        <i class="fas fa-info-circle"></i> Status
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge status-badge-<?= $filters['status'] ?>" style="font-size: 0.8rem;">
                                            <?= ucfirst($filters['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #e0e0e0; display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <?php if (isset($filters['status']) && $filters['status'] === 'pending'): ?>
                                <button class="btn btn-sm btn-success" onclick="updateRecommendationStatus(<?= $rec['id'] ?>, 'approved')" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-info" onclick="updateRecommendationStatus(<?= $rec['id'] ?>, 'in_progress')" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                    <i class="fas fa-clock me-1"></i> In Progress
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="updateRecommendationStatus(<?= $rec['id'] ?>, 'rejected')" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                    <i class="fas fa-times me-1"></i> Reject
                                </button>
                            <?php elseif (isset($filters['status']) && $filters['status'] === 'in_progress'): ?>
                                <button class="btn btn-sm btn-success" onclick="updateRecommendationStatus(<?= $rec['id'] ?>, 'completed')" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                    <i class="fas fa-check-double me-1"></i> Mark Complete
                                </button>
                                <button class="btn btn-sm btn-warning" onclick="updateRecommendationStatus(<?= $rec['id'] ?>, 'pending')" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                    <i class="fas fa-undo me-1"></i> Back to Pending
                                </button>
                            <?php elseif (isset($filters['status']) && in_array($filters['status'], ['approved', 'completed', 'rejected'])): ?>
                                <button class="btn btn-sm btn-secondary" onclick="updateRecommendationStatus(<?= $rec['id'] ?>, 'pending')" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                    <i class="fas fa-redo me-1"></i> Reopen
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchRecommendations');
    const filterLibrary = document.getElementById('filterLibrary');
    const sortBy = document.getElementById('sortBy');
    const clearBtn = document.getElementById('clearFilters');
    const recommendationCards = document.querySelectorAll('.recommendation-card');

    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedLibrary = filterLibrary.value;
        const sortMethod = sortBy.value;

        // Filter
        let visibleCards = Array.from(recommendationCards).filter(card => {
            const matchesSearch = 
                card.dataset.title.includes(searchTerm) ||
                card.dataset.teacher.includes(searchTerm) ||
                card.dataset.author.includes(searchTerm) ||
                card.dataset.subject.includes(searchTerm);
            
            const matchesLibrary = !selectedLibrary || card.dataset.library === selectedLibrary;

            const isVisible = matchesSearch && matchesLibrary;
            card.style.display = isVisible ? 'block' : 'none';
            return isVisible;
        });

        // Sort
        const container = document.getElementById('recommendationsList');
        
        if (sortMethod === 'newest') {
            visibleCards.sort((a, b) => parseInt(b.dataset.date) - parseInt(a.dataset.date));
        } else if (sortMethod === 'oldest') {
            visibleCards.sort((a, b) => parseInt(a.dataset.date) - parseInt(b.dataset.date));
        } else if (sortMethod === 'teacher') {
            visibleCards.sort((a, b) => a.dataset.teacher.localeCompare(b.dataset.teacher));
        }

        // Reorder DOM
        visibleCards.forEach(card => container.appendChild(card));
    }

    searchInput.addEventListener('input', filterAndSort);
    filterLibrary.addEventListener('change', filterAndSort);
    sortBy.addEventListener('change', filterAndSort);

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterLibrary.value = '';
        sortBy.value = 'newest';
        filterAndSort();
    });
});

function updateRecommendationStatus(reportId, newStatus) {
    if (!confirm(`Are you sure you want to change this recommendation status to "${newStatus.replace('_', ' ')}"?`)) {
        return;
    }

    const role = '<?= $_SESSION["role"] ?? "admin" ?>';
    const endpoint = role === 'librarian' ? 'librarian' : 'admin';

    fetch(`<?= BASE_PATH ?>/${endpoint}/update-recommendation-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'csrf_token': '<?= Security::generateCSRFToken() ?>',
            'report_id': reportId,
            'status': newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update status'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
}
</script>

<?php include '../app/views/shared/footer.php'; ?>
