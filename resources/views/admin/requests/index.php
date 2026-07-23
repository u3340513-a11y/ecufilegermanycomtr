<?php $pageTitle = 'Talepler'; $currentPage = 'admin-requests'; $sc = ['pending'=>'warning','reviewing'=>'info','processing'=>'primary','revision'=>'secondary','completed'=>'success','cancelled'=>'danger']; $sl = ['pending'=>'Bekliyor','reviewing'=>'İnceleniyor','processing'=>'İşlemde','revision'=>'Revizyon','completed'=>'Tamamlandı','cancelled'=>'İptal']; ?>
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="/admin/requests" class="btn btn-<?= !$statusFilter ? 'primary' : 'outline-primary' ?> btn-sm">Tümü (<?= $total ?>)</a>
    <?php foreach ($statusCounts as $sc2): ?><a href="/admin/requests?status=<?= $sc2['status'] ?>" class="btn btn-<?= $statusFilter === $sc2['status'] ? ($sc[$sc2['status']] ?? 'primary') : 'outline-secondary' ?> btn-sm"><?= $sl[$sc2['status']] ?? $sc2['status'] ?> (<?= $sc2['total'] ?>)</a><?php endforeach; ?>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0">
    <thead><tr><th>No</th><th>Kullanıcı</th><th>Araç</th><th>Kredi</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
    <tbody>
        <?php foreach ($requests as $r): ?><tr>
            <td class="fw-600">#<?= \Core\View::escape($r['ticket_no']) ?></td>
            <td><?= \Core\View::escape($r['user_name'] ?? '') ?><br><small class="text-muted"><?= \Core\View::escape($r['user_email'] ?? '') ?></small></td>
            <td><?= \Core\View::escape(($r['brand_name'] ?? '') . ' ' . ($r['model_name'] ?? '')) ?></td>
            <td><?= $r['total_credits'] ?></td>
            <td><span class="badge bg-<?= $sc[$r['status']] ?? 'secondary' ?>"><?= $sl[$r['status']] ?? $r['status'] ?></span></td>
            <td class="text-muted"><?= date('d.m.Y H:i', strtotime($r['created_at'])) ?></td>
            <td><a href="/admin/requests/<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
        </tr><?php endforeach; ?>
    </tbody>
</table></div></div></div>
<?php if ($totalPages > 1): ?><?= \App\Helpers\Pagination::render($page, $totalPages, '/admin/requests', $statusFilter ? ['status' => $statusFilter] : []) ?><?php endif; ?>
