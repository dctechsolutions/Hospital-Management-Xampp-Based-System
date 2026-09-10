<?php
/**
 * Employee Attendance Management View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */

$selectedYear = (int)($_GET['year'] ?? date('Y'));
$selectedMonth = (int)($_GET['month'] ?? date('m'));
$selectedDay = (int)($_GET['day'] ?? date('d'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/print.css" media="print">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header no-print">
            <div>
                <h1 class="page-title">Employee Attendance</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Track and record hospital staff daily attendance</p>
            </div>
            <div class="page-actions">
                <a href="<?php echo BASE_URL; ?>index.php?route=attendance_report&year=<?php echo $selectedYear; ?>&month=<?php echo $selectedMonth; ?>&day=<?php echo $selectedDay; ?>" 
                   target="_blank" class="btn btn-secondary">
                   Print Attendance Report (A4)
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card no-print" style="padding: 16px; margin-bottom: 20px;">
            <form action="<?php echo BASE_URL; ?>index.php" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <input type="hidden" name="route" value="attendance">
                
                <div class="form-group" style="min-width: 110px;">
                    <label class="form-label" for="filter_day">Day</label>
                    <select id="filter_day" name="day" class="form-control">
                        <option value="0">All Days</option>
                        <?php for ($d = 1; $d <= 31; $d++): ?>
                            <option value="<?php echo $d; ?>" <?php echo $selectedDay === $d ? 'selected' : ''; ?>>
                                <?php echo sprintf('%02d', $d); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group" style="min-width: 140px;">
                    <label class="form-label" for="filter_month">Month</label>
                    <select id="filter_month" name="month" class="form-control">
                        <option value="0">All Months</option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo $selectedMonth === $m ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group" style="min-width: 120px;">
                    <label class="form-label" for="filter_year">Year</label>
                    <select id="filter_year" name="year" class="form-control">
                        <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo $selectedYear === $y ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Filter Records</button>
            </form>
        </div>

        <!-- Attendance Entry Form (Screen Only) -->
        <div class="card no-print" style="margin-bottom: 20px;">
            <h3 class="card-title">+ Record Daily Staff Attendance</h3>
            <form action="<?php echo BASE_URL; ?>index.php?route=attendance_store" method="POST">
                <?php echo CSRF::field(); ?>

                <div class="form-grid" style="margin-bottom: 16px;">
                    <!-- Employee selection with auto-filling designation -->
                    <div class="form-group">
                        <label class="form-label" for="emp_select">Employee Name *</label>
                        <select id="emp_select" name="employee_id" class="form-control" required onchange="updateDesignation(this)">
                            <option value="">-- Select Employee --</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?php echo $emp['id']; ?>" data-designation="<?php echo htmlspecialchars($emp['designation']); ?>">
                                    <?php echo htmlspecialchars($emp['full_name']); ?> (<?php echo htmlspecialchars($emp['employee_code']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Auto-filled Designation -->
                    <div class="form-group">
                        <label class="form-label" for="emp_designation">Designation</label>
                        <input type="text" id="emp_designation" class="form-control" placeholder="Auto-populated from employee master" readonly>
                    </div>

                    <!-- Attendance Date -->
                    <div class="form-group">
                        <label class="form-label" for="att_date">Attendance Date *</label>
                        <input type="date" id="att_date" name="attendance_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <!-- Time In -->
                    <div class="form-group">
                        <label class="form-label" for="time_in">Time In</label>
                        <input type="time" id="time_in" name="time_in" class="form-control" value="08:00">
                    </div>

                    <!-- Time Out -->
                    <div class="form-group">
                        <label class="form-label" for="time_out">Time Out</label>
                        <input type="time" id="time_out" name="time_out" class="form-control" value="16:00">
                    </div>

                    <!-- Remarks -->
                    <div class="form-group">
                        <label class="form-label" for="remarks">Remarks</label>
                        <input type="text" id="remarks" name="remarks" class="form-control" placeholder="e.g. Present, On Duty, Late 10m">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Attendance</button>
            </form>
        </div>

        <!-- Attendance Records Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">
                    Attendance Records: 
                    <?php 
                        if ($selectedDay > 0) echo sprintf('%02d ', $selectedDay);
                        if ($selectedMonth > 0) echo date('F ', mktime(0, 0, 0, $selectedMonth, 1));
                        echo $selectedYear;
                    ?>
                </h3>
                <span style="font-size: 13px; color: #64748b;"><?php echo count($records); ?> Entries</span>
            </div>

            <div class="table-responsive">
                <table class="data-table report-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Sr. No.</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($records)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 32px; color: #64748b;">
                                    No attendance records found for this date filter.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $srNo = 1; foreach ($records as $row): ?>
                                <tr>
                                    <td><strong><?php echo $srNo++; ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($row['employee_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['designation']); ?></td>
                                    <td><?php echo date('d-M-Y', strtotime($row['attendance_date'])); ?></td>
                                    <td><?php echo !empty($row['time_in']) ? date('h:i A', strtotime($row['time_in'])) : '-'; ?></td>
                                    <td><?php echo !empty($row['time_out']) ? date('h:i A', strtotime($row['time_out'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($row['remarks'] ?: 'Present'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function updateDesignation(select) {
            const opt = select.options[select.selectedIndex];
            const desig = opt.getAttribute('data-designation') || '';
            document.getElementById('emp_designation').value = desig;
        }
    </script>
</body>
</html>
