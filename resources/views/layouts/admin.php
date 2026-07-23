<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \Core\View::escape($pageTitle ?? 'Yönetim') ?> — ECU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= \Core\App::asset('css/app.css') ?>" rel="stylesheet">
    <link href="<?= \Core\App::asset('css/admin.css') ?>" rel="stylesheet">
</head>
<body class="app-body admin-body">
    <?php \Core\View::partial('admin-sidebar') ?>
    <div class="app-main">
        <?php \Core\View::partial('header') ?>
        <div class="app-content">
            <?= \Core\View::alert() ?>
            <?= $content ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= \Core\App::asset('js/app.js') ?>"></script>
    <script src="<?= \Core\App::asset('js/admin.js') ?>"></script>
    <?php if (isset($extraJs)): ?><?= $extraJs ?><?php endif; ?>
</body>
</html>
