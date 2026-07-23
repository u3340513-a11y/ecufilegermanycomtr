<?php $pageTitle = 'E-posta Doğrulandı — ECU Dosya Servis'; ?>

<div class="text-center">
    <div class="auth-icon mb-4">
        <div class="verification-success-icon">
            <i class="fas fa-check-circle fa-4x" style="color: #22c55e;"></i>
        </div>
    </div>
    <h2 class="auth-title">E-posta Doğrulandı!</h2>
    <p class="auth-subtitle mb-4">Hesabınız başarıyla aktif edildi. Artık giriş yapabilirsiniz.</p>

    <a href="/login" class="btn btn-primary w-100 btn-auth" id="goToLogin">
        <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
    </a>
</div>

<style>
    .verification-success-icon {
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
