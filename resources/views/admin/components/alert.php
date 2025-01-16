<?php
/**
 * @var string $type success|info|warning|danger
 * @var string $message Alert message
 * @var bool $dismissible Make alert dismissible
 * @var string $title Alert title (optional)
 */
?>
<div class="alert alert-<?= $type ?> <?= ($dismissible ?? false) ? 'alert-dismissible' : '' ?> fade show" role="alert">
    <?php if (isset($title)): ?>
        <h5><i class="icon fas fa-<?= $icon ?? 'info' ?>"></i> <?= $title ?></h5>
    <?php endif; ?>
    
    <?= $message ?>
    
    <?php if ($dismissible ?? false): ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    <?php endif; ?>
</div> 