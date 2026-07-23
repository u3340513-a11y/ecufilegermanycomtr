<?php $pageTitle = 'Fiyat Listesi'; $currentPage = 'admin-pricing'; ?>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-700">Genel Servis Paketleri</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Hizmet</th><th>Varsayılan Kredi</th><th>Açıklama</th><th>Sıra</th><th>Durum</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($packages as $p): ?>
                <tr>
                    <td class="fw-600"><?= \Core\View::escape($p['name']) ?></td>
                    <td><span class="badge bg-info"><?= $p['credit_cost'] ?> Kr</span></td>
                    <td class="text-muted small"><?= \Core\View::escape($p['description'] ?? '') ?></td>
                    <td><?= $p['sort_order'] ?></td>
                    <td><span class="badge bg-<?= $p['is_active'] ? 'success' : 'danger' ?>"><?= $p['is_active'] ? 'Aktif' : 'Pasif' ?></span></td>
                    <td><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPkg<?= $p['id'] ?>"><i class="fas fa-edit"></i></button></td>
                </tr>
                <div class="modal fade" id="editPkg<?= $p['id'] ?>"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="/admin/pricing/<?= $p['id'] ?>/update"><?= \Core\View::csrf() ?><div class="modal-header"><h5 class="modal-title"><?= \Core\View::escape($p['name']) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label">Ad</label><input type="text" name="name" class="form-control" value="<?= \Core\View::escape($p['name']) ?>"></div><div class="mb-3"><label class="form-label">Varsayılan Kredi</label><input type="number" name="credit_cost" class="form-control" value="<?= $p['credit_cost'] ?>"></div><div class="mb-3"><label class="form-label">Açıklama</label><input type="text" name="description" class="form-control" value="<?= \Core\View::escape($p['description'] ?? '') ?>"></div><div class="mb-3"><label class="form-label">Sıra</label><input type="number" name="sort_order" class="form-control" value="<?= $p['sort_order'] ?>"></div><div class="mb-3"><label class="form-label">Durum</label><select name="is_active" class="form-select"><option value="1" <?= $p['is_active'] ? 'selected' : '' ?>>Aktif</option><option value="0" <?= !$p['is_active'] ? 'selected' : '' ?>>Pasif</option></select></div></div><div class="modal-footer"><button class="btn btn-primary">Kaydet</button></div></form></div></div></div>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-700">Stage Bazlı Servis Fiyatları</h6>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills mb-4 flex-wrap gap-2" id="stageTabs" role="tablist">
            <?php foreach ($stages as $i => $stage): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" id="stageTab<?= $stage['id'] ?>" data-bs-toggle="pill" data-bs-target="#stagePane<?= $stage['id'] ?>" type="button" role="tab">
                        <?= \Core\View::escape($stage['name']) ?>
                        <span class="badge bg-secondary ms-1"><?= $stage['base_credit'] ?> Kr</span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content" id="stageTabContent">
            <?php foreach ($stages as $i => $stage): ?>
                <?php
                    $stageServices = $stagePricing[$stage['id']] ?? [];
                    $assignedIds = array_map(fn($s) => (int)$s['service_package_id'], $stageServices);
                    $unassigned = array_filter($packages, fn($p) => $p['is_active'] && !in_array((int)$p['id'], $assignedIds));
                ?>
                <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="stagePane<?= $stage['id'] ?>" role="tabpanel">
                    <?php if (!empty($stageServices)): ?>
                        <form method="POST" action="/admin/pricing/stage/<?= $stage['id'] ?>/update">
                            <?= \Core\View::csrf() ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-3">
                                    <thead>
                                        <tr>
                                            <th style="min-width:220px">Servis Adı</th>
                                            <th style="width:120px">Kredi</th>
                                            <th style="width:100px">Görünür</th>
                                            <th style="width:80px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($stageServices as $svc): ?>
                                        <tr>
                                            <td class="fw-600 align-middle"><?= \Core\View::escape($svc['service_name']) ?></td>
                                            <td>
                                                <input type="number" name="services[<?= $svc['service_package_id'] ?>][credit_cost]" class="form-control form-control-sm" value="<?= $svc['credit_cost'] ?>" min="0" style="width:90px">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="services[<?= $svc['service_package_id'] ?>][is_visible]" class="form-check-input" value="1" <?= $svc['is_visible'] ? 'checked' : '' ?>>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removeModal<?= $stage['id'] ?>_<?= $svc['service_package_id'] ?>" title="Kaldır"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>Kaydet</button>
                        </form>

                        <?php foreach ($stageServices as $svc): ?>
                            <div class="modal fade" id="removeModal<?= $stage['id'] ?>_<?= $svc['service_package_id'] ?>">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form method="POST" action="/admin/pricing/stage/<?= $stage['id'] ?>/remove-service">
                                            <?= \Core\View::csrf() ?>
                                            <input type="hidden" name="service_package_id" value="<?= $svc['service_package_id'] ?>">
                                            <div class="modal-header"><h6 class="modal-title">Servisi Kaldır</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                            <div class="modal-body">
                                                <p class="mb-0"><strong><?= \Core\View::escape($svc['service_name']) ?></strong> servisi bu stage'den kaldırılsın mı?</p>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button><button class="btn btn-danger btn-sm">Kaldır</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-3">Bu stage'de henüz servis tanımlı değil.</p>
                    <?php endif; ?>

                    <?php if (!empty($unassigned)): ?>
                        <hr>
                        <form method="POST" action="/admin/pricing/stage/<?= $stage['id'] ?>/add-service" class="d-flex align-items-end gap-2 flex-wrap">
                            <?= \Core\View::csrf() ?>
                            <div>
                                <label class="form-label form-label-sm mb-1">Servis Ekle</label>
                                <select name="service_package_id" class="form-select form-select-sm" required style="min-width:220px">
                                    <option value="">Servis seçin...</option>
                                    <?php foreach ($unassigned as $up): ?>
                                        <option value="<?= $up['id'] ?>"><?= \Core\View::escape($up['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label form-label-sm mb-1">Kredi</label>
                                <input type="number" name="credit_cost" class="form-control form-control-sm" value="0" min="0" style="width:90px">
                            </div>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i>Ekle</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
