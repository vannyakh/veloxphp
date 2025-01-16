<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My App' ?></title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header>
        <?= $this->component('navbar') ?>
    </header>

    <main>
        <?= $this->yield('content') ?>
    </main>

    <footer>
        <?= $this->yield('footer') ?>
    </footer>

    <script src="/js/app.js"></script>
</body>
</html> 