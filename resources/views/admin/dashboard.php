<?php $this->layout('admin::layouts.app') ?>

<?php $this->section('content') ?>
<div class="row">
    <?= $this->include('admin::components.stats-card', [
        'color' => 'info',
        'value' => $totalUsers,
        'label' => 'Total Users',
        'icon' => 'users',
        'url' => '/admin/users'
    ]) ?>
    
    <?= $this->include('admin::components.stats-card', [
        'color' => 'success',
        'value' => $activeUsers,
        'label' => 'Active Users',
        'icon' => 'user-check'
    ]) ?>
    
    <?= $this->include('admin::components.stats-card', [
        'color' => 'warning',
        'value' => $pendingUsers,
        'label' => 'Pending Users',
        'icon' => 'user-clock'
    ]) ?>
    
    <?= $this->include('admin::components.stats-card', [
        'color' => 'danger',
        'value' => $blockedUsers,
        'label' => 'Blocked Users',
        'icon' => 'user-slash'
    ]) ?>
</div>

<div class="row">
    <div class="col-md-8">
        <?= $this->include('admin::components.datatable', [
            'id' => 'latest-users',
            'title' => 'Latest Users',
            'url' => '/admin/api/users',
            'columns' => [
                ['field' => 'name', 'label' => 'Name'],
                ['field' => 'email', 'label' => 'Email'],
                ['field' => 'created_at', 'label' => 'Registered'],
                ['field' => 'status', 'label' => 'Status']
            ],
            'actions' => true
        ]) ?>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group">
                    <a href="/admin/users/create" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-plus mr-2"></i> Add New User
                    </a>
                    <a href="/admin/settings" class="list-group-item list-group-item-action">
                        <i class="fas fa-cogs mr-2"></i> System Settings
                    </a>
                    <a href="/admin/logs" class="list-group-item list-group-item-action">
                        <i class="fas fa-list mr-2"></i> View Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?> 