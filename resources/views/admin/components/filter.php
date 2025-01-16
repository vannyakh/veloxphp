<?php
/**
 * @var array $filters Filter definitions
 * @var string $target DataTable ID to refresh
 */
?>
<div class="card card-outline card-primary collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Filters</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form id="filter-form" class="row">
            <?php foreach ($filters as $filter): ?>
                <div class="col-md-<?= $filter['width'] ?? '3' ?> mb-3">
                    <div class="form-group">
                        <label><?= $filter['label'] ?></label>
                        
                        <?php switch ($filter['type']):
                            case 'select': ?>
                                <select class="form-control" name="<?= $filter['name'] ?>">
                                    <option value="">All</option>
                                    <?php foreach ($filter['options'] as $value => $label): ?>
                                        <option value="<?= $value ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php break; ?>

                            <?php case 'daterange': ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control daterange" 
                                           name="<?= $filter['name'] ?>"
                                           data-start-name="<?= $filter['start_name'] ?>"
                                           data-end-name="<?= $filter['end_name'] ?>">
                                </div>
                                <?php break; ?>

                            <?php default: ?>
                                <input type="<?= $filter['type'] ?>" 
                                       class="form-control" 
                                       name="<?= $filter['name'] ?>"
                                       placeholder="<?= $filter['placeholder'] ?? '' ?>">
                        <?php endswitch; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply</button>
        <button type="button" class="btn btn-default" onclick="resetFilters()">Reset</button>
    </div>
</div>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker.css">
<?php $this->end() ?>

<?php $this->push('scripts') ?>
<script src="/admin/plugins/moment/moment.min.js"></script>
<script src="/admin/plugins/daterangepicker/daterangepicker.js"></script>
<script>
$(function() {
    $('.daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('.daterange').on('apply.daterangepicker', function(ev, picker) {
        const startName = $(this).data('start-name');
        const endName = $(this).data('end-name');
        $(`[name="${startName}"]`).val(picker.startDate.format('YYYY-MM-DD'));
        $(`[name="${endName}"]`).val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});

function applyFilters() {
    const filters = $('#filter-form').serialize();
    $('#<?= $target ?>').DataTable().ajax.url('<?= $url ?>?' + filters).load();
}

function resetFilters() {
    $('#filter-form')[0].reset();
    applyFilters();
}
</script>
<?php $this->end() ?> 