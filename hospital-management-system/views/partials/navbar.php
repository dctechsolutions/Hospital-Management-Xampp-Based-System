<?php
/**
 * Navigation Bar Component
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */

$userRole = Auth::role();
$userName = Auth::user()['full_name'] ?? 'Staff';
$currentRoute = $_GET['route'] ?? 'dashboard';
?>
<header class="navbar-hospital no-print">
    <a href="<?php echo BASE_URL; ?>index.php?route=dashboard" class="brand-section">
        <img src="<?php echo BASE_URL; ?>assets/images/yasmeen-logo.png" 
             alt="Logo" 
             class="brand-logo"
             onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/yasmeen-logo.svg';">
        <div>
            <div class="brand-text-main">YASMEEN</div>
            <div class="brand-text-sub">MATERNITY AND MEDICAL CENTER <span class="brand-ref">(R-85647)</span></div>
        </div>
    </a>

    <nav>
        <ul class="nav-links">
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?route=dashboard" 
                   class="nav-link <?php echo $currentRoute === 'dashboard' ? 'active' : ''; ?>">
                   Dashboard
                </a>
            </li>

            <?php if ($userRole === 'administrator' || $userRole === 'receptionist'): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>index.php?route=patients" 
                       class="nav-link <?php echo in_array($currentRoute, ['patients', 'patient_create', 'patient_view']) ? 'active' : ''; ?>">
                       Patients
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>index.php?route=attendance" 
                       class="nav-link <?php echo $currentRoute === 'attendance' ? 'active' : ''; ?>">
                       Attendance
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($userRole === 'administrator' || $userRole === 'pharmacist'): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" 
                       class="nav-link <?php echo in_array($currentRoute, ['pharmacy_stock', 'pharmacy_medicines', 'pharmacy_purchase', 'pharmacy_issue', 'pharmacy_patient_history', 'pharmacy_financial']) ? 'active' : ''; ?>">
                       Pharmacy
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="<?php echo BASE_URL; ?>index.php?route=reports" 
                   class="nav-link <?php echo $currentRoute === 'reports' ? 'active' : ''; ?>">
                   Reports
                </a>
            </li>

            <?php if ($userRole === 'administrator'): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>index.php?route=backup" 
                       class="nav-link <?php echo in_array($currentRoute, ['settings', 'backup']) ? 'active' : ''; ?>">
                       Backup
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="nav-user">
        <span class="user-badge"><?php echo htmlspecialchars($userRole); ?>: <?php echo htmlspecialchars($userName); ?></span>
        <a href="<?php echo BASE_URL; ?>index.php?route=logout" class="btn btn-secondary btn-sm">Logout</a>
    </div>
</header>
