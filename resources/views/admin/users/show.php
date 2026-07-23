<?php $pageTitle = \Core\View::escape($user['name']); $currentPage = 'admin-users'; ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card"><div class="card-body text-center">
            <div class="avatar-placeholder avatar-placeholder--lg mb-3"><?= mb_substr($user['name'], 0, 1) ?></div>
            <h5><?= \Core\View::escape($user['name']) ?></h5>
            <p class="text-muted"><?= \Core\View::escape($user['email']) ?></p>
            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?> mb-2"><?= $user['role'] ?></span>
            <div class="d-flex justify-content-between mt-3"><span>Kredi</span><strong><?= $user['credit_balance'] ?> Kr</strong></div>
            <div class="d-flex justify-content-between"><span>Durum</span><span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>"><?= $user['is_active'] ? 'Aktif' : 'Pasif' ?></span></div>
            <hr>
            <div class="d-flex gap-2">
                <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-sm btn-outline-primary flex-fill"><i class="fas fa-edit me-1"></i>Düzenle</a>
                <form method="POST" action="/admin/users/<?= $user['id'] ?>/toggle-status" class="flex-fill"><?= \Core\View::csrf() ?><button class="btn btn-sm btn-outline-<?= $user['is_active'] ? 'danger' : 'success' ?> w-100"><?= $user['is_active'] ? 'Devre Dışı' : 'Aktifleştir' ?></button></form>
            </div>
            <?php if ($user['role'] !== 'admin'): ?>
            <hr>
            <div class="border border-danger rounded p-3 mt-1">
                <p class="text-danger small fw-semibold mb-2"><i class="fas fa-exclamation-triangle me-1"></i>Tehlike Bölgesi</p>
                <p class="text-muted small mb-3">Bu kullanıcı kalıcı olarak silinir. Bu işlem geri alınamaz.</p>
                <form method="POST" action="/admin/users/<?= $user['id'] ?>/delete" id="deleteUserForm"><?= \Core\View::csrf() ?>
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="confirmDelete()">
                        <i class="fas fa-trash me-1"></i>Kullanıcıyı Sil
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div></div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-4"><div class="card-header"><h6 class="mb-0">Son Talepler</h6></div><div class="card-body p-0"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>No</th><th>Durum</th><th>Kredi</th><th>Tarih</th></tr></thead><tbody>
            <?php foreach ($requests as $r): ?><tr><td>#<?= \Core\View::escape($r['ticket_no']) ?></td><td><span class="badge bg-<?= ['pending'=>'warning','reviewing'=>'info','processing'=>'primary','revision'=>'secondary','completed'=>'success','cancelled'=>'danger'][$r['status']] ?? 'secondary' ?>"><?= ['pending'=>'Bekliyor','reviewing'=>'İnceleniyor','processing'=>'İşlemde','revision'=>'Revizyon','completed'=>'Tamamlandı','cancelled'=>'İptal'][$r['status']] ?? $r['status'] ?></span></td><td><?= $r['total_credits'] ?></td><td class="text-muted"><?= date('d.m.Y', strtotime($r['created_at'])) ?></td></tr><?php endforeach; ?>
        </tbody></table></div></div></div>
    </div>
</div>

<?php $extraJs = <<<'JS'
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Emin misiniz?',
        html: 'Bu kullanıcı ve tüm işlem geçmişi <b>kalıcı olarak silinecek</b>.<br>Bu işlem geri alınamaz.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i>Evet, Sil',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteUserForm').submit();
        }
    });
}
</script>
JS;
?>
