<?php $pageTitle = 'Taleplerim'; $currentPage = 'requests'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="/dashboard/requests/create" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Yeni Talep
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="requestsTable">
                <thead>
                    <tr>
                        <th>Talep No</th>
                        <th>Araç</th>
                        <th>Hizmetler</th>
                        <th>Kredi</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Henüz talep oluşturulmamış</td></tr>
                    <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                            <tr>
                                <td><span class="fw-600">#<?= \Core\View::escape($req['ticket_no']) ?></span></td>
                                <td><?= \Core\View::escape(($req['brand_name'] ?? '') . ' ' . ($req['model_name'] ?? '')) ?></td>
                                <td><span class="text-muted small"><?= $req['total_credits'] ?> Kr</span></td>
                                <td><?= $req['total_credits'] ?></td>
                                <td><?php
                                    $sc = ['pending'=>'warning','reviewing'=>'info','processing'=>'primary','revision'=>'secondary','completed'=>'success','cancelled'=>'danger'];
                                    $sl = ['pending'=>'Bekliyor','reviewing'=>'İnceleniyor','processing'=>'İşlemde','revision'=>'Revizyon','completed'=>'Tamamlandı','cancelled'=>'İptal'];
                                ?><span class="badge bg-<?= $sc[$req['status']] ?? 'secondary' ?>"><?= $sl[$req['status']] ?? $req['status'] ?></span></td>
                                <td class="text-muted"><?= date('d.m.Y H:i', strtotime($req['created_at'])) ?></td>
                                <td><a href="/dashboard/requests/<?= $req['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <?= \App\Helpers\Pagination::render($page, $totalPages, '/dashboard/requests') ?>
        <?php endif; ?>
    </div>
</div>
