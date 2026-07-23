<?php $pageTitle = 'Kullanıcılar'; $currentPage = 'admin-users'; ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead><tr><th>ID</th><th>Ad Soyad</th><th>E-posta</th><th>Firma</th><th>Kredi</th><th>Durum</th><th>Kayıt</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td class="fw-600"><?= \Core\View::escape($u['name']) ?></td>
                            <td><?= \Core\View::escape($u['email']) ?></td>
                            <td><?= \Core\View::escape($u['company'] ?? '-') ?></td>
                            <td><span class="badge bg-info"><?= $u['credit_balance'] ?> Kr</span></td>
                            <td><span class="badge bg-<?= $u['is_active'] ? 'success' : 'danger' ?>"><?= $u['is_active'] ? 'Aktif' : 'Pasif' ?></span></td>
                            <td class="text-muted"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                            <td><a href="/admin/users/<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($totalPages > 1): ?><?= \App\Helpers\Pagination::render($page, $totalPages, '/admin/users') ?><?php endif; ?>
    </div>
</div>
