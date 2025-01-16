<?php
/**
 * @var string $title Card title
 * @var string $type primary|success|warning|danger|info (optional)
 * @var bool $collapsible Make card collapsible (optional)
 * @var bool $removable Make card removable (optional)
 * @var bool $loading Show loading state (optional)
 */
?>
<div class="card <?= isset($type) ? "card-$type" : '' ?>" <?= isset($id) ? "id=\"$id\"" : '' ?>>
    <?php if (isset($title)): ?>
    <div class="card-header">
        <h3 class="card-title"><?= $title ?></h3>
        <div class="card-tools">
            <?php if ($loading ?? false): ?>
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            <?php endif; ?>
            
            <?php if ($collapsible ?? false): ?>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            <?php endif; ?>
            
            <?php if ($removable ?? false): ?>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="card-body">
        <?= $this->section('card_content') ?>
    </div>

    <?php if ($this->hasSection('card_footer')): ?>
        <div class="card-footer">
            <?= $this->section('card_footer') ?>
        </div>
    <?php endif; ?>
</div> 