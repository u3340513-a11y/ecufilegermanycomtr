<?php $pageTitle = 'Giriş Yap — ECU Dosya Servis'; ?>

<h2 class="auth-title">Giriş Yap</h2>
<p class="auth-subtitle">Hesabınıza erişmek için bilgilerinizi girin</p>

<form method="POST" action="/login" id="loginForm">
    <?= \Core\View::csrf() ?>

    <div class="mb-3">
        <label for="email" class="form-label">E-posta Adresi</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control <?= \Core\View::hasError('email') ? 'is-invalid' : '' ?>"
                   id="email" name="email" value="<?= \Core\View::old('email') ?>" required autofocus>
        </div>
        <?= \Core\View::error('email') ?>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Şifre</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control <?= \Core\View::hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" required>
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <?= \Core\View::error('password') ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Beni hatırla</label>
        </div>
        <a href="/forgot-password" class="auth-link">Şifremi Unuttum</a>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-auth">
        <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
    </button>

    <div class="auth-divider">
        <span>veya</span>
    </div>

    <p class="text-center mb-0">
        Hesabınız yok mu? <a href="/register" class="auth-link fw-600">Kayıt Ol</a>
    </p>
</form>
