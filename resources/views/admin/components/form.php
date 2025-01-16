<?php
/**
 * @var array $fields Form field definitions
 * @var string $action Form action URL
 * @var string $method HTTP method
 */
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $title ?? 'Form' ?></h3>
    </div>
    <form action="<?= $action ?>" method="<?= strtolower($method) === 'get' ? 'get' : 'post' ?>" enctype="multipart/form-data">
        <div class="card-body">
            <?php if (strtolower($method) !== 'get' && strtolower($method) !== 'post'): ?>
                <input type="hidden" name="_method" value="<?= $method ?>">
            <?php endif; ?>
            
            <?php foreach ($fields as $field): ?>
                <div class="form-group">
                    <label for="<?= $field['name'] ?>"><?= $field['label'] ?></label>
                    
                    <?php switch ($field['type']): 
                        case 'textarea': ?>
                            <textarea 
                                class="form-control <?= isset($field['class']) ? $field['class'] : '' ?>"
                                id="<?= $field['name'] ?>"
                                name="<?= $field['name'] ?>"
                                rows="<?= $field['rows'] ?? 3 ?>"
                                <?= isset($field['required']) && $field['required'] ? 'required' : '' ?>
                            ><?= $field['value'] ?? '' ?></textarea>
                            <?php break; ?>
                            
                        <?php case 'select': ?>
                            <select 
                                class="form-control <?= isset($field['class']) ? $field['class'] : '' ?>"
                                id="<?= $field['name'] ?>"
                                name="<?= $field['name'] ?>"
                                <?= isset($field['required']) && $field['required'] ? 'required' : '' ?>
                            >
                                <?php foreach ($field['options'] as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= isset($field['value']) && $field['value'] == $value ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php break; ?>
                            
                        <?php case 'file': ?>
                            <div class="custom-file">
                                <input type="file" 
                                    class="custom-file-input <?= isset($field['class']) ? $field['class'] : '' ?>"
                                    id="<?= $field['name'] ?>"
                                    name="<?= $field['name'] ?>"
                                    <?= isset($field['required']) && $field['required'] ? 'required' : '' ?>
                                >
                                <label class="custom-file-label" for="<?= $field['name'] ?>">Choose file</label>
                            </div>
                            <?php break; ?>
                            
                        <?php default: ?>
                            <input 
                                type="<?= $field['type'] ?>"
                                class="form-control <?= isset($field['class']) ? $field['class'] : '' ?>"
                                id="<?= $field['name'] ?>"
                                name="<?= $field['name'] ?>"
                                value="<?= $field['value'] ?? '' ?>"
                                <?= isset($field['required']) && $field['required'] ? 'required' : '' ?>
                            >
                    <?php endswitch; ?>
                    
                    <?php if (isset($field['help'])): ?>
                        <small class="form-text text-muted"><?= $field['help'] ?></small>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?= $cancelUrl ?? 'javascript:history.back()' ?>" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

<?php if (isset($hasFileUpload) && $hasFileUpload): ?>
<?php $this->push('scripts') ?>
<script src="/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
$(document).ready(function () {
    bsCustomFileInput.init();
});
</script>
<?php $this->end() ?>
<?php endif; ?> 