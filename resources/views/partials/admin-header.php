<?php
/**
 * Admin Header Partial
 *
 * Uses server-side rendering to populate the notification dropdown.
 * This eliminates the JS fetch dependency that was causing "Bildirim yok"
 * on the admin panel dropdown.
 */

$notifService    = new \App\Services\NotificationService();
$adminUserId     = (int) ($auth_user['id'] ?? 0);
$unreadCount     = $notifService->getUnreadCount($adminUserId);
$recentNotifs    = $notifService->getForUser($adminUserId, 10);
?>
<header class="app-header">
    <div class="header-left">
        <button class="sidebar-toggle d-lg-none" id="sidebarOpen">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title"><?= \Core\View::escape($pageTitle ?? 'Dashboard') ?></h1>
    </div>
    <div class="header-right">
        <div class="header-notification dropdown">
            <button class="btn btn-icon dropdown-toggle" data-bs-toggle="dropdown" id="notifDropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="notification-dot" id="headerNotifDot"></span>
                <?php else: ?>
                    <span class="notification-dot" id="headerNotifDot" style="display:none;"></span>
                <?php endif; ?>
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Bildirimler</span>
                    <a href="#" id="markAllRead" class="small">Tümünü Okundu İşaretle</a>
                </div>
                <div id="notificationList" class="notification-list">
                    <?php if (empty($recentNotifs)): ?>
                        <div class="text-center p-3 text-muted small">Bildirim yok</div>
                    <?php else: ?>
                        <?php foreach ($recentNotifs as $n): ?>
                            <?php
                                $isUnread = ((int) ($n['is_read'] ?? 1)) === 0;
                                $link     = \Core\View::escape($n['link'] ?? '#');
                                $title    = \Core\View::escape($n['title'] ?? '');
                                $content  = \Core\View::escape($n['content'] ?? '');
                                $type     = $n['type'] ?? 'info';
                                $iconMap  = [
                                    'request' => 'fa-file-alt', 'message' => 'fa-comment',
                                    'credit'  => 'fa-coins',    'payment' => 'fa-credit-card',
                                ];
                                $icon = $iconMap[$type] ?? 'fa-bell';
                            ?>
                            <a href="<?= $link ?>"
                               class="notification-item d-flex gap-2 align-items-start px-3 py-2<?= $isUnread ? ' unread' : '' ?>"
                               data-notif-id="<?= (int) $n['id'] ?>"
                               style="text-decoration:none;color:inherit;border-bottom:1px solid rgba(0,0,0,.06);">
                                <div class="notification-type-icon flex-shrink-0 mt-1">
                                    <i class="fas <?= $icon ?> fa-sm"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="small fw-semibold"><?= $title ?></div>
                                    <div class="small text-muted" style="line-height:1.3;white-space:normal;"><?= $content ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="dropdown-footer">
                    <a href="/admin/notifications">Tümünü Gör</a>
                </div>
            </div>
        </div>
        <div class="header-user dropdown">
            <button class="btn dropdown-toggle" data-bs-toggle="dropdown">
                <span class="d-none d-md-inline"><?= \Core\View::escape($auth_user['name'] ?? '') ?></span>
                <i class="fas fa-chevron-down ms-1"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/admin"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Çıkış</a></li>
            </ul>
        </div>
    </div>
</header>
