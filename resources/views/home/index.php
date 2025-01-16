<?php $this->layout('app') ?>

<?php $this->section('content') ?>
    <div class="container">
        <h1><?= e($title) ?></h1>
        
        <div class="users-list">
            <?php foreach ($users as $user): ?>
                <?= $this->component('user-card', ['user' => $user]) ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php $this->endSection() ?>

<?php $this->section('footer') ?>
    <p>&copy; <?= date('Y') ?> My App. All rights reserved.</p>
<?php $this->endSection() ?> 