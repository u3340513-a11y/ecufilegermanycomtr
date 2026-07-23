<?php $pageTitle = 'Sistem Ayarları'; $currentPage = 'admin-settings'; $groupLabels = ['general'=>'Genel Ayarlar','credits'=>'Kredi Ayarları']; ?>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="fas fa-image text-primary"></i>
        <h6 class="mb-0">Logo Yönetimi</h6>
    </div>
    <div class="card-body">
        <div class="row align-items-center g-4">
            <div class="col-md-3 text-center">
                <?php if (!empty($site_logo)): ?>
                    <img src="<?= \Core\App::url('storage/uploads/logo/' . \Core\View::escape($site_logo)) ?>"
                         alt="Mevcut Logo" class="img-fluid mb-2"
                         style="max-height:80px; max-width:200px; object-fit:contain;">
                    <div class="text-muted small">Mevcut logo</div>
                <?php else: ?>
                    <div class="border rounded p-4 text-muted">
                        <i class="fas fa-image fa-2x mb-2 d-block"></i>
                        <small>Logo yüklenmemiş</small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-5">
                <form method="POST" action="/admin/settings/upload-logo" enctype="multipart/form-data"><?= \Core\View::csrf() ?>
                    <label class="form-label fw-semibold">Yeni Logo Yükle</label>
                    <div class="input-group">
                        <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/svg+xml,image/webp" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-upload me-1"></i>Yükle
                        </button>
                    </div>
                    <small class="text-muted">PNG, JPG, SVG veya WEBP — Önerilen boyut: 200×60px</small>
                </form>
            </div>
            <?php if (!empty($site_logo)): ?>
            <div class="col-md-4">
                <form method="POST" action="/admin/settings/delete-logo"><?= \Core\View::csrf() ?>
                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Logo kaldırılsın mı?')">
                        <i class="fas fa-trash me-1"></i>Logoyu Kaldır
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<form method="POST" action="/admin/settings"><?= \Core\View::csrf() ?>
<?php foreach ($grouped as $group => $settings): ?>
<div class="card mb-4"><div class="card-header"><h6 class="mb-0"><?= $groupLabels[$group] ?? ucfirst($group) ?></h6></div><div class="card-body"><div class="row g-3">
<?php foreach ($settings as $s): ?>
<?php if ($s['key_name'] === 'site_logo') continue; ?>
<div class="col-md-6"><label class="form-label"><?= \Core\View::escape($s['key_name']) ?></label><input type="text" name="settings[<?= \Core\View::escape($s['key_name']) ?>]" class="form-control" value="<?= \Core\View::escape($s['value'] ?? '') ?>"></div><?php endforeach; ?>
</div></div></div>
<?php endforeach; ?>
<button class="btn btn-primary"><i class="fas fa-save me-1"></i>Kaydet</button></form>
