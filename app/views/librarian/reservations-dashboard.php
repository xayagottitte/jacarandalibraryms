<?php
$title = "Reservation Dashboard - Jacaranda Libraries";
include '../app/views/shared/header.php';
?>
<div class="container mt-5">
    <h2 class="mb-4">Reservation Dashboard</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">Pending Reservations</div>
                <div class="card-body">
                    <a href="<?= BASE_PATH ?>/librarian/pending-reservations" class="btn btn-outline-primary">View Pending Reservations</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-success text-white">Reservation Requests</div>
                <div class="card-body">
                    <a href="<?= BASE_PATH ?>/librarian/reservation-requests" class="btn btn-outline-success">View Reservation Requests</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../app/views/shared/footer.php'; ?>
