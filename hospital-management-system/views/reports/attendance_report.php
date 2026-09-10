<?php
/**
 * A4 Printable Attendance Report
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */

$year = (int)($_GET['year'] ?? date('Y'));
$month = (int)($_GET['month'] ?? date('m'));
$day = (int)($_GET['day'] ?? 0);

$records = Attendance::getFiltered($year, $month, $day);

$reportTitle = 'EMPLOYEE ATTENDANCE REPORT';
$reportSubTitle = 'STAFF DUTY RECORD';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/print.css">
</head>
<body style="background-color: #f8fafc;">
    <!-- Print Action Bar (hidden when printing) -->
    <div class="no-print" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; max-width: 900px; margin: 0 auto 20px auto;">
        <div>
            <strong style="color: #047857;">Employee Attendance Report (A4 Format)</strong>
            <span style="color: #64748b; font-size: 13px; margin-left: 10px;"><?php echo count($records); ?> staff records</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print();" class="btn btn-primary" style="padding: 7px 18px;">Print Report</button>
            <button onclick="window.close();" class="btn btn-secondary" style="padding: 7px 14px;">Close</button>
        </div>
    </div>

    <div class="report-paper">
        <!-- Reusable Common Hospital Header -->
        <?php require __DIR__ . '/../partials/report_header.php'; ?>

        <!-- Required Attendance Filter Display Section -->
        <div style="margin: 16px 0; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; display: flex; justify-content: space-between; font-size: 13px;">
            <div>
                <strong>Day:</strong> 
                <span style="text-decoration: underline; font-weight: 600;">
                    <?php echo $day > 0 ? sprintf('%02d', $day) : 'All Days'; ?>
                </span>
            </div>
            <div>
                <strong>Month:</strong> 
                <span style="text-decoration: underline; font-weight: 600;">
                    <?php echo $month > 0 ? date('F', mktime(0, 0, 0, $month, 1)) : 'All Months'; ?>
                </span>
            </div>
            <div>
                <strong>Year:</strong> 
                <span style="text-decoration: underline; font-weight: 600;">
                    <?php echo $year; ?>
                </span>
            </div>
        </div>

        <!-- Attendance Report Table -->
        <table class="report-table data-table" style="width: 100%; margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">Sr. No.</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th style="width: 100px;">Time In</th>
                    <th style="width: 100px;">Time Out</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: #64748b;">
                            No attendance records found for the selected date criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $sr = 1; foreach ($records as $row): ?>
                        <tr>
                            <td style="text-align: center; font-weight: bold;"><?php echo $sr++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['employee_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['designation']); ?></td>
                            <td><?php echo !empty($row['time_in']) ? date('h:i A', strtotime($row['time_in'])) : '-'; ?></td>
                            <td><?php echo !empty($row['time_out']) ? date('h:i A', strtotime($row['time_out'])) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($row['remarks'] ?: 'Present'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Reusable Common Hospital Footer -->
        <?php require __DIR__ . '/../partials/report_footer.php'; ?>
    </div>
</body>
</html>
