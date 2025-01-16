<?php $this->layout('app') ?>

<?php $this->section('content') ?>
<div class="container">
    <h1>Update Profile Picture</h1>

    <form action="<?= url('/profile/avatar') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="avatar">Profile Picture</label>
            <input type="file" 
                   name="avatar" 
                   id="avatar" 
                   accept="image/*"
                   class="form-control">
            <?php if ($errors->has('avatar')): ?>
                <span class="text-danger"><?= $errors->first('avatar') ?></span>
            <?php endif; ?>
        </div>

        <?php if ($user->avatar): ?>
            <div class="current-avatar">
                <img src="<?= asset($user->avatar) ?>" alt="Current Avatar">
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Upload Avatar</button>
    </form>
</div>
<?php $this->endSection() ?> 