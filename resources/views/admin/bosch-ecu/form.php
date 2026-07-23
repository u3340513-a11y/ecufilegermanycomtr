<?php
$pageTitle = $ecu ? 'Bosch ECU Düzenle' : 'Yeni Bosch ECU';
$currentPage = 'admin-bosch-ecu';
$action = $ecu ? "/admin/bosch-ecu/{$ecu['id']}/update" : '/admin/bosch-ecu/store';
?>
<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= $action ?>">
            <?= \Core\View::csrf() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ECU Numarası</label>
                    <input type="text" name="ecu_number" class="form-control font-monospace"
                           value="<?= \Core\View::escape($ecu['ecu_number'] ?? '') ?>" required
                           placeholder="örn: 0281014238">
                </div>
                <div class="col-md-6">
                    <label class="form-label">ECU Tipi</label>
                    <input type="text" name="ecu_type" class="form-control"
                           value="<?= \Core\View::escape($ecu['ecu_type'] ?? '') ?>" required
                           placeholder="örn: EDC17CP02">
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary"><i class="fas fa-save me-1"></i>Kaydet</button>
                <a href="/admin/bosch-ecu" class="btn btn-outline-secondary ms-2">İptal</a>
            </div>
        </form>
    </div>
</div>
