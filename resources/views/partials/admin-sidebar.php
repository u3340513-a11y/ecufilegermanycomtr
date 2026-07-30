<?php
    $adminPanelTitle = 'ECU Admin';
    try {
        $row = \Core\Database::getInstance()->fetch("SELECT value FROM settings WHERE key_name = 'admin_panel_title'");
        if (!empty($row['value'])) { $adminPanelTitle = $row['value']; }
    } catch (\Throwable) {}
?>
<aside class="sidebar admin-sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-microchip"></i>
            <span><?= \Core\View::escape($adminPanelTitle) ?></span>
        </div>
        <button class="sidebar-toggle d-lg-none" id="sidebarClose"><i class="fas fa-times"></i></button>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="sidebar-section">Genel</li>
            <li><a href="/admin" class="<?= ($currentPage ?? '') === 'admin-dashboard' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>

            <li class="sidebar-section">İşlemler</li>
            <li><a href="/admin/requests" class="<?= ($currentPage ?? '') === 'admin-requests' ? 'active' : '' ?>"><i class="fas fa-file-alt"></i><span>Talepler</span></a></li>
            <li><a href="/admin/users" class="<?= ($currentPage ?? '') === 'admin-users' ? 'active' : '' ?>"><i class="fas fa-users"></i><span>Kullanıcılar</span></a></li>
            <li><a href="/admin/credits" class="<?= ($currentPage ?? '') === 'admin-credits' ? 'active' : '' ?>"><i class="fas fa-coins"></i><span>Kredi Yönetimi</span></a></li>
            <li><a href="/admin/stripe" class="<?= ($currentPage ?? '') === 'admin-stripe' ? 'active' : '' ?>"><i class="fab fa-stripe-s"></i><span>Stripe</span></a></li>

            <li class="sidebar-section">Araç Veritabanı</li>
            <li><a href="/admin/vehicles/brands" class="<?= ($currentPage ?? '') === 'admin-brands' ? 'active' : '' ?>"><i class="fas fa-car"></i><span>Markalar</span></a></li>
            <li><a href="/admin/vehicles/models" class="<?= ($currentPage ?? '') === 'admin-models' ? 'active' : '' ?>"><i class="fas fa-car-side"></i><span>Modeller</span></a></li>
            <li><a href="/admin/vehicles/generations" class="<?= ($currentPage ?? '') === 'admin-generations' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i><span>Jenerasyonlar</span></a></li>
            <li><a href="/admin/vehicles/engines" class="<?= ($currentPage ?? '') === 'admin-engines' ? 'active' : '' ?>"><i class="fas fa-cog"></i><span>Motorlar</span></a></li>
            <li><a href="/admin/vehicles/ecus" class="<?= ($currentPage ?? '') === 'admin-ecus' ? 'active' : '' ?>"><i class="fas fa-microchip"></i><span>ECU'lar</span></a></li>
            <li><a href="/admin/vehicles/reading-methods" class="<?= ($currentPage ?? '') === 'admin-reading-methods' ? 'active' : '' ?>"><i class="fas fa-plug"></i><span>Okuma Yöntemleri</span></a></li>

            <li class="sidebar-section">İçerik</li>
            <li><a href="/admin/fault-codes" class="<?= ($currentPage ?? '') === 'admin-fault-codes' ? 'active' : '' ?>"><i class="fas fa-exclamation-triangle"></i><span>Arıza Kodları</span></a></li>
            <li><a href="/admin/bosch-ecu" class="<?= ($currentPage ?? '') === 'admin-bosch-ecu' ? 'active' : '' ?>"><i class="fas fa-search"></i><span>Bosch ECU</span></a></li>
            <li><a href="/admin/pricing" class="<?= ($currentPage ?? '') === 'admin-pricing' ? 'active' : '' ?>"><i class="fas fa-tags"></i><span>Fiyat Listesi</span></a></li>

            <li class="sidebar-section">Sistem</li>
            <li><a href="/admin/landing" class="<?= ($currentPage ?? '') === 'admin-landing' ? 'active' : '' ?>"><i class="fas fa-edit"></i><span>Landing Editor</span></a></li>
            <li><a href="/admin/notifications" class="<?= ($currentPage ?? '') === 'admin-notifications' ? 'active' : '' ?>"><i class="fas fa-bell"></i><span>Bildirimler</span></a></li>
            <li><a href="/admin/settings" class="<?= ($currentPage ?? '') === 'admin-settings' ? 'active' : '' ?>"><i class="fas fa-cog"></i><span>Ayarlar</span></a></li>
            <li><a href="/admin/logs" class="<?= ($currentPage ?? '') === 'admin-logs' ? 'active' : '' ?>"><i class="fas fa-clipboard-list"></i><span>Loglar</span></a></li>

            <li class="sidebar-section"></li>
            <li><a href="/dashboard"><i class="fas fa-arrow-left"></i><span>Kullanıcı Paneli</span></a></li>
            <li><a href="/logout" class="text-danger"><i class="fas fa-sign-out-alt"></i><span>Çıkış</span></a></li>
        </ul>
    </nav>
</aside>
