<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= config('admin.title') ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/admin/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/css/adminlte.min.css">
    <?= $this->section('styles') ?>
</head>
<body class="hold-transition sidebar-mini <?= config('admin.theme.accent') ?>">
<div class="wrapper">
    <?= $this->include('admin.partials.navbar') ?>
    <?= $this->include('admin.partials.sidebar') ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <?= $this->include('admin.partials.content-header') ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?= $this->section('content') ?>
            </div>
        </section>
    </div>

    <?= $this->include('admin.partials.footer') ?>
</div>

<!-- jQuery -->
<script src="/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/admin/js/adminlte.min.js"></script>
<?= $this->section('scripts') ?>
</body>
</html> 