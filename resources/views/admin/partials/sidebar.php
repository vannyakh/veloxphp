<aside class="main-sidebar <?= config('admin.theme.sidebar') ?> elevation-4">
    <a href="/admin" class="brand-link">
        <span class="brand-text font-weight-light"><?= config('admin.logo') ?></span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= auth()->user()->avatar ?? '/admin/img/default-avatar.png' ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= auth()->user()->name ?></a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <?php foreach (config('admin.menu') as $group): ?>
                    <?php if (isset($group['header'])): ?>
                        <li class="nav-header"><?= $group['header'] ?></li>
                    <?php endif; ?>

                    <?php foreach ($group['items'] as $item): ?>
                        <li class="nav-item">
                            <a href="<?= $item['url'] ?>" class="nav-link <?= request()->is($item['url']) ? 'active' : '' ?>">
                                <i class="nav-icon <?= $item['icon'] ?>"></i>
                                <p><?= $item['text'] ?></p>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</aside> 