<?php
/**
 * @var string $id Modal ID
 * @var string $title Modal title
 * @var string $size lg|sm|xl (optional)
 */
?>
<div class="modal fade" id="<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-<?= $size ?? 'md' ?>" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->section('modal_content') ?>
            </div>
            <?php if ($this->hasSection('modal_footer')): ?>
                <div class="modal-footer">
                    <?= $this->section('modal_footer') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 