<?php $pageTitle = 'Bosch ECU Sorgula'; $currentPage = 'bosch-ecu'; ?>

<div class="lookup-page">
    <div class="page-header-bar">
        <div>
            <h1 class="page-title"><i class="fas fa-microchip me-2 text-primary"></i>Bosch ECU Sorgula</h1>
            <p class="page-subtitle">ECU numarasını girerek ECU tipini öğrenin. <?= number_format($total) ?> kayıt.</p>
        </div>
    </div>

    <div class="card lookup-search-card">
        <div class="card-body">
            <form method="GET" action="/dashboard/bosch-ecu" id="ecuSearchForm">
                <div class="lookup-search-wrap">
                    <i class="fas fa-search lookup-search-icon"></i>
                    <input
                        type="text"
                        name="q"
                        id="ecuSearchInput"
                        class="lookup-search-input"
                        placeholder="ECU numarası veya tip ara… (örn: 0281014238 veya EDC17)"
                        value="<?= \Core\View::escape($search) ?>"
                        autocomplete="off"
                    >
                    <?php if ($search): ?>
                        <a href="/dashboard/bosch-ecu" class="lookup-search-clear" title="Temizle">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="lookup-search-btn">Sorgula</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($search): ?>
        <div class="search-result-info">
            <i class="fas fa-filter me-1"></i>
            <strong>"<?= \Core\View::escape($search) ?>"</strong> için
            <strong><?= number_format($total) ?></strong> sonuç
        </div>
    <?php endif; ?>

    <?php if (empty($ecus)): ?>
        <div class="empty-state">
            <div class="empty-state__icon"><i class="fas fa-search-minus"></i></div>
            <h3>Sonuç bulunamadı</h3>
            <p>Farklı bir ECU numarası veya tip deneyin.</p>
            <?php if ($search): ?>
                <a href="/dashboard/bosch-ecu" class="btn btn-outline-primary mt-2">Tümünü Listele</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="ecu-grid">
            <?php foreach ($ecus as $ecu): ?>
                <div class="ecu-card">
                    <div class="ecu-card__number">
                        <i class="fas fa-barcode me-1 text-muted" style="font-size:.75rem"></i>
                        <?= \Core\View::escape($ecu['ecu_number']) ?>
                    </div>
                    <div class="ecu-card__type">
                        <span class="ecu-type-badge"><?= \Core\View::escape($ecu['ecu_type']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="lookup-pagination">
                <?php if ($page > 1): ?>
                    <a href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                <?php endif; ?>
                <?php
                $start = max(1, $page - 2);
                $end   = min($totalPages, $page + 2);
                if ($start > 1): ?><span class="page-dots">…</span><?php endif;
                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"
                       class="page-btn <?= $i === $page ? 'page-btn--active' : '' ?>"><?= $i ?></a>
                <?php endfor;
                if ($end < $totalPages): ?><span class="page-dots">…</span><?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.lookup-page { padding-bottom: 3rem; }

.page-header-bar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: .75rem;
}
.page-title  { font-size: 1.5rem; font-weight: 700; margin: 0; }
.page-subtitle { color: var(--text-muted,#6c757d); margin: .25rem 0 0; font-size: .9rem; }

.lookup-search-card  { border-radius: 1rem; margin-bottom: 1.25rem; }
.lookup-search-wrap  { display: flex; align-items: center; position: relative; }
.lookup-search-icon  { position: absolute; left: 1rem; color: var(--text-muted,#6c757d); pointer-events: none; }
.lookup-search-input {
    width: 100%;
    padding: .75rem 1rem .75rem 2.75rem;
    border: 1.5px solid rgba(0,0,0,.1);
    border-radius: .75rem;
    font-size: .95rem;
    background: var(--input-bg,#f8f9fa);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    font-family: 'JetBrains Mono','Fira Code',monospace;
}
.lookup-search-input:focus {
    border-color: var(--primary,#6366f1);
    box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    background: #fff;
}
.lookup-search-clear {
    position: absolute;
    right: 7.5rem;
    color: var(--text-muted,#6c757d);
    text-decoration: none;
    padding: .25rem .5rem;
    transition: color .2s;
}
.lookup-search-clear:hover { color: #dc3545; }
.lookup-search-btn {
    margin-left: .75rem;
    padding: .75rem 1.5rem;
    border: none;
    border-radius: .75rem;
    background: var(--primary,#6366f1);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: opacity .2s, transform .15s;
}
.lookup-search-btn:hover { opacity: .9; transform: translateY(-1px); }

.search-result-info {
    font-size: .9rem;
    color: var(--text-muted,#6c757d);
    margin-bottom: 1rem;
    padding: .5rem .75rem;
    background: rgba(var(--primary-rgb,99,102,241),.06);
    border-radius: .5rem;
    display: inline-block;
}

.empty-state { text-align: center; padding: 4rem 1rem; color: var(--text-muted,#6c757d); }
.empty-state__icon { font-size: 3rem; margin-bottom: 1rem; opacity: .4; }
.empty-state h3 { font-size: 1.25rem; font-weight: 600; margin-bottom: .5rem; }

.ecu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: .85rem;
}
.ecu-card {
    background: var(--card-bg,#fff);
    border: 1.5px solid rgba(0,0,0,.07);
    border-radius: .9rem;
    padding: 1rem 1.2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    transition: box-shadow .2s, transform .15s, border-color .2s;
}
.ecu-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,.08);
    transform: translateY(-2px);
    border-color: rgba(var(--primary-rgb,99,102,241),.25);
}
.ecu-card__number {
    font-family: 'JetBrains Mono','Fira Code',monospace;
    font-size: .875rem;
    font-weight: 700;
    color: var(--text-primary,#1e293b);
    letter-spacing: .03em;
}
.ecu-type-badge {
    display: inline-block;
    padding: .25rem .65rem;
    border-radius: .45rem;
    background: rgba(var(--primary-rgb,99,102,241),.1);
    color: var(--primary,#6366f1);
    font-size: .8rem;
    font-weight: 700;
    white-space: nowrap;
}

.lookup-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}
.page-btn {
    min-width: 2.25rem;
    height: 2.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: .6rem;
    text-decoration: none;
    color: var(--text-primary,#1e293b);
    background: var(--card-bg,#fff);
    border: 1.5px solid rgba(0,0,0,.1);
    font-size: .875rem;
    font-weight: 500;
    transition: background .15s, color .15s, border-color .15s;
    padding: 0 .65rem;
}
.page-btn:hover { background: var(--primary,#6366f1); color:#fff; border-color:var(--primary,#6366f1); }
.page-btn--active { background:var(--primary,#6366f1); color:#fff; border-color:var(--primary,#6366f1); pointer-events:none; }
.page-dots { color:var(--text-muted,#6c757d); padding:0 .25rem; }
</style>
