<?php
// Set headers for CSV download
$filename = str_replace(' ', '_', $title) . '.csv';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Add report metadata
fputcsv($output, ['Report Title:', $title]);
fputcsv($output, ['Report Type:', ucfirst($type)]);
fputcsv($output, ['Generated On:', date('Y-m-d H:i:s')]);
fputcsv($output, []); // Blank line

if (empty($data)) {
    fputcsv($output, ['No data available for this report.']);
    fclose($output);
    exit;
}

// Get headers from the first item and write to CSV
$headers = array_keys((array)$data[0]);
fputcsv($output, $headers);

// Write data rows to CSV
foreach ($data as $row) {
    fputcsv($output, (array)$row);
}

fclose($output);
exit;
?>
