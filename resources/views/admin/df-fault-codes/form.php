<?php
$pageTitle   = $code ? 'DF Kodu Düzenle' : 'Yeni DF Kodu';
$currentPage = 'admin-df-fault-codes';
$action      = $code ? "/admin/df-fault-codes/{$code['id']}/update" : '/admin/df-fault-codes/store';
?>
<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= $action ?>">
            <?= \Core\View::csrf() ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">DF Kodu</label>
                    <input type="text" name="df_code" class="form-control font-monospace text-uppercase"
                           value="<?= \Core\View::escape($code['df_code'] ?? '') ?>" required
                           placeholder="örn: DF010">
                </div>
                <div class="col-md-4">
                    <label class="form-label">P Kodu (OBD-II)</label>
                    <input type="text" name="p_code" class="form-control font-monospace text-uppercase"
                           value="<?= \Core\View::escape($code['p_code'] ?? '') ?>" required
                           placeholder="örn: P0409">
                </div>
                <div class="col-12">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Açıklama (isteğe bağlı)"><?= \Core\View::escape($code['description'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary"><i class="fas fa-save me-1"></i>Kaydet</button>
                <a href="/admin/df-fault-codes" class="btn btn-outline-secondary ms-2">İptal</a>
            </div>
        </form>
    </div>
</div>
