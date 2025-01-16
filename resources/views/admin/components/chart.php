<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $title ?></h3>
    </div>
    <div class="card-body">
        <canvas id="<?= $id ?>" height="<?= $height ?? '300' ?>"></canvas>
    </div>
</div>

<?php $this->push('scripts') ?>
<script src="/admin/plugins/chart.js/Chart.min.js"></script>
<script>
$(function() {
    new Chart(document.getElementById('<?= $id ?>').getContext('2d'), {
        type: '<?= $type ?>',
        data: <?= json_encode($data) ?>,
        options: <?= json_encode($options ?? (object)[]) ?>
    });
});
</script>
<?php $this->end() ?> 