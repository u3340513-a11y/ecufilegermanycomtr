<?php $pageTitle = 'Arıza Kodları'; $currentPage = 'admin-fault-codes'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Arıza Kodları</h4>
        <small class="text-muted"><?= number_format($total) ?> kayıt</small>
    </div>
    <a href="/admin/fault-codes/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Yeni</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="/admin/fault-codes" class="d-flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm"
                   placeholder="Kod veya başlık ara… (örn: P0300)"
                   value="<?= \Core\View::escape($search) ?>">
            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
                <a href="/admin/fault-codes" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0" id="faultCodesTable">
            <thead>
                <tr>
                    <th style="width:100px">Kod</th>
                    <th>Başlık / Açıklama</th>
                    <th style="width:90px">Yayın</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $c): ?>
                <tr>
                    <td><code class="fw-bold"><?= \Core\View::escape($c['code']) ?></code></td>
                    <td class="text-muted small"><?= \Core\View::escape(mb_substr($c['title'], 0, 80)) ?><?= mb_strlen($c['title']) > 80 ? '…' : '' ?></td>
                    <td><span class="badge bg-<?= $c['is_published'] ? 'success' : 'warning' ?>"><?= $c['is_published'] ? 'Yayında' : 'Taslak' ?></span></td>
                    <td class="text-end">
                        <a href="/admin/fault-codes/<?= $c['id'] ?>/edit" class="btn btn-xs btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/admin/fault-codes/<?= $c['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Sil?')">
                            <?= \Core\View::csrf() ?>
                            <button class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
<nav class="d-flex justify-content-center gap-1 mt-3 flex-wrap">
    <?php if ($page > 1): ?>
        <a href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-left"></i></a>
    <?php endif; ?>
    <?php
    $start = max(1, $page - 3);
    $end   = min($totalPages, $page + 3);
    if ($start > 1): ?><span class="btn btn-sm disabled">…</span><?php endif;
    for ($i = $start; $i <= $end; $i++): ?>
        <a href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"
           class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline-secondary' ?>"><?= $i ?></a>
    <?php endfor;
    if ($end < $totalPages): ?><span class="btn btn-sm disabled">…</span><?php endif; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-right"></i></a>
    <?php endif; ?>
</nav>
<?php endif; ?>
