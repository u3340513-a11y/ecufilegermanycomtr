<?php $pageTitle = 'Aktivite Logları'; $currentPage = 'admin-logs'; ?>
<div class="card"><div class="card-body p-0"><table class="table table-hover mb-0" id="logsTable"><thead><tr><th>Kullanıcı</th><th>Aksiyon</th><th>Detay</th><th>IP</th><th>Tarih</th></tr></thead><tbody>
<?php foreach ($logs as $l): ?><tr><td><?= \Core\View::escape($l['user_name'] ?? 'Sistem') ?></td><td><span class="badge bg-secondary"><?= \Core\View::escape($l['action']) ?></span></td><td class="text-muted small"><?= \Core\View::escape($l['entity_type'] ?? '') ?> #<?= $l['entity_id'] ?? '' ?></td><td class="text-muted small"><?= \Core\View::escape($l['ip_address'] ?? '') ?></td><td class="text-muted"><?= date('d.m.Y H:i', strtotime($l['created_at'])) ?></td></tr><?php endforeach; ?>
</tbody></table></div></div>
<?php if ($totalPages > 1): ?><?= \App\Helpers\Pagination::render($page, $totalPages, '/admin/logs') ?><?php endif; ?>
