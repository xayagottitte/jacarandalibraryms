<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<div class="container mt-4">
    <h2>Pending Reservations</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Book ID</th>
                <th>User ID</th>
                <th>Requested At</th>
                <th>Expires At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendingReservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['id']) ?></td>
                    <td><?= htmlspecialchars($r['book_id']) ?></td>
                    <td><?= htmlspecialchars($r['user_id']) ?></td>
                    <td><?= htmlspecialchars($r['requested_at']) ?></td>
                    <td><?= htmlspecialchars($r['expires_at']) ?></td>
                    <td>
                        <form method="post" action="/reservation/approve" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form method="post" action="/reservation/deny" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Deny</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../app/views/shared/footer.php'; ?>
