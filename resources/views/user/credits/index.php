<?php $pageTitle = 'Kredilerim'; $currentPage = 'credits'; ?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card stat-card--info">
            <div class="stat-card__icon"><i class="fas fa-coins"></i></div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $balance ?></span>
                <span class="stat-card__label">Mevcut Bakiye</span>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($pendingPayments)): ?>
    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0"><i class="fas fa-clock me-2"></i>Bekleyen Ödemeler</h6></div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Kredi</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th>Ödeme Linki</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingPayments as $pl): ?>
                        <tr>
                            <td><?= $pl['credit_amount'] ?> Kr</td>
                            <td><?= number_format((float)$pl['price'], 2) ?> <?= $pl['currency'] ?></td>
                            <td><span class="badge bg-warning">Bekliyor</span></td>
                            <td><a href="<?= \Core\View::escape($pl['stripe_link']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-external-link-alt me-1"></i>Öde</a></td>
                            <td class="text-muted"><?= date('d.m.Y H:i', strtotime($pl['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h6 class="mb-0"><i class="fas fa-history me-2"></i>Kredi Geçmişi</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>İşlem</th>
                        <th>Tutar</th>
                        <th>Bakiye</th>
                        <th>Açıklama</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Henüz işlem yok</td></tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $tx): ?>
                            <?php
                            $typeLabels = ['purchase'=>'Satın Alma','usage'=>'Kullanım','refund'=>'İade','admin_add'=>'Admin Ekleme'];
                            $typeColors = ['purchase'=>'success','usage'=>'danger','refund'=>'info','admin_add'=>'success'];
                            ?>
                            <tr>
                                <td><span class="badge bg-<?= $typeColors[$tx['type']] ?? 'secondary' ?>"><?= $typeLabels[$tx['type']] ?? $tx['type'] ?></span></td>
                                <td class="fw-600 <?= $tx['amount'] > 0 ? 'text-success' : 'text-danger' ?>"><?= $tx['amount'] > 0 ? '+' : '' ?><?= $tx['amount'] ?> Kr</td>
                                <td><?= $tx['balance_after'] ?> Kr</td>
                                <td class="text-muted"><?= \Core\View::escape($tx['description'] ?? '') ?></td>
                                <td class="text-muted"><?= date('d.m.Y H:i', strtotime($tx['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($totalPages > 1): ?>
    <?= \App\Helpers\Pagination::render($page, $totalPages, '/dashboard/credits') ?>
<?php endif; ?>
