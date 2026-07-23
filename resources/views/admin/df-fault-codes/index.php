<?php $pageTitle = 'DF Arıza Kodları'; $currentPage = 'admin-df-fault-codes'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">DF → P Kodu Eşlemeleri</h4>
        <small class="text-muted"><?= number_format(count($codes)) ?> kayıt</small>
    </div>
    <a href="/admin/df-fault-codes/create" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Yeni Kayıt</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="/admin/df-fault-codes" class="d-flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm"
                   placeholder="DF kodu, P kodu veya açıklama…"
                   value="<?= \Core\View::escape($search) ?>">
            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
                <a href="/admin/df-fault-codes" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="dfCodesTable">
            <thead>
                <tr>
                    <th>DF Kodu</th>
                    <th>P Kodu</th>
                    <th>Açıklama</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $c): ?>
                <tr>
                    <td><span class="badge bg-danger font-monospace"><?= \Core\View::escape($c['df_code']) ?></span></td>
                    <td><span class="badge bg-primary font-monospace"><?= \Core\View::escape($c['p_code']) ?></span></td>
                    <td class="text-muted small"><?= \Core\View::escape($c['description'] ?? '-') ?></td>
                    <td class="text-end">
                        <a href="/admin/df-fault-codes/<?= $c['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/admin/df-fault-codes/<?= $c['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Sil?')">
                            <?= \Core\View::csrf() ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
