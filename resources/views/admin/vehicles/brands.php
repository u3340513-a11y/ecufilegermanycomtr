<?php $pageTitle = 'Markalar'; $currentPage = 'admin-brands'; ?>
<div class="row g-4"><div class="col-lg-4">
    <div class="card"><div class="card-header"><h6 class="mb-0">Yeni Marka</h6></div><div class="card-body">
        <form method="POST" action="/admin/vehicles/brands/store"><?= \Core\View::csrf() ?>
            <div class="mb-3"><label class="form-label">Marka Adı</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Sıralama</label><input type="number" name="sort_order" class="form-control" value="0"></div>
            <button class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i>Ekle</button>
        </form>
    </div></div>
</div><div class="col-lg-8">
    <div class="card"><div class="card-body p-0"><table class="table table-hover mb-0">
        <thead><tr><th>ID</th><th>Marka</th><th>Slug</th><th>Durum</th><th>Sıra</th><th></th></tr></thead>
        <tbody><?php foreach ($brands as $b): ?><tr>
            <td><?= $b['id'] ?></td><td class="fw-600"><?= \Core\View::escape($b['name']) ?></td><td class="text-muted"><?= $b['slug'] ?></td>
            <td><span class="badge bg-<?= $b['is_active'] ? 'success' : 'danger' ?>"><?= $b['is_active'] ? 'Aktif' : 'Pasif' ?></span></td>
            <td><?= $b['sort_order'] ?></td>
            <td><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBrand<?= $b['id'] ?>"><i class="fas fa-edit"></i></button>
                <form method="POST" action="/admin/vehicles/brands/<?= $b['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')"><?= \Core\View::csrf() ?><button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
            </td>
        </tr>
        <div class="modal fade" id="editBrand<?= $b['id'] ?>"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="/admin/vehicles/brands/<?= $b['id'] ?>/update"><?= \Core\View::csrf() ?><div class="modal-header"><h5 class="modal-title">Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label">Ad</label><input type="text" name="name" class="form-control" value="<?= \Core\View::escape($b['name']) ?>" required></div><div class="mb-3"><label class="form-label">Sıra</label><input type="number" name="sort_order" class="form-control" value="<?= $b['sort_order'] ?>"></div><div class="mb-3"><label class="form-label">Durum</label><select name="is_active" class="form-select"><option value="1" <?= $b['is_active'] ? 'selected' : '' ?>>Aktif</option><option value="0" <?= !$b['is_active'] ? 'selected' : '' ?>>Pasif</option></select></div></div><div class="modal-footer"><button class="btn btn-primary">Kaydet</button></div></form></div></div></div>
        <?php endforeach; ?></tbody>
    </table></div></div>
</div></div>
