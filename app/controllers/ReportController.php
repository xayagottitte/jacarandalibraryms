<?php
class ReportController extends Controller {
    private $reportModel;
    private $userModel;
    private $libraryModel;

    public function __construct() {
        session_start();
        $this->requireAuth();
        $this->reportModel = new Report();
        $this->userModel = new User();
        $this->libraryModel = new Library();
    }

    public function index() {
        if ($_SESSION['role'] === 'super_admin') {
            $this->redirect('/admin/reports');
        } else {
            $this->redirect('/librarian/reports');
        }
    }

    public function generateAdvancedReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['report_type'] ?? '';
            $libraryId = $_SESSION['role'] === 'super_admin' ? ($_POST['library_id'] ?? null) : $_SESSION['library_id'];
            $filters = json_decode($_POST['filters'] ?? '{}', true);
            
            $data = [];
            $summary = [];

            switch ($type) {
                case 'comprehensive':
                    $data['books'] = $this->reportModel->generateBooksReport($libraryId, $filters);
                    $data['students'] = $this->reportModel->generateStudentsReport($libraryId, $filters);
                    $data['borrows'] = $this->reportModel->generateBorrowingReport($libraryId, $filters);
                    $data['financial'] = $this->reportModel->generateFinancialReport($libraryId, $filters);
                    break;
                    
                case 'analytics':
                    $period = $filters['period'] ?? '30';
                    $data['analytics'] = $this->reportModel->generateLibraryAnalytics($libraryId, $period);
                    $data['popular_books'] = $this->reportModel->getPopularBooks($libraryId);
                    $data['top_students'] = $this->reportModel->getTopStudents($libraryId);
                    break;
                    
                case 'performance':
                    $data['monthly_summary'] = (new SystemStatistics())->getMonthlySummary($libraryId);
                    $data['book_trend'] = (new SystemStatistics())->getStatisticsTrend('total_books', $libraryId, 90);
                    $data['borrow_trend'] = (new SystemStatistics())->getStatisticsTrend('active_borrows', $libraryId, 90);
                    break;
                    
                default:
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'Invalid report type']);
                    exit;
            }

            // Generate summary statistics
            $summary = $this->generateReportSummary($data, $type);

            // Save report
            $this->reportModel->saveReportWithData([
                'title' => $_POST['report_title'] ?? 'Advanced Report',
                'type' => $type,
                'generated_by' => $_SESSION['user_id'],
                'library_id' => $libraryId,
                'date_range_start' => $filters['start_date'] ?? null,
                'date_range_end' => $filters['end_date'] ?? null,
                'filters' => json_encode($filters)
            ], array_merge($data, ['summary' => $summary]));

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'data' => $data,
                'summary' => $summary
            ]);
            exit;
        }
    }

    private function generateReportSummary($data, $type) {
        $summary = [];

        switch ($type) {
            case 'comprehensive':
                if (isset($data['books'])) {
                    $summary['total_books'] = count($data['books']);
                    $summary['total_copies'] = array_sum(array_column($data['books'], 'total_copies'));
                    $summary['available_copies'] = array_sum(array_column($data['books'], 'available_copies'));
                    $summary['utilization_rate'] = round(($summary['total_copies'] - $summary['available_copies']) / $summary['total_copies'] * 100, 2);
                }
                
                if (isset($data['students'])) {
                    $summary['total_students'] = count($data['students']);
                    $summary['active_students'] = count(array_filter($data['students'], function($s) {
                        return $s['status'] === 'active';
                    }));
                }
                
                if (isset($data['borrows'])) {
                    $summary['total_borrows'] = count($data['borrows']);
                    $summary['active_borrows'] = count(array_filter($data['borrows'], function($b) {
                        return in_array($b['status'], ['borrowed', 'overdue']);
                    }));
                    $summary['total_fines'] = array_sum(array_column($data['borrows'], 'calculated_fine'));
                }
                break;

            case 'analytics':
                if (isset($data['analytics'])) {
                    $summary['total_borrows_period'] = array_sum(array_column($data['analytics'], 'daily_borrows'));
                    $summary['avg_daily_borrows'] = round($summary['total_borrows_period'] / count($data['analytics']), 2);
                    $summary['unique_students'] = count(array_unique(array_column($data['analytics'], 'unique_students')));
                }
                break;
        }

        return $summary;
    }

    public function viewSavedReport($reportId) {
        $report = $this->reportModel->getReportWithData($reportId);
        
        if (!$report) {
            $_SESSION['error'] = "Report not found.";
            $this->redirect('/report');
            return;
        }

        // Check permissions
        if ($_SESSION['role'] === 'librarian' && $report['library_id'] != $_SESSION['library_id']) {
            $_SESSION['error'] = "Access denied to this report.";
            $this->redirect('/librarian/reports');
            return;
        }

        $data = [
            'report' => $report,
            'generated_by' => $this->userModel->find($report['generated_by']),
            'library' => $report['library_id'] ? $this->libraryModel->find($report['library_id']) : null
        ];

        if ($_SESSION['role'] === 'super_admin') {
            $this->view('admin/view-report', $data);
        } else {
            $this->view('librarian/view-report', $data);
        }
    }

    public function deleteReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reportId = $_POST['report_id'] ?? null;
            $report = $this->reportModel->find($reportId);
            
            if (!$report) {
                $_SESSION['error'] = "Report not found.";
            } elseif ($_SESSION['role'] === 'librarian' && $report['library_id'] != $_SESSION['library_id']) {
                $_SESSION['error'] = "Access denied to delete this report.";
            } else {
                $query = "DELETE FROM reports WHERE id = :id";
                $stmt = $this->reportModel->db->prepare($query);
                $stmt->bindParam(':id', $reportId);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Report deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete report.";
                }
            }
        }
        
        if ($_SESSION['role'] === 'super_admin') {
            $this->redirect('/admin/reports');
        } else {
            $this->redirect('/librarian/reports');
        }
    }

    public function exportReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $exportType = $_POST['export_type'] ?? 'csv';
            $reportData = json_decode($_POST['report_data'] ?? '[]', true);
            $reportType = $_POST['report_type'] ?? 'generic';
            $filename = $_POST['filename'] ?? 'report';
            
            if ($exportType === 'csv') {
                $this->exportToCSV($reportData, $filename, $reportType);
            } elseif ($exportType === 'pdf') {
                $this->exportToPDF($reportData, $filename, $reportType);
            } elseif ($exportType === 'excel') {
                $this->exportToExcel($reportData, $filename, $reportType);
            }
        }
    }

    private function exportToCSV($data, $filename, $reportType) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fputs($output, "\xEF\xBB\xBF");
        
        if ($reportType === 'comprehensive' && is_array($data)) {
            // Export each section as separate sheets (simulated with headers)
            foreach ($data as $section => $sectionData) {
                if (!empty($sectionData)) {
                    fputcsv($output, [strtoupper($section) . ' REPORT']);
                    fputcsv($output, array_keys($sectionData[0]));
                    
                    foreach ($sectionData as $row) {
                        fputcsv($output, $row);
                    }
                    fputcsv($output, []); // Empty line between sections
                }
            }
        } else {
            if (!empty($data)) {
                fputcsv($output, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($output, $row);
                }
            }
        }
        
        fclose($output);
        exit;
    }

    private function exportToPDF($data, $filename, $reportType) {
        // Simple HTML-based PDF export
        $html = '<html><head><style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .section-header { background-color: #e9ecef; padding: 10px; margin: 20px 0; }
                .summary { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
                </style></head><body>';
        
        $html .= '<h1>' . htmlspecialchars($filename) . '</h1>';
        $html .= '<p>Generated on: ' . date('F j, Y g:i A') . '</p>';
        
        if ($reportType === 'comprehensive' && isset($data['summary'])) {
            $html .= '<div class="summary">';
            $html .= '<h3>Summary</h3>';
            foreach ($data['summary'] as $key => $value) {
                $html .= '<p><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong> ' . $value . '</p>';
            }
            $html .= '</div>';
        }
        
        if (is_array($data)) {
            foreach ($data as $section => $sectionData) {
                if (!empty($sectionData) && $section !== 'summary') {
                    $html .= '<div class="section-header">';
                    $html .= '<h2>' . ucfirst($section) . '</h2>';
                    $html .= '</div>';
                    
                    $html .= '<table>';
                    $html .= '<tr>';
                    foreach (array_keys($sectionData[0]) as $header) {
                        $html .= '<th>' . ucfirst(str_replace('_', ' ', $header)) . '</th>';
                    }
                    $html .= '</tr>';
                    
                    foreach ($sectionData as $row) {
                        $html .= '<tr>';
                        foreach ($row as $cell) {
                            $html .= '<td>' . htmlspecialchars($cell) . '</td>';
                        }
                        $html .= '</tr>';
                    }
                    $html .= '</table>';
                }
            }
        }
        
        $html .= '</body></html>';
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
        
        // In a real implementation, use a PDF library like TCPDF or Dompdf
        echo $html;
        exit;
    }

    private function exportToExcel($data, $filename, $reportType) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        
        if (is_array($data)) {
            foreach ($data as $section => $sectionData) {
                if (!empty($sectionData) && $section !== 'summary') {
                    $html .= '<h2>' . ucfirst($section) . '</h2>';
                    $html .= '<table border="1">';
                    $html .= '<tr>';
                    foreach (array_keys($sectionData[0]) as $header) {
                        $html .= '<th>' . ucfirst(str_replace('_', ' ', $header)) . '</th>';
                    }
                    $html .= '</tr>';
                    
                    foreach ($sectionData as $row) {
                        $html .= '<tr>';
                        foreach ($row as $cell) {
                            $html .= '<td>' . $cell . '</td>';
                        }
                        $html .= '</tr>';
                    }
                    $html .= '</table><br>';
                }
            }
        }
        
        $html .= '</body></html>';
        echo $html;
        exit;
    }
}
?>