<?php $pageTitle = 'Kullanıcılar'; $currentPage = 'admin-users'; ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span class="fw-600">Tüm Kullanıcılar <span class="badge bg-secondary ms-1"><?= (int) $total ?></span></span>
        <?php if (!empty($pendingCount) && $pendingCount > 0): ?>
            <a href="/admin/users/pending-verification" class="btn btn-sm btn-warning">
                <i class="fas fa-envelope-open me-1"></i>
                Onay Bekleyenler
                <span class="badge bg-dark ms-1"><?= (int) $pendingCount ?></span>
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead><tr><th>ID</th><th>Ad Soyad</th><th>E-posta</th><th>Firma</th><th>Kredi</th><th>E-posta Onayı</th><th>Durum</th><th>Kayıt</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= (int) $u['id'] ?></td>
                            <td class="fw-600"><?= \Core\View::escape($u['name']) ?></td>
                            <td><?= \Core\View::escape($u['email']) ?></td>
                            <td><?= \Core\View::escape($u['company'] ?? '-') ?></td>
                            <td><span class="badge bg-info"><?= (int) $u['credit_balance'] ?> Kr</span></td>
                            <td>
                                <?php if ($u['email_verified']): ?>
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Onaylı</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Bekliyor</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-<?= $u['is_active'] ? 'success' : 'danger' ?>"><?= $u['is_active'] ? 'Aktif' : 'Pasif' ?></span></td>
                            <td class="text-muted"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                            <td><a href="/admin/users/<?= (int) $u['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($totalPages > 1): ?><div class="p-3"><?= \App\Helpers\Pagination::render($page, $totalPages, '/admin/users') ?></div><?php endif; ?>
    </div>
</div>
