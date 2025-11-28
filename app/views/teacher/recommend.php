<?php
// app/views/teacher/recommend.php
// Recommend Materials page for teachers
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommend Materials</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2><i class="fas fa-lightbulb"></i> Recommend Materials</h2>
        <p>Suggest new books or digital resources for your students. Admin/librarian will review your recommendations.</p>
        <form action="<?= BASE_PATH ?>/teacher/recommend" method="post" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="title" class="form-label">Title of Material</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="book">Book</option>
                    <option value="digital">Digital Resource</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Recommendation</button>
        </form>
    </div>
</body>
</html>
