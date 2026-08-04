<?php $pageTitle = 'Talep #' . \Core\View::escape($req['ticket_no']); $currentPage = 'admin-requests'; $sc = ['pending'=>'warning','reviewing'=>'info','processing'=>'primary','revision'=>'secondary','completed'=>'success','cancelled'=>'danger']; $sl = ['pending'=>'Bekliyor','reviewing'=>'İnceleniyor','processing'=>'İşlemde','revision'=>'Revizyon','completed'=>'Tamamlandı','cancelled'=>'İptal']; ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4"><div class="card-header d-flex justify-content-between"><h6 class="mb-0">Talep Detayı — <?= \Core\View::escape($req['user_name']) ?></h6><span class="badge bg-<?= $sc[$req['status']] ?? 'secondary' ?>"><?= $sl[$req['status']] ?? $req['status'] ?></span></div>
        <div class="card-body"><div class="row g-2">
            <div class="col-4"><small class="text-muted">Marka</small><br><?= \Core\View::escape($req['brand_name'] ?? '-') ?></div>
            <div class="col-4"><small class="text-muted">Model</small><br><?= \Core\View::escape($req['model_name'] ?? '-') ?></div>
            <div class="col-4"><small class="text-muted">Motor</small><br><?= \Core\View::escape($req['engine_name'] ?? '-') ?></div>
            <div class="col-4"><small class="text-muted">ECU</small><br><?= \Core\View::escape($req['ecu_name'] ?? '-') ?></div>
            <div class="col-4"><small class="text-muted">ECU SW/HW</small><br><?= \Core\View::escape(($req['ecu_sw'] ?? '') . ' / ' . ($req['ecu_hw'] ?? '')) ?></div>
            <div class="col-4"><small class="text-muted">Okuma</small><br><?= \Core\View::escape($req['reading_method_name'] ?? '-') ?></div>
            <div class="col-4"><small class="text-muted">Yıl</small><br><?= $req['year'] ?? '-' ?></div>
            <div class="col-4"><small class="text-muted">Plaka</small><br><?= \Core\View::escape($req['plate_number'] ?? '-') ?></div>
            <div class="col-4"><small class="text-muted">Kredi</small><br><strong><?= $req['total_credits'] ?></strong></div>
        </div>
        <?php if ($req['customer_note']): ?><hr><small class="text-muted">Not:</small><br><?= nl2br(\Core\View::escape($req['customer_note'])) ?><?php endif; ?>
        </div></div>

        <div class="card mb-4"><div class="card-header"><h6 class="mb-0">Mesajlar</h6></div><div class="card-body">
            <?php foreach ($messages as $m): ?><div class="message-item <?= $m['is_admin'] ? 'message-admin' : 'message-user' ?>"><div class="message-header"><strong><?= \Core\View::escape($m['sender_name']) ?> <?= $m['is_admin'] ? '<span class="badge bg-danger">Admin</span>' : '' ?></strong><small class="text-muted"><?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></small></div><div class="message-body"><?= nl2br(\Core\View::escape($m['content'])) ?></div><?php if ($m['attachment_name']): ?><div class="message-attachment"><i class="fas fa-paperclip me-1"></i><?= \Core\View::escape($m['attachment_name']) ?></div><?php endif; ?></div><?php endforeach; ?>
            <hr>
            <form method="POST" action="/admin/requests/<?= $req['id'] ?>/message" enctype="multipart/form-data"><?= \Core\View::csrf() ?>
                <textarea class="form-control mb-2" name="content" rows="3" placeholder="Mesaj yazın..." required></textarea>
                <div class="d-flex justify-content-between"><input type="file" name="attachment" class="form-control form-control-sm w-auto"><button class="btn btn-primary btn-sm"><i class="fas fa-paper-plane me-1"></i>Gönder</button></div>
            </form>
        </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4"><div class="card-header"><h6 class="mb-0">Durum Güncelle</h6></div><div class="card-body">
            <form method="POST" action="/admin/requests/<?= $req['id'] ?>/status"><?= \Core\View::csrf() ?>
                <select name="status" class="form-select mb-2">
                    <?php foreach ($sl as $k => $v): ?><option value="<?= $k ?>" <?= $req['status'] === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?>
                </select>
                <button class="btn btn-primary w-100"><i class="fas fa-sync me-1"></i>Güncelle</button>
            </form>
        </div></div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Mevcut Servisler</h6>
                <span class="badge bg-secondary"><?= count($req['services']) ?> adet</span>
            </div>
            <div class="card-body">
                <?php if (empty($req['services'])): ?>
                    <small class="text-muted">Servis eklenmemiş.</small>
                <?php else: ?>
                    <?php foreach ($req['services'] as $svc): ?>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small><?= \Core\View::escape($svc['service_name']) ?></small>
                            <span class="badge bg-warning text-dark"><?= $svc['credit_cost'] ?> Kr</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning bg-opacity-10">
                <h6 class="mb-0 text-warning"><i class="fas fa-plus-circle me-1"></i>Talebe Servis Ekle</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Müşteri seçmeyi unuttuğu bir servisi burada ekleyin. Kredi müşteri bakiyesinden otomatik tahsil edilir.</p>
                <form method="POST" action="/admin/requests/<?= $req['id'] ?>/add-service"><?= \Core\View::csrf() ?>
                    <select name="service_package_id" class="form-select mb-2" required>
                        <option value="">— Servis seçin —</option>
                        <?php
                        $existingIds = array_column($req['services'], 'service_package_id');
                        foreach ($allPackages as $pkg):
                            $alreadyAdded = in_array($pkg['id'], $existingIds);
                        ?>
                            <option value="<?= $pkg['id'] ?>" <?= $alreadyAdded ? 'disabled' : '' ?>>
                                <?= \Core\View::escape($pkg['name']) ?><?= $alreadyAdded ? ' ✓ ekli' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-warning w-100 btn-sm">
                        <i class="fas fa-plus me-1"></i>Ekle & Krediyi Tahsil Et
                    </button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-upload me-1"></i>Dosya Yükle</h6></div>
            <div class="card-body">
                <form method="POST"
                      action="/admin/requests/<?= $req['id'] ?>/upload-file"
                      enctype="multipart/form-data"
                      id="adminUploadForm">
                    <?= \Core\View::csrf() ?>
                    <div class="mb-2">
                        <label class="form-label form-label-sm text-muted mb-1">Dosya Türü</label>
                        <select name="file_type" class="form-select form-select-sm">
                            <option value="revision">Revizyon</option>
                            <option value="completed">Tamamlanan</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label form-label-sm text-muted mb-1">Dosya Seç</label>
                        <input type="file" name="file" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-upload me-1"></i>Yükle
                    </button>
                </form>
            </div>
        </div>

        <div class="card"><div class="card-header"><h6 class="mb-0">Dosyalar</h6></div><div class="card-body">
            <?php $grouped = ['original'=>[],'revision'=>[],'completed'=>[]]; foreach ($req['files'] as $f) { $grouped[$f['type']][] = $f; } $tl = ['original'=>'Orijinal','revision'=>'Revizyon','completed'=>'Tamamlanan']; ?>
            <?php foreach ($grouped as $type => $files): if (!empty($files)): ?>
                <h6 class="small text-uppercase text-muted mb-2"><?= $tl[$type] ?></h6>
                <?php foreach ($files as $f): ?><div class="d-flex justify-content-between align-items-center mb-2"><div><small><?= \Core\View::escape($f['original_name']) ?></small><br><small class="text-muted">v<?= $f['version'] ?></small></div><a href="/download/<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a></div><?php endforeach; ?>
            <?php endif; endforeach; ?>
        </div></div>
    </div>
</div>
