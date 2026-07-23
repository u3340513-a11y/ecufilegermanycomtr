<?php $pageTitle = 'ECU Listesi'; $currentPage = 'ecu-list'; ?>

<div class="lookup-page">
    <div class="page-header-bar">
        <div>
            <h1 class="page-title"><i class="fas fa-server me-2 text-info"></i>ECU Listesi</h1>
            <p class="page-subtitle">Desteklenen ECU modüllerini görüntüleyin. <?= number_format($total) ?> aktif ECU.</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="/dashboard/ecu-list" class="ecu-filter-wrap">
                <div class="lookup-search-wrap" style="flex:1">
                    <i class="fas fa-search lookup-search-icon"></i>
                    <input
                        type="text"
                        name="q"
                        class="lookup-search-input"
                        placeholder="ECU adı ara… (örn: ME7.5)"
                        value="<?= \Core\View::escape($search) ?>"
                        autocomplete="off"
                    >
                </div>
                <select name="brand" class="form-select ecu-brand-select">
                    <option value="">Tüm Markalar</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= \Core\View::escape($b['brand']) ?>"
                            <?= $brand === $b['brand'] ? 'selected' : '' ?>>
                            <?= \Core\View::escape($b['brand']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="lookup-search-btn">Filtrele</button>
                <?php if ($search || $brand): ?>
                    <a href="/dashboard/ecu-list" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if ($search || $brand): ?>
        <div class="search-result-info">
            <i class="fas fa-filter me-1"></i>
            <strong><?= number_format($total) ?></strong> sonuç
            <?php if ($brand): ?> — Marka: <strong><?= \Core\View::escape($brand) ?></strong><?php endif; ?>
            <?php if ($search): ?> — Arama: <strong>"<?= \Core\View::escape($search) ?>"</strong><?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($ecus)): ?>
        <div class="empty-state">
            <div class="empty-state__icon"><i class="fas fa-search-minus"></i></div>
            <h3>Sonuç bulunamadı</h3>
            <p>Farklı bir filtre deneyin.</p>
        </div>
    <?php else: ?>
        <?php
        $grouped = [];
        foreach ($ecus as $ecu) {
            $grouped[$ecu['brand'] ?? 'Diğer'][] = $ecu;
        }
        ?>
        <?php foreach ($grouped as $brandName => $items): ?>
            <div class="ecu-brand-group">
                <div class="ecu-brand-header">
                    <i class="fas fa-tag me-1"></i><?= \Core\View::escape($brandName) ?>
                    <span class="ecu-brand-count"><?= count($items) ?></span>
                </div>
                <div class="ecu-name-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="ecu-name-chip"><?= \Core\View::escape($item['name']) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($totalPages > 1): ?>
            <nav class="lookup-pagination">
                <?php if ($page > 1): ?>
                    <a href="?q=<?= urlencode($search) ?>&brand=<?= urlencode($brand) ?>&page=<?= $page - 1 ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                <?php endif; ?>
                <?php
                $start = max(1, $page - 2);
                $end   = min($totalPages, $page + 2);
                if ($start > 1): ?><span class="page-dots">…</span><?php endif;
                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?q=<?= urlencode($search) ?>&brand=<?= urlencode($brand) ?>&page=<?= $i ?>"
                       class="page-btn <?= $i === $page ? 'page-btn--active' : '' ?>"><?= $i ?></a>
                <?php endfor;
                if ($end < $totalPages): ?><span class="page-dots">…</span><?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?q=<?= urlencode($search) ?>&brand=<?= urlencode($brand) ?>&page=<?= $page + 1 ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.lookup-page { padding-bottom: 3rem; }
