<?php $pageTitle = 'Yönetim Paneli'; $currentPage = 'admin-dashboard'; ?>

<?php if (!empty($debtWarning)): ?>
<div class="alert alert-danger d-flex align-items-start gap-3 mb-4 shadow-sm border-0" role="alert" style="border-left:4px solid #dc3545 !important;">
    <i class="fas fa-exclamation-triangle fa-lg mt-1 flex-shrink-0 text-danger"></i>
    <div class="flex-grow-1">
        <strong class="d-block mb-1">⚠️ Cumartesi Borç Hatırlatması</strong>
        <p class="mb-2 text-danger-emphasis">Aşağıdaki kullanıcıların borç bakiyesi bulunmaktadır. Lütfen kontrol edin:</p>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0 bg-white">
                <thead class="table-danger">
                    <tr>
                        <th>Kullanıcı</th>
                        <th>E-posta</th>
                        <th>Borç</th>
                        <th>Kredi Bakiyesi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersWithDebt as $du): ?>
                    <tr>
                        <td><strong><?= \Core\View::escape($du['name']) ?></strong></td>
                        <td class="text-muted"><?= \Core\View::escape($du['email']) ?></td>
                        <td class="text-danger fw-bold"><?= (int)$du['debt_balance'] ?> Kr</td>
                        <td><?= (int)$du['credit_balance'] ?> Kr</td>
                        <td><a href="/admin/users/<?= $du['id'] ?>" class="btn btn-sm btn-outline-danger">Görüntüle</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-2">
        <div class="stat-card stat-card--primary"><div class="stat-card__icon"><i class="fas fa-users"></i></div><div class="stat-card__info"><span class="stat-card__value"><?= $stats['total_users'] ?></span><span class="stat-card__label">Kullanıcı</span></div></div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="stat-card stat-card--info"><div class="stat-card__icon"><i class="fas fa-file-alt"></i></div><div class="stat-card__info"><span class="stat-card__value"><?= $stats['total_requests'] ?></span><span class="stat-card__label">Toplam Talep</span></div></div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="stat-card stat-card--warning"><div class="stat-card__icon"><i class="fas fa-clock"></i></div><div class="stat-card__info"><span class="stat-card__value"><?= $stats['pending_requests'] ?></span><span class="stat-card__label">Bekleyen</span></div></div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="stat-card stat-card--success"><div class="stat-card__icon"><i class="fas fa-check"></i></div><div class="stat-card__info"><span class="stat-card__value"><?= $stats['completed_requests'] ?></span><span class="stat-card__label">Tamamlanan</span></div></div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="stat-card stat-card--danger"><div class="stat-card__icon"><i class="fas fa-coins"></i></div><div class="stat-card__info"><span class="stat-card__value"><?= $stats['total_credits_used'] ?></span><span class="stat-card__label">Kullanılan Kr</span></div></div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="stat-card stat-card--secondary"><div class="stat-card__icon"><i class="fas fa-credit-card"></i></div><div class="stat-card__info"><span class="stat-card__value"><?= $stats['pending_payments'] ?></span><span class="stat-card__label">Bekleyen Ödeme</span></div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between"><h5 class="mb-0">Son Talepler</h5><a href="/admin/requests" class="btn btn-sm btn-outline-primary">Tümü</a></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>No</th><th>Kullanıcı</th><th>Araç</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
                        <tbody>
                            <?php foreach ($recentRequests as $r): ?>
                                <?php $sc = ['pending'=>'warning','reviewing'=>'info','processing'=>'primary','revision'=>'secondary','completed'=>'success','cancelled'=>'danger']; $sl = ['pending'=>'Bekliyor','reviewing'=>'İnceleniyor','processing'=>'İşlemde','revision'=>'Revizyon','completed'=>'Tamamlandı','cancelled'=>'İptal']; ?>
                                <tr>
                                    <td class="fw-600">#<?= \Core\View::escape($r['ticket_no']) ?></td>
                                    <td><?= \Core\View::escape($r['user_name'] ?? '') ?></td>
                                    <td><?= \Core\View::escape(($r['brand_name'] ?? '') . ' ' . ($r['model_name'] ?? '')) ?></td>
                                    <td><span class="badge bg-<?= $sc[$r['status']] ?? 'secondary' ?>"><?= $sl[$r['status']] ?? $r['status'] ?></span></td>
                                    <td class="text-muted"><?= date('d.m.Y H:i', strtotime($r['created_at'])) ?></td>
                                    <td><a href="/admin/requests/<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Son Üyeler</h5></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($recentUsers as $u): ?>
                        <a href="/admin/users/<?= $u['id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between"><strong><?= \Core\View::escape($u['name']) ?></strong><small class="text-muted"><?= date('d.m.Y', strtotime($u['created_at'])) ?></small></div>
                            <small class="text-muted"><?= \Core\View::escape($u['email']) ?></small>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
