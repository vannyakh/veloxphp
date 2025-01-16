<?php
/**
 * @var array $columns Column definitions
 * @var string $url API endpoint for data
 * @var string $id Table ID
 */
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $title ?? 'Data Table' ?></h3>
        <?php if (isset($createUrl)): ?>
            <div class="card-tools">
                <a href="<?= $createUrl ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create New
                </a>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table id="<?= $id ?>" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?= $column['label'] ?></th>
                    <?php endforeach; ?>
                    <?php if (isset($actions)): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<?php $this->end() ?>

<?php $this->push('scripts') ?>
<script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function() {
    $('#<?= $id ?>').DataTable({
        processing: true,
        serverSide: true,
        ajax: '<?= $url ?>',
        columns: <?= json_encode(array_map(fn($col) => ['data' => $col['field']], $columns)) ?>,
        <?php if (isset($actions)): ?>
        columnDefs: [{
            targets: -1,
            data: null,
            render: function(data, type, row) {
                return `
                    <div class="btn-group">
                        <a href="/admin/${row.id}/edit" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem(${row.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            }
        }]
        <?php endif; ?>
    });
});

function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax({
            url: '/admin/' + id,
            type: 'DELETE',
            success: function() {
                $('#<?= $id ?>').DataTable().ajax.reload();
            }
        });
    }
}
</script>
<?php $this->end() ?> 