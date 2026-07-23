<?php $pageTitle = 'Bildirimler'; $currentPage = 'admin-notifications'; ?>
<div class="card"><div class="card-body p-0"><div class="list-group list-group-flush">
<?php if (empty($notifications)): ?><div class="text-center py-4 text-muted">Bildirim yok</div><?php endif; ?>
<?php foreach ($notifications as $n): ?><div class="list-group-item <?= !$n['is_read'] ? 'list-group-item-light' : '' ?>"><div class="d-flex justify-content-between"><strong><?= \Core\View::escape($n['title']) ?></strong><small class="text-muted"><?= date('d.m.Y H:i', strtotime($n['created_at'])) ?></small></div><p class="mb-0 text-muted small"><?= \Core\View::escape($n['content']) ?></p></div><?php endforeach; ?>
</div></div></div>
