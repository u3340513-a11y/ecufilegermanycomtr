<?php $pageTitle = 'Bosch ECU'; $currentPage = 'admin-bosch-ecu'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Bosch ECU Listesi</h4>
        <small class="text-muted"><?= number_format(count($ecus)) ?> kayıt</small>
    </div>
    <a href="/admin/bosch-ecu/create" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Yeni Kayıt</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="boschTable">
            <thead>
                <tr>
                    <th>ECU Numarası</th>
                    <th>ECU Tipi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ecus as $e): ?>
                <tr>
                    <td class="fw-bold font-monospace"><?= \Core\View::escape($e['ecu_number']) ?></td>
                    <td><?= \Core\View::escape($e['ecu_type'] ?? '-') ?></td>
                    <td class="text-end">
                        <a href="/admin/bosch-ecu/<?= $e['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/admin/bosch-ecu/<?= $e['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Sil?')">
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
