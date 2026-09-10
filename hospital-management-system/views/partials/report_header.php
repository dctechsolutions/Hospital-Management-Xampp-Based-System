<?php
/**
 * Common Hospital Report Header
 * YASMEEN MATERNITY AND MEDICAL CENTER
 * Reference: R-85647
 * Reusable Component across ALL reports and printables
 * Developed_By_DCtechsolutions
 */

if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../app/config/app.php';
}

$reportTitle = $reportTitle ?? 'Hospital Report';
$reportSubTitle = $reportSubTitle ?? '';
$generatedAt = date('d-M-Y h:i A');
?>
<div class="common-report-header" id="common-report-header">
    <div class="report-header-grid">
        <div class="report-logo-col">
            <img src="<?php echo BASE_URL; ?>assets/images/yasmeen-logo.png" 
                 alt="Yasmeen Maternity & Medical Center Logo" 
                 class="report-logo"
                 onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/yasmeen-logo.svg';">
        </div>
        <div class="report-title-col">
            <h1 class="hospital-name-primary">YASMEEN</h1>
            <h2 class="hospital-name-secondary">MATERNITY AND MEDICAL CENTER</h2>
            <div class="hospital-ref-badge">
                <span class="ref-label">Registration / Reference:</span>
                <strong class="ref-number">R-85647</strong>
            </div>
        </div>
    </div>
    
    <div class="report-meta-bar">
        <div class="report-title-section">
            <h3 class="report-document-title"><?php echo htmlspecialchars($reportTitle); ?></h3>
            <?php if (!empty($reportSubTitle)): ?>
                <p class="report-document-subtitle"><?php echo htmlspecialchars($reportSubTitle); ?></p>
            <?php endif; ?>
        </div>
        <div class="report-timestamp-section">
            <span class="report-date-label">Date Generated:</span>
            <span class="report-date-value"><?php echo $generatedAt; ?></span>
        </div>
    </div>
    <hr class="report-divider">
</div>
