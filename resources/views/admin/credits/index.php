<?php $pageTitle = 'Kredi Yönetimi'; $currentPage = 'admin-credits'; ?>
<div class="row g-4 mb-4">

    <!-- Kredi Ekle -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Kredi Ekle</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/credits/add"><?= \Core\View::csrf() ?>
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= \Core\View::escape($u['name']) ?> (<?= \Core\View::escape($u['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kredi Miktarı</label>
                        <input type="number" name="amount" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <input type="text" name="description" class="form-control" value="Admin tarafından eklendi">
                    </div>
                    <button class="btn btn-success w-100"><i class="fas fa-plus me-1"></i>Ekle</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kredi Düş -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-minus-circle me-2 text-danger"></i>Kredi Düş</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/credits/deduct"><?= \Core\View::csrf() ?>
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= \Core\View::escape($u['name']) ?> (<?= \Core\View::escape($u['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kredi Miktarı</label>
                        <input type="number" name="amount" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <input type="text" name="description" class="form-control" value="Admin tarafından düşüldü">
                    </div>
                    <button class="btn btn-danger w-100"><i class="fas fa-minus me-1"></i>Düş</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Borç Olarak Kredi Ver -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-hand-holding-usd me-2 text-warning"></i>Borç Olarak Kredi Ver</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Kullanıcı krediyi hemen kullanabilir. Borç bakiyesi Cumartesi günleri yönetim panelinde uyarı olarak gösterilir.</p>
                <form method="POST" action="/admin/credits/debt"><?= \Core\View::csrf() ?>
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= \Core\View::escape($u['name']) ?> (<?= \Core\View::escape($u['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Borç Kredi Miktarı</label>
                        <input type="number" name="amount" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <input type="text" name="description" class="form-control" value="Borç olarak verildi">
                    </div>
                    <button class="btn btn-warning w-100 text-dark"><i class="fas fa-hand-holding-usd me-1"></i>Borç Ver</button>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Son İşlemler Tablosu -->
<div class="card">
    <div class="card-header"><h6 class="mb-0">Son İşlemler</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kullanıcı</th>
                        <th>İşlem</th>
                        <th>Tutar</th>
                        <th>Bakiye</th>
                        <th>Açıklama</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $typeLabels  = ['purchase' => 'Satın Alma', 'usage' => 'Kullanım', 'refund' => 'İade', 'admin_add' => 'Admin Ekleme', 'admin_deduct' => 'Admin Düşme', 'debt' => 'Borç'];
                    $typeClasses = ['purchase' => 'success', 'usage' => 'danger', 'refund' => 'info', 'admin_add' => 'success', 'admin_deduct' => 'warning', 'debt' => 'warning'];
                    foreach ($transactions as $t):
                    ?>
                    <tr>
                        <td><?= \Core\View::escape($t['user_name'] ?? '') ?></td>
                        <td>
                            <span class="badge bg-<?= $typeClasses[$t['type']] ?? 'secondary' ?>">
                                <?= $typeLabels[$t['type']] ?? $t['type'] ?>
                            </span>
                        </td>
                        <td class="fw-600 <?= $t['amount'] > 0 ? 'text-success' : 'text-danger' ?>">
                            <?= $t['amount'] > 0 ? '+' : '' ?><?= $t['amount'] ?>
                        </td>
                        <td><?= $t['balance_after'] ?></td>
                        <td class="text-muted small"><?= \Core\View::escape($t['description'] ?? '') ?></td>
                        <td class="text-muted"><?= date('d.m.Y H:i', strtotime($t['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
