<?php $pageTitle = 'Dashboard'; $currentPage = 'dashboard'; ?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card--primary">
            <div class="stat-card__icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $stats['total_requests'] ?></span>
                <span class="stat-card__label">Toplam Talep</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card--warning">
            <div class="stat-card__icon"><i class="fas fa-clock"></i></div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $stats['pending_requests'] ?></span>
                <span class="stat-card__label">Bekleyen</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card--success">
            <div class="stat-card__icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $stats['completed_requests'] ?></span>
                <span class="stat-card__label">Tamamlanan</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card--info">
            <div class="stat-card__icon"><i class="fas fa-coins"></i></div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $stats['credit_balance'] ?></span>
                <span class="stat-card__label">Kredi Bakiye</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Son Talepler</h5>
                <a href="/dashboard/requests" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Talep No</th>
                                <th>Araç</th>
                                <th>Durum</th>
                                <th>Tarih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentRequests)): ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Henüz talep oluşturulmamış</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentRequests as $req): ?>
                                    <tr class="cursor-pointer" onclick="location.href='/dashboard/requests/<?= $req['id'] ?>'">
                                        <td><span class="fw-600">#<?= \Core\View::escape($req['ticket_no']) ?></span></td>
                                        <td><?= \Core\View::escape(($req['brand_name'] ?? '') . ' ' . ($req['model_name'] ?? '')) ?></td>
                                        <td><?php
                                            $statusClasses = ['pending'=>'warning','reviewing'=>'info','processing'=>'primary','revision'=>'secondary','completed'=>'success','cancelled'=>'danger'];
                                            $statusLabels = ['pending'=>'Bekliyor','reviewing'=>'İnceleniyor','processing'=>'İşlemde','revision'=>'Revizyon','completed'=>'Tamamlandı','cancelled'=>'İptal'];
                                            $cls = $statusClasses[$req['status']] ?? 'secondary';
                                            $lbl = $statusLabels[$req['status']] ?? $req['status'];
                                        ?>
                                        <span class="badge bg-<?= $cls ?>"><?= $lbl ?></span></td>
                                        <td class="text-muted"><?= date('d.m.Y H:i', strtotime($req['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Son Kredi Hareketleri</h5>
                <a href="/dashboard/credits" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body">
                <?php if (empty($recentTransactions)): ?>
                    <p class="text-center text-muted py-3">Henüz işlem yok</p>
                <?php else: ?>
                    <div class="transaction-list">
                        <?php foreach ($recentTransactions as $tx): ?>
                            <div class="transaction-item">
                                <div class="transaction-icon <?= $tx['amount'] > 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas <?= $tx['amount'] > 0 ? 'fa-plus-circle' : 'fa-minus-circle' ?>"></i>
                                </div>
                                <div class="transaction-info">
                                    <span class="transaction-desc"><?= \Core\View::escape($tx['description'] ?? '') ?></span>
                                    <small class="text-muted"><?= date('d.m.Y H:i', strtotime($tx['created_at'])) ?></small>
                                </div>
                                <span class="transaction-amount <?= $tx['amount'] > 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= $tx['amount'] > 0 ? '+' : '' ?><?= $tx['amount'] ?> Kr
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body text-center">
                <a href="/dashboard/requests/create" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-plus-circle me-2"></i>Yeni Talep Oluştur
                </a>
            </div>
        </div>
    </div>
</div>
