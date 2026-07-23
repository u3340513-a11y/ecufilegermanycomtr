<?php $user = $auth_user ?? null; ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-logo" aria-label="Ana Sayfa">
            <?php if (!empty($site_logo)): ?>
                <img src="<?= \Core\App::url('storage/uploads/logo/' . \Core\View::escape($site_logo)) ?>"
                     alt="Logo" class="sidebar-logo-img">
            <?php else: ?>
                <i class="fas fa-microchip"></i>
                <span>ECU Platform</span>
            <?php endif; ?>
        </a>
        <button class="sidebar-toggle d-lg-none" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar">
            <?php if ($user && $user['avatar']): ?>
                <img src="<?= \Core\App::url('storage/uploads/avatars/' . $user['avatar']) ?>" alt="Avatar">
            <?php else: ?>
                <div class="avatar-placeholder"><?= mb_substr($user['name'] ?? 'U', 0, 1) ?></div>
            <?php endif; ?>
        </div>
        <div class="sidebar-user-info">
            <span class="sidebar-user-name"><?= \Core\View::escape($user['name'] ?? '') ?></span>
            <span class="sidebar-user-credits"><i class="fas fa-coins me-1"></i><?= $user['credit_balance'] ?? 0 ?> Kredi</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="sidebar-section">Ana Menü</li>
            <li><a href="/dashboard" class="<?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li><a href="/dashboard/requests" class="<?= ($currentPage ?? '') === 'requests' ? 'active' : '' ?>"><i class="fas fa-file-alt"></i><span>Taleplerim</span></a></li>
            <li><a href="/dashboard/requests/create" class="<?= ($currentPage ?? '') === 'create-request' ? 'active' : '' ?>"><i class="fas fa-plus-circle"></i><span>Yeni Talep</span></a></li>
            <li><a href="/dashboard/fault-codes" class="<?= ($currentPage ?? '') === 'fault-codes' ? 'active' : '' ?>"><i class="fas fa-exclamation-triangle"></i><span>Arıza Kodları</span></a></li>
            <li><a href="/dashboard/bosch-ecu" class="<?= ($currentPage ?? '') === 'bosch-ecu' ? 'active' : '' ?>"><i class="fas fa-microchip"></i><span>Bosch ECU Sorgula</span></a></li>
            <li><a href="/dashboard/ecu-list" class="<?= ($currentPage ?? '') === 'ecu-list' ? 'active' : '' ?>"><i class="fas fa-server"></i><span>ECU Listesi</span></a></li>

            <li class="sidebar-section">Hesap</li>
            <li><a href="/dashboard/credits" class="<?= ($currentPage ?? '') === 'credits' ? 'active' : '' ?>"><i class="fas fa-coins"></i><span>Kredilerim</span></a></li>
            <li><a href="/dashboard/notifications" class="<?= ($currentPage ?? '') === 'notifications' ? 'active' : '' ?>"><i class="fas fa-bell"></i><span>Bildirimler</span><span class="notification-badge" id="sidebarNotifBadge"></span></a></li>
            <li><a href="/dashboard/profile" class="<?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>"><i class="fas fa-user-cog"></i><span>Profil</span></a></li>

            <li class="sidebar-section"></li>
            <li>
                <a href="https://wa.me/905549608102" target="_blank" rel="noopener noreferrer" class="sidebar-whatsapp">
                    <svg class="whatsapp-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="17" height="17" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L.057 23.885a.5.5 0 0 0 .609.61l6.178-1.483A11.954 11.954 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.836 9.836 0 0 1-5.031-1.381l-.361-.214-3.667.88.908-3.563-.234-.372A9.817 9.817 0 0 1 2.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                    </svg>
                    <span>WhatsApp Destek</span>
                </a>
            </li>
            <li><a href="/logout" class="text-danger"><i class="fas fa-sign-out-alt"></i><span>Çıkış Yap</span></a></li>
        </ul>
    </nav>
</aside>

<style>
.sidebar-whatsapp {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .6rem 1rem;
    margin: .25rem .75rem;
    border-radius: .6rem;
    background: linear-gradient(135deg, #25d366, #128c7e);
    color: #fff !important;
    text-decoration: none;
    font-weight: 600;
    font-size: .875rem;
    transition: transform .15s, box-shadow .2s, opacity .15s;
    box-shadow: 0 2px 10px rgba(37,211,102,.35);
    animation: waPulse 2.5s ease-in-out infinite;
}
.sidebar-whatsapp:hover {
    opacity: .92;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37,211,102,.45);
    animation: none;
}
.whatsapp-icon { flex-shrink: 0; }

@keyframes waPulse {
    0%, 100% { box-shadow: 0 2px 10px rgba(37,211,102,.35); }
    50%       { box-shadow: 0 2px 18px rgba(37,211,102,.65); }
}
</style>
