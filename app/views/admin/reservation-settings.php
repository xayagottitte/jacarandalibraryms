<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<div class="container mt-4">
    <h2>Reservation Settings</h2>
    <form method="post" action="/admin/updateReservationSettings">
        <div class="form-group">
            <label for="reservation_period">Default Reservation Period (days)</label>
            <input type="number" class="form-control" id="reservation_period" name="reservation_period" value="<?= htmlspecialchars($settings['reservation_period']) ?>" min="1" required>
        </div>
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>
<?php include '../app/views/shared/footer.php'; ?>
