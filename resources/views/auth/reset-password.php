<?php $pageTitle = 'Şifre Sıfırla — ECU Dosya Servis'; ?>

<h2 class="auth-title">Yeni Şifre Belirle</h2>
<p class="auth-subtitle">Yeni şifrenizi girin</p>

<form method="POST" action="/reset-password">
    <?= \Core\View::csrf() ?>
    <input type="hidden" name="token" value="<?= \Core\View::escape($token ?? '') ?>">

    <div class="mb-3">
        <label for="password" class="form-label">Yeni Şifre</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" required minlength="8">
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <?= \Core\View::error('password') ?>
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Şifre Tekrar</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-auth">
        <i class="fas fa-key me-2"></i>Şifremi Değiştir
    </button>
</form>
