<?php $pageTitle = 'Talep #' . \Core\View::escape($req['ticket_no']); $currentPage = 'requests'; ?>
<?php
$sc = ['pending'=>'warning','reviewing'=>'info','processing'=>'primary','revision'=>'secondary','completed'=>'success','cancelled'=>'danger'];
$sl = ['pending'=>'Bekliyor','reviewing'=>'İnceleniyor','processing'=>'İşlemde','revision'=>'Revizyon','completed'=>'Tamamlandı','cancelled'=>'İptal'];
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Talep Bilgileri</h6>
                <span class="badge bg-<?= $sc[$req['status']] ?? 'secondary' ?> fs-6"><?= $sl[$req['status']] ?? $req['status'] ?></span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Talep No</strong><?= \Core\View::escape($req['ticket_no']) ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Marka</strong><?= \Core\View::escape($req['brand_name'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Model</strong><?= \Core\View::escape($req['model_name'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Jenerasyon</strong><?= \Core\View::escape($req['generation_name'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Motor</strong><?= \Core\View::escape($req['engine_name'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">ECU</strong><?= \Core\View::escape($req['ecu_name'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Yıl</strong><?= $req['year'] ?? '-' ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">ECU SW</strong><?= \Core\View::escape($req['ecu_sw'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">ECU HW</strong><?= \Core\View::escape($req['ecu_hw'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Okuma Yöntemi</strong><?= \Core\View::escape($req['reading_method_name'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Plaka</strong><?= \Core\View::escape($req['plate_number'] ?? '-') ?></div>
                    <div class="col-6 col-md-4"><strong class="text-muted d-block small">Toplam Kredi</strong><?= $req['total_credits'] ?> Kr</div>
                </div>
                <?php if ($req['customer_note']): ?>
                    <hr>
                    <strong class="text-muted d-block small mb-1">Müşteri Notu</strong>
                    <p class="mb-0"><?= nl2br(\Core\View::escape($req['customer_note'])) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Seçilen Hizmetler</h6></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <?php foreach ($req['services'] as $svc): ?>
                            <tr>
                                <td><?= \Core\View::escape($svc['service_name']) ?></td>
                                <td class="text-end"><?= $svc['credit_cost'] ?> Kr</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-light">
                            <td class="fw-700">Toplam</td>
                            <td class="text-end fw-700"><?= $req['total_credits'] ?> Kr</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-comments me-2"></i>Mesajlar</h6></div>
            <div class="card-body">
                <div class="message-list" id="messageList">
                    <?php if (empty($messages)): ?>
                        <p class="text-center text-muted py-3">Henüz mesaj yok</p>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <div class="message-item <?= $msg['is_admin'] ? 'message-admin' : 'message-user' ?>">
                                <div class="message-header">
                                    <strong><?= \Core\View::escape($msg['sender_name']) ?></strong>
                                    <small class="text-muted"><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></small>
                                </div>
                                <div class="message-body"><?= nl2br(\Core\View::escape($msg['content'])) ?></div>
                                <?php if ($msg['attachment_name']): ?>
                                    <div class="message-attachment">
                                        <i class="fas fa-paperclip me-1"></i>
                                        <a href="/download/<?= $msg['id'] ?>"><?= \Core\View::escape($msg['attachment_name']) ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!in_array($req['status'], ['completed', 'cancelled'])): ?>
                    <hr>
                    <form id="messageForm" enctype="multipart/form-data">
                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                        <input type="hidden" name="_csrf_token" value="<?= \Core\Session::getCsrfToken() ?>">
                        <div class="mb-2">
                            <textarea class="form-control" name="content" rows="3" placeholder="Mesajınızı yazın..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <input type="file" name="attachment" id="msgAttachment" class="d-none">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('msgAttachment').click()">
                                    <i class="fas fa-paperclip me-1"></i>Dosya Ekle
                                </button>
                                <span id="attachmentName" class="small text-muted ms-2"></span>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane me-1"></i>Gönder
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-file-archive me-2"></i>Dosya Geçmişi</h6></div>
            <div class="card-body">
                <?php if (empty($req['files'])): ?>
                    <p class="text-center text-muted">Dosya yok</p>
                <?php else: ?>
                    <?php
                    $grouped = ['original' => [], 'revision' => [], 'completed' => []];
                    foreach ($req['files'] as $f) { $grouped[$f['type']][] = $f; }
                    $typeLabels = ['original' => 'Orijinal Dosyalar', 'revision' => 'Revizyon Dosyaları', 'completed' => 'Tamamlanan Dosyalar'];
                    $typeIcons = ['original' => 'fa-file text-primary', 'revision' => 'fa-file-alt text-warning', 'completed' => 'fa-file-check text-success'];
                    ?>
                    <?php foreach ($grouped as $type => $files): ?>
                        <?php if (!empty($files)): ?>
                            <h6 class="small text-uppercase text-muted mb-2 mt-3"><?= $typeLabels[$type] ?></h6>
                            <?php foreach ($files as $f): ?>
                                <div class="file-item d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas <?= $typeIcons[$type] ?? 'fa-file' ?> me-2"></i>
                                        <div>
                                            <span class="small d-block"><?= \Core\View::escape($f['original_name']) ?></span>
                                            <span class="small text-muted">v<?= $f['version'] ?> · <?= round($f['size'] / 1024) ?>KB</span>
                                        </div>
                                    </div>
                                    <a href="/download/<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <small class="text-muted d-block mb-1">Oluşturulma</small>
                <p class="mb-2"><?= date('d.m.Y H:i', strtotime($req['created_at'])) ?></p>
                <small class="text-muted d-block mb-1">Son Güncelleme</small>
                <p class="mb-0"><?= date('d.m.Y H:i', strtotime($req['updated_at'])) ?></p>
            </div>
        </div>
    </div>
</div>
