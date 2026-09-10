<?php
/**
 * Pharmacy Financial and Profit Report View (Administrator Only)
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */

$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Financial Report - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/print.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header no-print">
            <div>
                <h1 class="page-title">Pharmacy Financial & Gross Profit Analysis</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Confidential executive report for Administrator</p>
            </div>
            <div class="page-actions">
                <button onclick="window.print();" class="btn btn-primary">Print Financial Report (A4)</button>
                <a href="<?php echo BASE_URL; ?>index.php?route=reports" class="btn btn-secondary">&larr; Reports Center</a>
            </div>
        </div>

        <!-- Date Filter Form (Screen Only) -->
        <div class="card no-print" style="padding: 16px; margin-bottom: 20px;">
            <form action="<?php echo BASE_URL; ?>index.php" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <input type="hidden" name="route" value="pharmacy_financial">

                <div class="form-group" style="min-width: 160px;">
                    <label class="form-label" for="start_date">From Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($startDate); ?>" required>
                </div>

                <div class="form-group" style="min-width: 160px;">
                    <label class="form-label" for="end_date">To Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($endDate); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Filter Report</button>
            </form>
        </div>

        <!-- Summary KPIs (Screen Only) -->
        <div class="no-print" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="card" style="margin-bottom: 0;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Total Sales Revenue</span>
                <div style="font-size: 26px; font-weight: 800; color: #047857; margin-top: 4px;">
                    Rs. <?php echo number_format($reportData['summary']['total_sales'], 2); ?>
                </div>
            </div>

            <div class="card" style="margin-bottom: 0;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Total Purchase Cost</span>
                <div style="font-size: 26px; font-weight: 800; color: #475569; margin-top: 4px;">
                    Rs. <?php echo number_format($reportData['summary']['total_cost'], 2); ?>
                </div>
            </div>

            <div class="card" style="margin-bottom: 0;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Total Gross Profit</span>
                <div style="font-size: 26px; font-weight: 800; color: #0284c7; margin-top: 4px;">
                    Rs. <?php echo number_format($reportData['summary']['total_profit'], 2); ?>
                </div>
                <span style="font-size: 12px; color: #0284c7; font-weight: 600;">
                    Margin: <?php echo $reportData['summary']['total_sales'] > 0 ? round(($reportData['summary']['total_profit'] / $reportData['summary']['total_sales']) * 100, 1) : 0; ?>%
                </span>
            </div>
        </div>

        <!-- Printable A4 Section -->
        <div class="report-paper">
            <?php 
                $reportTitle = 'PHARMACY FINANCIAL & PROFIT STATEMENT';
                $reportSubTitle = 'Period: ' . date('d-M-Y', strtotime($startDate)) . ' to ' . date('d-M-Y', strtotime($endDate));
                require __DIR__ . '/../partials/report_header.php'; 
            ?>

            <div style="margin: 16px 0; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; display: flex; justify-content: space-between;">
                <div><strong>From:</strong> <?php echo date('d-M-Y', strtotime($startDate)); ?></div>
                <div><strong>To:</strong> <?php echo date('d-M-Y', strtotime($endDate)); ?></div>
                <div><strong>Items Analyzed:</strong> <?php echo count($reportData['items']); ?></div>
            </div>

            <table class="report-table data-table" style="width: 100%; margin-top: 14px;">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Cost Price</th>
                        <th style="text-align: right;">Sale Price</th>
                        <th style="text-align: right;">Purchase Total</th>
                        <th style="text-align: right;">Sales Total</th>
                        <th style="text-align: right;">Gross Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reportData['items'])): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 24px; color: #64748b;">
                                No sales recorded during this date period.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reportData['items'] as $item): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($item['medicine_name']); ?></strong></td>
                                <td style="text-align: center;"><?php echo $item['quantity_sold']; ?></td>
                                <td style="text-align: right;">Rs. <?php echo number_format($item['cost_price'], 2); ?></td>
                                <td style="text-align: right;">Rs. <?php echo number_format($item['sale_price'], 2); ?></td>
                                <td style="text-align: right;">Rs. <?php echo number_format($item['total_cost'], 2); ?></td>
                                <td style="text-align: right;">Rs. <?php echo number_format($item['total_sales'], 2); ?></td>
                                <td style="text-align: right;"><strong style="color: #047857;">Rs. <?php echo number_format($item['gross_profit'], 2); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                        <tfoot>
                            <tr style="font-weight: bold; background: #f8fafc;">
                                <td colspan="4" style="text-align: right;">Total Financial Summary:</td>
                                <td style="text-align: right;">Rs. <?php echo number_format($reportData['summary']['total_cost'], 2); ?></td>
                                <td style="text-align: right;">Rs. <?php echo number_format($reportData['summary']['total_sales'], 2); ?></td>
                                <td style="text-align: right; color: #047857; font-size: 14px;">Rs. <?php echo number_format($reportData['summary']['total_profit'], 2); ?></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php require __DIR__ . '/../partials/report_footer.php'; ?>
        </div>
    </main>
</body>
</html>
