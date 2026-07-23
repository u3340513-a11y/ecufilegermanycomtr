<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \Core\View::escape($pageTitle ?? 'ECU Dosya Servis') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= \Core\App::asset('css/app.css') ?>" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="fas fa-microchip"></i>
                <span>ECU Platform</span>
            </div>
            <?= \Core\View::alert() ?>
            <?= $content ?>
        </div>
        <div class="auth-footer">
            <p>&copy; <?= date('Y') ?> ECU Dosya Servis. Tüm hakları saklıdır.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= \Core\App::asset('js/app.js') ?>"></script>
</body>
</html>
