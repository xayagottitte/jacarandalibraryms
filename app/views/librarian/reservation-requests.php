<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<div class="container mt-4">
    <h2>Reservation Requests</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>User ID</th>
                <th>Requested At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservationRequests as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['book_id']) ?></td>
                    <td><?= htmlspecialchars($r['user_id']) ?></td>
                    <td><?= htmlspecialchars($r['requested_at']) ?></td>
                    <td><?= ucfirst($r['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../app/views/shared/footer.php'; ?>