.page-header-bar { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:.75rem; }
.page-title { font-size:1.5rem; font-weight:700; margin:0; }
.page-subtitle { color:var(--text-muted,#6c757d); margin:.25rem 0 0; font-size:.9rem; }

.ecu-filter-wrap { display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; }
.lookup-search-wrap { display:flex; align-items:center; position:relative; min-width:200px; }
.lookup-search-icon { position:absolute; left:1rem; color:var(--text-muted,#6c757d); pointer-events:none; }
.lookup-search-input {
    width:100%; padding:.7rem 1rem .7rem 2.75rem;
    border:1.5px solid rgba(0,0,0,.1); border-radius:.75rem;
    font-size:.9rem; background:var(--input-bg,#f8f9fa);
    transition:border-color .2s,box-shadow .2s; outline:none;
}
.lookup-search-input:focus { border-color:var(--primary,#6366f1); box-shadow:0 0 0 3px rgba(99,102,241,.12); background:#fff; }
.ecu-brand-select { max-width:180px; border-radius:.75rem; font-size:.9rem; }
.lookup-search-btn {
    padding:.7rem 1.4rem; border:none; border-radius:.75rem;
    background:var(--primary,#6366f1); color:#fff; font-weight:600;
    cursor:pointer; white-space:nowrap; transition:opacity .2s,transform .15s;
}
.lookup-search-btn:hover { opacity:.9; transform:translateY(-1px); }

.search-result-info { font-size:.9rem; color:var(--text-muted,#6c757d); margin-bottom:1rem; padding:.5rem .75rem; background:rgba(var(--primary-rgb,99,102,241),.06); border-radius:.5rem; display:inline-block; }

.empty-state { text-align:center; padding:4rem 1rem; color:var(--text-muted,#6c757d); }
.empty-state__icon { font-size:3rem; margin-bottom:1rem; opacity:.4; }
.empty-state h3 { font-size:1.25rem; font-weight:600; margin-bottom:.5rem; }

.ecu-brand-group { margin-bottom:1.5rem; }
.ecu-brand-header {
    display:inline-flex; align-items:center; gap:.5rem;
    font-size:.8rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.08em; color:var(--text-muted,#6c757d);
    margin-bottom:.75rem; padding-bottom:.4rem;
    border-bottom:2px solid rgba(var(--primary-rgb,99,102,241),.15);
}
.ecu-brand-count {
    display:inline-flex; align-items:center; justify-content:center;
    background:rgba(var(--primary-rgb,99,102,241),.12);
    color:var(--primary,#6366f1);
    font-size:.7rem; font-weight:700;
    border-radius:1rem; padding:1px 7px; min-width:22px;
}

.ecu-name-grid { display:flex; flex-wrap:wrap; gap:.5rem; }
.ecu-name-chip {
    padding:.35rem .85rem;
    background:var(--card-bg,#fff);
    border:1.5px solid rgba(0,0,0,.08);
    border-radius:.5rem;
    font-size:.875rem;
    font-weight:500;
    color:var(--text-primary,#1e293b);
    transition:border-color .15s,box-shadow .15s,background .15s;
}
.ecu-name-chip:hover { border-color:var(--primary,#6366f1); background:rgba(var(--primary-rgb,99,102,241),.05); box-shadow:0 2px 8px rgba(99,102,241,.1); }

.lookup-pagination { display:flex; align-items:center; justify-content:center; gap:.4rem; margin-top:2rem; flex-wrap:wrap; }
.page-btn { min-width:2.25rem; height:2.25rem; display:inline-flex; align-items:center; justify-content:center; border-radius:.6rem; text-decoration:none; color:var(--text-primary,#1e293b); background:var(--card-bg,#fff); border:1.5px solid rgba(0,0,0,.1); font-size:.875rem; font-weight:500; transition:background .15s,color .15s,border-color .15s; padding:0 .65rem; }
.page-btn:hover { background:var(--primary,#6366f1); color:#fff; border-color:var(--primary,#6366f1); }
.page-btn--active { background:var(--primary,#6366f1); color:#fff; border-color:var(--primary,#6366f1); pointer-events:none; }
.page-dots { color:var(--text-muted,#6c757d); padding:0 .25rem; }
</style>
