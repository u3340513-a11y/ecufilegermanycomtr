<?php $pageTitle = $pageTitle ?? 'Landing Page Editor'; $currentPage = $currentPage ?? 'admin-landing'; ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Landing Page Editor</h4>
        <p class="text-muted small mb-0">Edit all landing page sections in one place</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-external-link-alt me-1"></i>Preview Site
        </a>
        <button type="submit" form="landingForm" class="btn btn-sm btn-primary">
            <i class="fas fa-save me-1"></i>Save All Changes
        </button>
    </div>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= \Core\View::escape($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?= \Core\View::alert() ?>

<form method="POST" action="/admin/landing/save" id="landingForm">
    <?= \Core\View::csrf() ?>

    <?php
    $icons = [
        'hero'         => 'fas fa-rocket',
        'notice'       => 'fas fa-bell',
        'how_it_works' => 'fas fa-list-ol',
        'showcase'     => 'fas fa-images',
        'about'        => 'fas fa-info-circle',
        'cta'          => 'fas fa-bullhorn',
        'footer'       => 'fas fa-shoe-prints',
    ];
    foreach ($sections as $sectionKey => $fields):
        $label = $sectionLabels[$sectionKey] ?? ucfirst(str_replace('_', ' ', $sectionKey));
        $icon  = $icons[$sectionKey] ?? 'fas fa-edit';
        $hasImages = array_filter($fields, fn($f) => $f['type'] === 'image');
    ?>
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center gap-2"
             role="button" data-bs-toggle="collapse"
             data-bs-target="#section-<?= $sectionKey ?>" aria-expanded="true">
            <div class="lp-editor-icon"><i class="<?= $icon ?>"></i></div>
            <span class="fw-semibold"><?= $label ?></span>
            <?php if ($hasImages): ?>
                <span class="badge bg-info ms-1 small">Has Images</span>
            <?php endif; ?>
            <i class="fas fa-chevron-down ms-auto text-muted small"></i>
        </div>
        <div class="collapse show" id="section-<?= $sectionKey ?>">
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php foreach ($fields as $field): ?>
                        <?php
                        $inputName = "fields[{$sectionKey}][{$field['key_name']}]";
                        $val = htmlspecialchars($field['value'] ?? '', ENT_QUOTES);
                        ?>
                        <div class="<?= $field['type'] === 'image' ? 'col-md-6' : ($field['type'] === 'textarea' ? 'col-12' : 'col-md-6') ?>">
                            <label class="form-label small fw-semibold text-muted">
                                <?= htmlspecialchars($field['label']) ?>
                            </label>

                            <?php if ($field['type'] === 'image'): ?>
                                <div class="lp-dropzone" id="dz-<?= $sectionKey ?>-<?= $field['key_name'] ?>"
                                     data-section="<?= $sectionKey ?>"
                                     data-key="<?= $field['key_name'] ?>">
                                    <div class="lp-dropzone__inner">
                                        <?php if (!empty($field['value'])): ?>
                                            <img src="<?= $val ?>" alt="preview"
                                                 class="lp-dropzone__preview" id="prev-<?= $sectionKey ?>-<?= $field['key_name'] ?>">
                                        <?php else: ?>
                                            <img src="" alt="preview" class="lp-dropzone__preview d-none"
                                                 id="prev-<?= $sectionKey ?>-<?= $field['key_name'] ?>">
                                        <?php endif; ?>
                                        <div class="lp-dropzone__placeholder" id="ph-<?= $sectionKey ?>-<?= $field['key_name'] ?>">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Drag & drop or click to upload</span>
                                            <small>JPG, PNG, WebP — max 5MB</small>
                                        </div>
                                    </div>
                                    <input type="file" class="lp-dropzone__input" accept="image/*">
                                    <div class="lp-dropzone__loading d-none">
                                        <div class="spinner-border spinner-border-sm text-primary"></div>
                                        <span>Uploading...</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label small text-muted">Or enter image URL</label>
                                    <input type="text" name="<?= $inputName ?>" value="<?= $val ?>"
                                           class="form-control form-control-sm lp-img-url"
                                           id="url-<?= $sectionKey ?>-<?= $field['key_name'] ?>"
                                           placeholder="https://...">
                                </div>

                            <?php elseif ($field['type'] === 'textarea'): ?>
                                <textarea name="<?= $inputName ?>"
                                          class="form-control form-control-sm"
                                          rows="3"><?= $val ?></textarea>

                            <?php else: ?>
                                <input type="text" name="<?= $inputName ?>"
                                       value="<?= $val ?>"
                                       class="form-control form-control-sm">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="d-flex justify-content-end mt-2 mb-5">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>Save All Changes
        </button>
    </div>
</form>

<style>
.lp-editor-icon {
    width: 32px; height: 32px;
    background: rgba(249,115,22,.12);
    color: #f97316;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}
.lp-dropzone {
    border: 2px dashed var(--bs-border-color);
    border-radius: 10px;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    position: relative;
    min-height: 160px;
    overflow: hidden;
}
.lp-dropzone:hover, .lp-dropzone.dragover {
    border-color: #f97316;
    background: rgba(249,115,22,.04);
}
.lp-dropzone__inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 158px;
    position: relative;
}
.lp-dropzone__preview {
    width: 100%; height: 158px;
    object-fit: cover;
    border-radius: 8px;
    display: block;
}
.lp-dropzone__placeholder {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 6px; padding: 20px;
    color: var(--bs-secondary-color);
    pointer-events: none;
}
.lp-dropzone__placeholder i { font-size: 2rem; color: #f97316; }
.lp-dropzone__placeholder span { font-size: .9rem; font-weight: 500; }
.lp-dropzone__placeholder small { font-size: .75rem; }
.lp-dropzone__input {
    position: absolute; inset: 0;
    opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.lp-dropzone__loading {
    position: absolute; inset: 0;
    background: rgba(255,255,255,.85);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 8px; font-size: .85rem;
    border-radius: 8px;
}
[data-bs-theme="dark"] .lp-dropzone__loading {
    background: rgba(30,30,40,.85);
}
</style>

<script>
document.querySelectorAll('.lp-dropzone').forEach(dz => {
    const section  = dz.dataset.section;
    const key      = dz.dataset.key;
    const input    = dz.querySelector('.lp-dropzone__input');
    const preview  = document.getElementById(`prev-${section}-${key}`);
    const ph       = document.getElementById(`ph-${section}-${key}`);
    const urlInput = document.getElementById(`url-${section}-${key}`);
    const loading  = dz.querySelector('.lp-dropzone__loading');

    const showPreview = url => {
        preview.src = url;
        preview.classList.remove('d-none');
        ph.classList.add('d-none');
        if (urlInput) urlInput.value = url;
    };

    const upload = file => {
        if (!file || !file.type.startsWith('image/')) return;
        const fd = new FormData();
        fd.append('image', file);
        fd.append('section', section);
        fd.append('key_name', key);
        fd.append('_csrf_token', document.querySelector('[name="_csrf_token"]').value);

        loading.classList.remove('d-none');

        fetch('/admin/landing/upload-image', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                loading.classList.add('d-none');
                if (data.success) {
                    showPreview(data.url);
                } else {
                    alert('Upload failed: ' + data.message);
                }
            })
            .catch(() => {
                loading.classList.add('d-none');
                alert('Upload error.');
            });
    };

    input.addEventListener('change', e => upload(e.target.files[0]));

    dz.addEventListener('dragover',  e => { e.preventDefault(); dz.classList.add('dragover'); });
    dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));
    dz.addEventListener('drop', e => {
        e.preventDefault();
        dz.classList.remove('dragover');
        upload(e.dataTransfer.files[0]);
    });
});
</script>
