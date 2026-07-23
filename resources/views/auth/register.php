<?php $pageTitle = 'Kayıt Ol — ECU Dosya Servis'; ?>

<h2 class="auth-title">Kayıt Ol</h2>
<p class="auth-subtitle">Yeni hesap oluşturun</p>

<form method="POST" action="/register" id="registerForm">
    <?= \Core\View::csrf() ?>

    <div class="mb-3">
        <label for="name" class="form-label">Ad Soyad</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" class="form-control <?= \Core\View::hasError('name') ? 'is-invalid' : '' ?>"
                   id="name" name="name" value="<?= \Core\View::old('name') ?>" required>
        </div>
        <?= \Core\View::error('name') ?>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-posta Adresi</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control <?= \Core\View::hasError('email') ? 'is-invalid' : '' ?>"
                   id="email" name="email" value="<?= \Core\View::old('email') ?>" required>
        </div>
        <?= \Core\View::error('email') ?>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Telefon</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= \Core\View::old('phone') ?>">
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="company" class="form-label">Firma</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
                <input type="text" class="form-control" id="company" name="company" value="<?= \Core\View::old('company') ?>">
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Şifre</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control <?= \Core\View::hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" required minlength="8">
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <?= \Core\View::error('password') ?>
        <small class="form-text text-muted">En az 8 karakter</small>
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Şifre Tekrar</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-auth">
        <i class="fas fa-user-plus me-2"></i>Kayıt Ol
    </button>

    <div class="auth-divider">
        <span>veya</span>
    </div>

    <p class="text-center mb-0">
        Zaten hesabınız var mı? <a href="/login" class="auth-link fw-600">Giriş Yap</a>
    </p>
</form>
