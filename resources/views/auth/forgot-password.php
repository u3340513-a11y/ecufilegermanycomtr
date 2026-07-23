<?php $pageTitle = 'Şifremi Unuttum — ECU Dosya Servis'; ?>

<h2 class="auth-title">Şifremi Unuttum</h2>
<p class="auth-subtitle">E-posta adresinizi girin, size bir sıfırlama bağlantısı göndereceğiz</p>

<form method="POST" action="/forgot-password">
    <?= \Core\View::csrf() ?>

    <div class="mb-4">
        <label for="email" class="form-label">E-posta Adresi</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control <?= \Core\View::hasError('email') ? 'is-invalid' : '' ?>"
                   id="email" name="email" value="<?= \Core\View::old('email') ?>" required autofocus>
        </div>
        <?= \Core\View::error('email') ?>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-auth">
        <i class="fas fa-paper-plane me-2"></i>Sıfırlama Bağlantısı Gönder
    </button>

    <div class="auth-divider"><span>veya</span></div>

    <p class="text-center mb-0">
        <a href="/login" class="auth-link fw-600"><i class="fas fa-arrow-left me-1"></i>Giriş sayfasına dön</a>
    </p>
</form>
