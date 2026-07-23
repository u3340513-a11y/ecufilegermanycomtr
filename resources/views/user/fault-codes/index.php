<?php $pageTitle = 'Arıza Kodları'; $currentPage = 'fault-codes'; ?>

<div class="fault-codes-page">
    <div class="page-header-bar">
        <div>
            <h1 class="page-title"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Arıza Kodları</h1>
            <p class="page-subtitle">OBD arıza kodları ve Bosch DF↔P kodu eşlemelerini arayın.</p>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="faultTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="obd-tab" data-bs-toggle="tab" data-bs-target="#obd-panel" type="button" role="tab">
                <i class="fas fa-search-plus me-1"></i>OBD Arıza Kodları
                <span class="badge bg-primary ms-1"><?= number_format($total) ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="df-tab" data-bs-toggle="tab" data-bs-target="#df-panel" type="button" role="tab">
                <i class="fas fa-random me-1"></i>DF → P Kodu
                <span class="badge bg-danger ms-1"><?= number_format(count($dfCodes)) ?></span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="faultTabContent">

        <div class="tab-pane fade show active" id="obd-panel" role="tabpanel">

    <div class="card fault-search-card">
        <div class="card-body">
            <form method="GET" action="/dashboard/fault-codes" id="faultSearchForm">
                <div class="fault-search-wrap">
                    <i class="fas fa-search fault-search-icon"></i>
                    <input
                        type="text"
                        name="q"
                        id="faultSearchInput"
                        class="fault-search-input"
                        placeholder="Kod numarası, başlık veya açıklama ara… (örn: P0301)"
                        value="<?= \Core\View::escape($search) ?>"
                        autocomplete="off"
                    >
                    <?php if ($search): ?>
                        <a href="/dashboard/fault-codes" class="fault-search-clear" title="Temizle">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="fault-search-btn">Ara</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($search): ?>
        <div class="search-result-info">
            <i class="fas fa-filter me-1"></i>
            <strong>"<?= \Core\View::escape($search) ?>"</strong> için
            <strong><?= number_format($total) ?></strong> sonuç bulundu.
        </div>
    <?php endif; ?>

    <?php if (empty($codes)): ?>
        <div class="empty-state">
            <div class="empty-state__icon"><i class="fas fa-search-minus"></i></div>
            <h3>Sonuç bulunamadı</h3>
            <p>Arama kriterlerinizi değiştirerek tekrar deneyin.</p>
            <?php if ($search): ?>
                <a href="/dashboard/fault-codes" class="btn btn-outline-primary mt-2">Tümünü Listele</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="fault-codes-grid">
            <?php foreach ($codes as $fc): ?>
                <div class="fault-card" id="fc-<?= $fc['id'] ?>">
                    <div class="fault-card__header">
                        <span class="fault-code-badge"><?= \Core\View::escape($fc['code']) ?></span>
                        <button class="fault-card__toggle" data-target="detail-<?= $fc['id'] ?>" aria-expanded="false">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="fault-card__title"><?= \Core\View::escape($fc['title']) ?></div>

                    <?php if ($fc['description']): ?>
                        <p class="fault-card__desc"><?= nl2br(\Core\View::escape(mb_substr($fc['description'], 0, 120))) ?><?= mb_strlen($fc['description']) > 120 ? '…' : '' ?></p>
                    <?php endif; ?>

                    <div class="fault-card__detail" id="detail-<?= $fc['id'] ?>" style="display:none;">
                        <?php if ($fc['description']): ?>
                            <div class="detail-section">
                                <h6><i class="fas fa-info-circle me-1 text-primary"></i>Açıklama</h6>
                                <p><?= nl2br(\Core\View::escape($fc['description'])) ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($fc['solution']): ?>
                            <div class="detail-section">
                                <h6><i class="fas fa-tools me-1 text-success"></i>Çözüm</h6>
                                <p><?= nl2br(\Core\View::escape($fc['solution'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="fault-pagination">
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
        </div><!-- /#obd-panel -->

        <div class="tab-pane fade" id="df-panel" role="tabpanel">
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" action="/dashboard/fault-codes" class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="tab" value="df">
                        <input type="text" name="dq" class="form-control form-control-sm"
                               placeholder="DF kodu, P kodu veya açıklama ara…"
                               value="<?= \Core\View::escape($dfSearch) ?>">
                        <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i></button>
                        <?php if ($dfSearch): ?>
                            <a href="/dashboard/fault-codes?tab=df" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <?php if (empty($dfCodes)): ?>
                <div class="empty-state">
                    <div class="empty-state__icon"><i class="fas fa-search-minus"></i></div>
                    <h3>Sonuç bulunamadı</h3>
                    <p>Arama kriterlerinizi değiştirerek tekrar deneyin.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>DF Kodu</th>
                                <th>P Kodu</th>
                                <th>Açıklama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dfCodes as $df): ?>
                            <tr>
                                <td><span class="fault-code-badge df-badge"><?= \Core\View::escape($df['df_code']) ?></span></td>
                                <td><span class="fault-code-badge"><?= \Core\View::escape($df['p_code']) ?></span></td>
                                <td class="text-muted small"><?= \Core\View::escape($df['description'] ?? '—') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div><!-- /#df-panel -->

    </div><!-- /.tab-content -->
</div>

<style>
.fault-codes-page { padding-bottom: 3rem; }

.page-header-bar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: .75rem;
}
.page-title { font-size: 1.5rem; font-weight: 700; margin: 0; }
.page-subtitle { color: var(--text-muted, #6c757d); margin: .25rem 0 0; font-size: .9rem; }
.page-header-meta { align-self: center; }
.badge-total {
    display: inline-flex;
    align-items: center;
    padding: .35rem .9rem;
    border-radius: 2rem;
    background: rgba(var(--primary-rgb, 99,102,241), .12);
    color: var(--primary, #6366f1);
    font-weight: 600;
    font-size: .85rem;
}

.fault-search-card { border-radius: 1rem; margin-bottom: 1.25rem; }
.fault-search-wrap {
    display: flex;
    align-items: center;
    position: relative;
}
.fault-search-icon {
    position: absolute;
    left: 1rem;
    color: var(--text-muted, #6c757d);
    pointer-events: none;
}
.fault-search-input {
    width: 100%;
    padding: .75rem 1rem .75rem 2.75rem;
    border: 1.5px solid rgba(0,0,0,.1);
    border-radius: .75rem;
    font-size: .95rem;
    background: var(--input-bg, #f8f9fa);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.fault-search-input:focus {
    border-color: var(--primary, #6366f1);
    box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    background: #fff;
}
.fault-search-clear {
    position: absolute;
    right: 7.5rem;
    color: var(--text-muted, #6c757d);
    text-decoration: none;
    padding: .25rem .5rem;
    transition: color .2s;
}
.fault-search-clear:hover { color: #dc3545; }
.fault-search-btn {
    margin-left: .75rem;
    padding: .75rem 1.5rem;
    border: none;
    border-radius: .75rem;
    background: var(--primary, #6366f1);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: opacity .2s, transform .15s;
}
.fault-search-btn:hover { opacity: .9; transform: translateY(-1px); }

.search-result-info {
    font-size: .9rem;
    color: var(--text-muted, #6c757d);
    margin-bottom: 1rem;
    padding: .5rem .75rem;
    background: rgba(var(--primary-rgb,99,102,241),.06);
    border-radius: .5rem;
    display: inline-block;
}

.empty-state {
    text-align: center;
    padding: 4rem 1rem;
    color: var(--text-muted, #6c757d);
}
.empty-state__icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: .4;
}
.empty-state h3 { font-size: 1.25rem; font-weight: 600; margin-bottom: .5rem; }

.fault-codes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1rem;
}

.fault-card {
    background: var(--card-bg, #fff);
    border: 1.5px solid rgba(0,0,0,.07);
    border-radius: 1rem;
    padding: 1.25rem;
    transition: box-shadow .2s, transform .15s, border-color .2s;
}
.fault-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,.08);
    transform: translateY(-2px);
    border-color: rgba(var(--primary-rgb,99,102,241),.25);
}
.fault-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: .6rem;
}
.fault-code-badge {
    display: inline-block;
    padding: .3rem .75rem;
    border-radius: .5rem;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: .85rem;
    font-weight: 700;
    letter-spacing: .05em;
}
.fault-card__toggle {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-muted, #6c757d);
    padding: .25rem .5rem;
    border-radius: .5rem;
    transition: color .2s, background .2s;
    display: flex;
    align-items: center;
}
.fault-card__toggle:hover { background: rgba(0,0,0,.05); color: var(--primary, #6366f1); }
.fault-card__toggle.open i { transform: rotate(180deg); }
.fault-card__toggle i { transition: transform .25s; }

.fault-card__title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    margin-bottom: .5rem;
    line-height: 1.4;
}
.fault-card__desc {
    font-size: .875rem;
    color: var(--text-muted, #6c757d);
    margin: 0;
    line-height: 1.6;
}
.fault-card__detail {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(0,0,0,.07);
    animation: slideDown .2s ease;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.detail-section { margin-bottom: .75rem; }
.detail-section:last-child { margin-bottom: 0; }
.detail-section h6 {
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: .35rem;
    display: flex;
    align-items: center;
}
.detail-section p {
    font-size: .875rem;
    color: var(--text-secondary, #475569);
    margin: 0;
    line-height: 1.65;
}

.fault-pagination {
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
    color: var(--text-primary, #1e293b);
    background: var(--card-bg, #fff);
    border: 1.5px solid rgba(0,0,0,.1);
    font-size: .875rem;
    font-weight: 500;
    transition: background .15s, color .15s, border-color .15s;
    padding: 0 .65rem;
}
.page-btn:hover { background: var(--primary, #6366f1); color: #fff; border-color: var(--primary, #6366f1); }
.page-btn--active { background: var(--primary, #6366f1); color: #fff; border-color: var(--primary, #6366f1); pointer-events: none; }
.page-dots { color: var(--text-muted, #6c757d); padding: 0 .25rem; }
</style>

<?php $extraJs = <<<'JS'
<script>
document.querySelectorAll('.fault-card__toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var targetId = this.dataset.target;
        var detail   = document.getElementById(targetId);
        var isOpen   = detail.style.display !== 'none';
        detail.style.display = isOpen ? 'none' : 'block';
        this.classList.toggle('open', !isOpen);
        this.setAttribute('aria-expanded', String(!isOpen));
    });
});
</script>
JS;
?>
