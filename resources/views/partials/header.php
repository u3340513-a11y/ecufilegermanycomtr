<header class="app-header">
    <div class="header-left">
        <button class="sidebar-toggle d-lg-none" id="sidebarOpen">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title"><?= \Core\View::escape($pageTitle ?? 'Dashboard') ?></h1>
    </div>
    <div class="header-right">
        <div class="header-notification dropdown">
            <button class="btn btn-icon dropdown-toggle" data-bs-toggle="dropdown" id="notifDropdown">
                <i class="fas fa-bell"></i>
                <span class="notification-dot" id="headerNotifDot" style="display:none;"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Bildirimler</span>
                    <a href="#" id="markAllRead" class="small">Tümünü Okundu İşaretle</a>
                </div>
                <div id="notificationList" class="notification-list">
                    <div class="text-center p-3 text-muted">Bildirim yok</div>
                </div>
                <div class="dropdown-footer">
                    <a href="/dashboard/notifications">Tümünü Gör</a>
                </div>
            </div>
        </div>
        <div class="header-user dropdown">
            <button class="btn dropdown-toggle" data-bs-toggle="dropdown">
                <span class="d-none d-md-inline"><?= \Core\View::escape($auth_user['name'] ?? '') ?></span>
                <i class="fas fa-chevron-down ms-1"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/dashboard/profile"><i class="fas fa-user me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="/dashboard/credits"><i class="fas fa-coins me-2"></i>Kredilerim</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Çıkış</a></li>
            </ul>
        </div>
    </div>
</header>
