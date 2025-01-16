<?php $this->layout('app') ?>

<?php $this->section('content') ?>
<div class="container">
    <h1>Edit Profile</h1>

    <form action="<?= url("/users/{$user->id}") ?>" method="POST">
        <?= csrf_field() ?>
        <?= method_field('PUT') ?>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" 
                   name="name" 
                   value="<?= old('name', $user->name) ?>" 
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" 
                   name="email" 
                   value="<?= old('email', $user->email) ?>" 
                   class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
<?php $this->endSection() ?> 