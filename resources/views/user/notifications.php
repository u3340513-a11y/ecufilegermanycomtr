<?php $pageTitle = 'Bildirimler'; $currentPage = 'notifications'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Bildirimler</h5>
        <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn">
            <i class="fas fa-check-double me-1"></i>Tümünü Okundu İşaretle
        </button>
    </div>
    <div class="card-body p-0">
        <?php if (empty($notifications)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3 opacity-25"></i>
                <p>Henüz bildirim yok</p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notif): ?>
                    <div class="list-group-item notification-item <?= !$notif['is_read'] ? 'unread' : '' ?>"
                         data-id="<?= $notif['id'] ?>">
                        <div class="d-flex align-items-start">
                            <div class="notification-type-icon me-3">
                                <?php
                                    $iconMap = ['request'=>'fa-file-alt text-primary','message'=>'fa-comment text-info','credit'=>'fa-coins text-success','payment'=>'fa-credit-card text-warning','info'=>'fa-info-circle text-secondary'];
                                    $icon = $iconMap[$notif['type']] ?? 'fa-bell text-primary';
                                ?>
                                <i class="fas <?= $icon ?> fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?= \Core\View::escape($notif['title']) ?></h6>
                                    <small class="text-muted"><?= date('d.m.Y H:i', strtotime($notif['created_at'])) ?></small>
                                </div>
                                <p class="mb-0 text-muted"><?= \Core\View::escape($notif['content']) ?></p>
                                <?php if ($notif['link']): ?>
                                    <a href="<?= $notif['link'] ?>" class="small">Görüntüle →</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
