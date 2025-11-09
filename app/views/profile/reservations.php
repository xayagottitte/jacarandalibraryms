<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<div class="container mt-4">
    <h2>My Reservations</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Expires At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['book_id']) ?></td>
                    <td><?= ucfirst($r['status']) ?></td>
                    <td><?= htmlspecialchars($r['requested_at']) ?></td>
                    <td><?= htmlspecialchars($r['expires_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../app/views/shared/footer.php'; ?>
