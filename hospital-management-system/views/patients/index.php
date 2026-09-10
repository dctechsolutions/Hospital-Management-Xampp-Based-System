<?php
/**
 * Patients List View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Directory - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">Patient Records</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Search and manage hospital patient registrations</p>
            </div>
            <div class="page-actions">
                <a href="<?php echo BASE_URL; ?>index.php?route=patient_create" class="btn btn-primary">+ Register Patient</a>
            </div>
        </div>

        <div class="card" style="padding: 16px; margin-bottom: 16px;">
            <form action="<?php echo BASE_URL; ?>index.php" method="GET" style="display: flex; gap: 10px; align-items: center;">
                <input type="hidden" name="route" value="patients">
                <div style="flex-grow: 1;">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by Patient ID (e.g. P-2026-000001), Name, Relation, or Contact..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="<?php echo BASE_URL; ?>index.php?route=patients" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Unique ID</th>
                            <th>Reg. Date</th>
                            <th>Patient Name</th>
                            <th>Relation</th>
                            <th>Age / Sex</th>
                            <th>Contact</th>
                            <th>Doctor / LHV</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($patients)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 32px; color: #64748b;">
                                    No patient records found. Click <strong>+ Register Patient</strong> to create one.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $p): ?>
                                <tr>
                                    <td><strong style="color: #047857;"><?php echo htmlspecialchars($p['unique_id']); ?></strong></td>
                                    <td><?php echo date('d-M-Y', strtotime($p['registration_date'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($p['relation_type'] . ' ' . $p['relation_name']); ?></td>
                                    <td><?php echo htmlspecialchars($p['age'] . 'y / ' . $p['sex']); ?></td>
                                    <td><?php echo htmlspecialchars($p['contact'] ?: '-'); ?></td>
                                    <td><?php echo htmlspecialchars($p['doctor_name'] ?: 'General OPD'); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>index.php?route=patient_view&id=<?php echo $p['id']; ?>" class="btn btn-secondary btn-sm">
                                            View & Visits
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
