<?php 
$pageTitle = "Digital Resources";
include '../app/views/shared/header.php'; 
include '../app/views/shared/navbar.php';
include '../app/views/shared/layout-header.php';
?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid var(--jacaranda-primary);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(102, 51, 153, 0.2);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--jacaranda-primary);
        margin: 0;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
        margin-top: 0.5rem;
    }

    .resource-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .resource-thumbnail {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .resource-thumbnail i {
        font-size: 4rem;
        color: white;
        opacity: 0.8;
    }

    .resource-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-duration {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .resource-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .resource-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .resource-description {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .resource-meta {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        background: #f8f9fa;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #495057;
    }

    .resource-footer {
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-resource {
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary-gradient {
        background: var(--gradient-primary);
        color: white;
        border: none;
    }

    .btn-primary-gradient:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 51, 153, 0.3);
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .resource-type-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .type-video { background: #ff4757; color: white; }
    .type-document { background: #3498db; color: white; }
    .type-link { background: #2ecc71; color: white; }
    .type-image { background: #f39c12; color: white; }
    .type-other { background: #95a5a6; color: white; }

    .source-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.6rem;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .youtube-badge {
        background: #ff0000;
        color: white;
    }

    .modal-content {
        border-radius: 12px;
    }

    .modal-header {
        background: var(--gradient-primary);
        color: white;
        border-radius: 12px 12px 0 0;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem 0;
    }
</style>

<div class="main-content modern-dashboard">
    <div class="container-fluid px-4">
        <div class="page-header">
            <div>
                <h1 class="h3 mb-2">
                    <i class="fas fa-photo-video me-2"></i>Digital Resources
                </h1>
                <p class="mb-0 text-muted">Upload and share videos, documents, and learning materials</p>
            </div>
            <button class="btn btn-primary-gradient" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-plus me-2"></i>Add Resource
            </button>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="stat-card">
                    <p class="stat-number"><?= $stats['total_resources'] ?></p>
                    <p class="stat-label">Total Resources</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <p class="stat-number"><?= $stats['my_uploads'] ?></p>
                    <p class="stat-label">My Uploads</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <p class="stat-number"><?= $stats['videos'] ?></p>
                    <p class="stat-label">Video Resources</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <p class="stat-number"><?= number_format($stats['total_views']) ?></p>
                    <p class="stat-label">Total Views</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="<?= BASE_PATH ?>/teacher/resources" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search resources..." value="<?= htmlspecialchars($filters['search']) ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="video" <?= $filters['type'] === 'video' ? 'selected' : '' ?>>Videos</option>
                            <option value="document" <?= $filters['type'] === 'document' ? 'selected' : '' ?>>Documents</option>
                            <option value="link" <?= $filters['type'] === 'link' ? 'selected' : '' ?>>Links</option>
                            <option value="image" <?= $filters['type'] === 'image' ? 'selected' : '' ?>>Images</option>
                            <option value="other" <?= $filters['type'] === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Subject</label>
                        <select name="subject" class="form-select">
                            <option value="">All Subjects</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= htmlspecialchars($subject) ?>" <?= $filters['subject'] === $subject ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Grade Level</label>
                        <select name="grade" class="form-select">
                            <option value="">All Grades</option>
                            <?php foreach ($grades as $grade): ?>
                                <option value="<?= htmlspecialchars($grade) ?>" <?= $filters['grade'] === $grade ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grade) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?= BASE_PATH ?>/teacher/resources" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Resources Grid -->
        <div class="row g-4">
            <?php if (empty($resources)): ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h4>No Resources Found</h4>
                        <p>Start by uploading your first resource using the "Add Resource" button above.</p>
                        <p class="text-muted">You can upload YouTube videos, documents, links, and more!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($resources as $resource): ?>
                    <div class="col-md-4">
                        <div class="resource-card">
                            <div class="resource-thumbnail">
                                <?php if ($resource['thumbnail_url']): ?>
                                    <img src="<?= htmlspecialchars($resource['thumbnail_url']) ?>" alt="Thumbnail">
                                <?php else: ?>
                                    <?php
                                    $icons = [
                                        'video' => 'fa-video',
                                        'document' => 'fa-file-alt',
                                        'link' => 'fa-link',
                                        'image' => 'fa-image',
                                        'other' => 'fa-file'
                                    ];
                                    $icon = $icons[$resource['resource_type']] ?? 'fa-file';
                                    ?>
                                    <i class="fas <?= $icon ?>"></i>
                                <?php endif; ?>
                                
                                <span class="resource-type-badge type-<?= $resource['resource_type'] ?>">
                                    <?= ucfirst($resource['resource_type']) ?>
                                </span>
                                
                                <?php if ($resource['duration']): ?>
                                    <span class="video-duration">
                                        <i class="fas fa-clock me-1"></i><?= htmlspecialchars($resource['duration']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="resource-body">
                                <h5 class="resource-title"><?= htmlspecialchars($resource['title']) ?></h5>
                                
                                <?php if ($resource['description']): ?>
                                    <p class="resource-description"><?= htmlspecialchars($resource['description']) ?></p>
                                <?php endif; ?>
                                
                                <div class="resource-meta">
                                    <?php if ($resource['source'] === 'youtube'): ?>
                                        <span class="source-badge youtube-badge">
                                            <i class="fab fa-youtube"></i> YouTube
                                        </span>
                                    <?php elseif ($resource['source'] !== 'teacher_upload'): ?>
                                        <span class="source-badge">
                                            <i class="fas fa-globe"></i> <?= htmlspecialchars($resource['source']) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($resource['subject']): ?>
                                        <span class="meta-badge">
                                            <i class="fas fa-book"></i> <?= htmlspecialchars($resource['subject']) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($resource['grade_level']): ?>
                                        <span class="meta-badge">
                                            <i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($resource['grade_level']) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <span class="meta-badge">
                                        <i class="fas fa-eye"></i> <?= number_format($resource['views']) ?> views
                                    </span>
                                </div>
                                
                                <div class="resource-footer">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        <?= htmlspecialchars($resource['uploader_full_name'] ?? $resource['uploader_name']) ?>
                                    </small>
                                    
                                    <div>
                                        <?php if ($resource['resource_type'] === 'video' && strpos($resource['resource_url'], 'youtube.com') !== false): ?>
                                            <a href="<?= htmlspecialchars($resource['resource_url']) ?>" target="_blank" class="btn btn-sm btn-resource btn-primary-gradient" onclick="trackView(<?= $resource['id'] ?>)">
                                                <i class="fab fa-youtube me-1"></i>Watch
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= htmlspecialchars($resource['resource_url']) ?>" target="_blank" class="btn btn-sm btn-resource btn-primary-gradient" onclick="trackView(<?= $resource['id'] ?>)">
                                                <i class="fas fa-external-link-alt me-1"></i>Open
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($resource['uploaded_by'] == $_SESSION['user_id']): ?>
                                            <button class="btn btn-sm btn-danger" onclick="deleteResource(<?= $resource['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Resource</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_PATH ?>/teacher/upload-resource">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Resource Type *</label>
                        <select name="resource_type" class="form-select" required onchange="updateSourceOptions(this.value)">
                            <option value="">Select Type</option>
                            <option value="video">Video</option>
                            <option value="document">Document</option>
                            <option value="link">Link/Website</option>
                            <option value="image">Image</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Enter resource title">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Describe this resource..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Resource URL *</label>
                        <input type="url" name="resource_url" class="form-control" required placeholder="https://example.com or https://youtube.com/watch?v=...">
                        <small class="form-text text-muted">
                            For YouTube videos: paste the full YouTube URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID)
                        </small>
                    </div>
                    
                    <div class="mb-3" id="sourceField">
                        <label class="form-label">Source</label>
                        <select name="source" class="form-select">
                            <option value="teacher_upload">Teacher Upload</option>
                            <option value="youtube">YouTube</option>
                            <option value="external_api">External API</option>
                            <option value="google_drive">Google Drive</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g., Mathematics">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Grade Level</label>
                            <input type="text" name="grade_level" class="form-control" placeholder="e.g., Form 1">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3" id="durationField" style="display: none;">
                            <label class="form-label">Duration (for videos)</label>
                            <input type="text" name="duration" class="form-control" placeholder="e.g., 10:30">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thumbnail URL (optional)</label>
                            <input type="url" name="thumbnail_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tags (comma-separated)</label>
                        <input type="text" name="tags" class="form-control" placeholder="e.g., algebra, equations, tutorial">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="fas fa-upload me-2"></i>Upload Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateSourceOptions(type) {
    const durationField = document.getElementById('durationField');
    const sourceField = document.querySelector('select[name="source"]');
    
    if (type === 'video') {
        durationField.style.display = 'block';
        // Auto-select YouTube if it's a video
        sourceField.value = 'youtube';
    } else {
        durationField.style.display = 'none';
        sourceField.value = 'teacher_upload';
    }
}

function trackView(resourceId) {
    fetch('<?= BASE_PATH ?>/teacher/track-view', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'resource_id=' + resourceId
    });
}

function deleteResource(resourceId) {
    if (!confirm('Are you sure you want to delete this resource?')) {
        return;
    }
    
    fetch('<?= BASE_PATH ?>/teacher/delete-resource', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'resource_id=' + resourceId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the resource');
    });
}
</script>

<?php include '../app/views/shared/footer.php'; ?>
