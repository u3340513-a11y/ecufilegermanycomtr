<?php $pageTitle = 'E-posta Onay Bekleyenler'; $currentPage = 'admin-users'; ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-0">E-posta Onay Bekleyenler</h6>
            <p class="text-muted small mb-0 mt-1">
                Bu kullanıcılar kayıt oldu ancak onay maili spam klasörüne düştüğü için e-postalarını doğrulayamadı.
                Aşağıdaki listeden manuel olarak onaylayabilirsiniz.
            </p>
        </div>
        <a href="/admin/users" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Tüm Kullanıcılar
        </a>
    </div>

    <div class="card-body p-0">
        <?php if (empty($users)): ?>
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <p class="text-muted mb-0">Onay bekleyen kullanıcı yok.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="pendingUsersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>E-posta</th>
                            <th>Firma</th>
                            <th>Telefon</th>
                            <th>Kayıt Tarihi</th>
                            <th class="text-end">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr id="user-row-<?= (int) $u['id'] ?>">
                                <td class="text-muted"><?= (int) $u['id'] ?></td>
                                <td class="fw-600"><?= \Core\View::escape($u['name']) ?></td>
                                <td><?= \Core\View::escape($u['email']) ?></td>
                                <td><?= \Core\View::escape($u['company'] ?? '-') ?></td>
                                <td><?= \Core\View::escape($u['phone'] ?? '-') ?></td>
                                <td class="text-muted"><?= date('d.m.Y H:i', strtotime($u['created_at'])) ?></td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="/admin/users/<?= (int) $u['id'] ?>"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Profili Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST"
                                              action="/admin/users/<?= (int) $u['id'] ?>/approve-verification"
                                              class="approve-form"
                                              data-name="<?= \Core\View::escape($u['name']) ?>">
                                            <?= \Core\View::csrf() ?>
                                            <button type="button"
                                                    class="btn btn-sm btn-success approve-btn"
                                                    title="Manuel Onayla">
                                                <i class="fas fa-check me-1"></i>Onayla
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <?= \App\Helpers\Pagination::render($page, $totalPages, '/admin/users/pending-verification') ?>
        </div>
    <?php endif; ?>
</div>

<?php $extraJs = <<<'JS'
<script>
document.querySelectorAll('.approve-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var form = this.closest('.approve-form');
        var name = form.dataset.name;

        Swal.fire({
            title: 'Kullanıcıyı onayla?',
            html: '<strong>' + name + '</strong> adlı kullanıcının e-posta doğrulaması onaylanacak ve giriş yapabilecek.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check me-1"></i>Evet, Onayla',
            cancelButtonText: 'İptal'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
JS;
?>
