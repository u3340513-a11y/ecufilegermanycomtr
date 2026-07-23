<?php $pageTitle = $code ? 'Arıza Kodu Düzenle' : 'Yeni Arıza Kodu'; $currentPage = 'admin-fault-codes'; $action = $code ? "/admin/fault-codes/{$code['id']}/update" : '/admin/fault-codes/store'; ?>
<div class="card"><div class="card-body"><form method="POST" action="<?= $action ?>"><?= \Core\View::csrf() ?>
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Arıza Kodu</label><input type="text" name="code" class="form-control" value="<?= \Core\View::escape($code['code'] ?? '') ?>" required placeholder="P0420"></div>
        <div class="col-md-8"><label class="form-label">Başlık</label><input type="text" name="title" class="form-control" value="<?= \Core\View::escape($code['title'] ?? '') ?>" required></div>
        <div class="col-12"><label class="form-label">Açıklama</label><textarea name="description" class="form-control" rows="4"><?= \Core\View::escape($code['description'] ?? '') ?></textarea></div>
        <div class="col-12"><label class="form-label">Çözüm</label><textarea name="solution" class="form-control" rows="4"><?= \Core\View::escape($code['solution'] ?? '') ?></textarea></div>
        <div class="col-md-6"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="<?= \Core\View::escape($code['meta_title'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Meta Description</label><input type="text" name="meta_description" class="form-control" value="<?= \Core\View::escape($code['meta_description'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Yayın Durumu</label><select name="is_published" class="form-select"><option value="1" <?= ($code['is_published'] ?? 0) ? 'selected' : '' ?>>Yayında</option><option value="0" <?= !($code['is_published'] ?? 0) ? 'selected' : '' ?>>Taslak</option></select></div>
    </div>
    <div class="mt-3"><button class="btn btn-primary"><i class="fas fa-save me-1"></i>Kaydet</button> <a href="/admin/fault-codes" class="btn btn-outline-secondary">İptal</a></div>
</form></div></div>
