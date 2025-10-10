<?php 
$title = "System Maintenance - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/admin-sidebar.php'; 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">System Maintenance</h1>
    </div>

    <!-- System Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <?php foreach ($system_info as $key => $value): ?>
                            <tr>
                                <td><strong><?= ucfirst(str_replace('_', ' ', $key)) ?></strong></td>
                                <td><?= htmlspecialchars($value) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Database Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <?php foreach ($database_info as $key => $value): ?>
                            <tr>
                                <td><strong><?= ucfirst(str_replace('_', ' ', $key)) ?></strong></td>
                                <td><?= htmlspecialchars($value) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Actions -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-broom fa-3x text-warning mb-3"></i>
                    <h5>Cleanup Reports</h5>
                    <p class="text-muted">Remove reports older than 30 days</p>
                    <form method="POST" action="/system/maintenance">
                        <input type="hidden" name="action" value="cleanup_reports">
                        <button type="submit" class="btn btn-warning" 
                                onclick="return confirm('Clean up old reports?')">
                            Run Cleanup
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                    <h5>Record Statistics</h5>
                    <p class="text-muted">Record daily statistics for analytics</p>
                    <form method="POST" action="/system/maintenance">
                        <input type="hidden" name="action" value="record_stats">
                        <button type="submit" class="btn btn-info">
                            Record Stats
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-tachometer-alt fa-3x text-success mb-3"></i>
                    <h5>Optimize Database</h5>
                    <p class="text-muted">Optimize database tables for performance</p>
                    <form method="POST" action="/system/maintenance">
                        <input type="hidden" name="action" value="optimize_tables">
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('Optimize database tables?')">
                            Optimize
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">System Health</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="card <?= $system_info['memory_usage'] < 50 ? 'bg-success' : 'bg-warning' ?> text-white">
                        <div class="card-body">
                            <h5>Memory Usage</h5>
                            <h3><?= $system_info['memory_usage'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5>Database Size</h5>
                            <h3><?= $database_info['database_size'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Total Records</h5>
                            <h3><?= array_sum($database_info) - $database_info['database_size'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>System Status</h5>
                            <h3>OK</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../app/views/shared/footer.php'; ?>