<div class="user-card">
    <img src="<?= e($user->avatar) ?>" alt="<?= e($user->name) ?>">
    <h3><?= e($user->name) ?></h3>
    <p><?= e($user->email) ?></p>
    <div class="actions">
        <a href="/users/<?= e($user->id) ?>" class="btn">View Profile</a>
    </div>
</div> 