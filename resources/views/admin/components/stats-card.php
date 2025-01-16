<div class="col-lg-3 col-6">
    <div class="small-box bg-<?= $color ?? 'info' ?>">
        <div class="inner">
            <h3><?= $value ?></h3>
            <p><?= $label ?></p>
        </div>
        <div class="icon">
            <i class="fas fa-<?= $icon ?>"></i>
        </div>
        <?php if (isset($url)): ?>
            <a href="<?= $url ?>" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        <?php endif; ?>
    </div>
</div> 