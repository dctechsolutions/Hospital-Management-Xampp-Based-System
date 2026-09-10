<?php
/**
 * Alert Flash Notification Component
 */
$flash = getFlash();
if ($flash):
?>
    <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>" role="alert">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>
