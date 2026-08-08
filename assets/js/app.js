document.addEventListener('DOMContentLoaded', function() {
    // Guard: skip user-specific notification code when inside the admin panel.
    // Admin panel loads its own admin.js which handles notifications independently.
    var isAdminPanel = window.location.pathname.indexOf('/admin') === 0;
    var sidebarOpen = document.getElementById('sidebarOpen');
    var sidebarClose = document.getElementById('sidebarClose');
    var sidebar = document.getElementById('sidebar');

    if (sidebarOpen) {
        sidebarOpen.addEventListener('click', function() { sidebar.classList.add('show'); });
    }
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() { sidebar.classList.remove('show'); });
    }

    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var target = document.getElementById(btn.dataset.target);
            var icon = btn.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    var markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch('/dashboard/notifications/read-all', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function() { location.reload(); });
        });
    }

    if (!isAdminPanel) {
        var markAllRead = document.getElementById('markAllRead');
        if (markAllRead) {
            markAllRead.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('/dashboard/notifications/read-all', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(function() {
                    document.querySelectorAll('.notification-dot').forEach(function(d) { d.style.display = 'none'; });
                });
            });
        }
    }

    var msgForm = document.getElementById('messageForm');
    if (msgForm) {
        var msgAttachment = document.getElementById('msgAttachment');
        if (msgAttachment) {
            msgAttachment.addEventListener('change', function() {
                var nameEl = document.getElementById('attachmentName');
                if (nameEl) { nameEl.textContent = msgAttachment.files.length ? msgAttachment.files[0].name : ''; }
            });
        }

        msgForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(msgForm);

            fetch('/dashboard/messages/send', {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Gönderildi', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Hata', text: data.message });
                }
            });
        });
    }

    // Only run user-panel notification polling when NOT in admin panel
    if (!isAdminPanel) {
        loadNotificationCount();
        setInterval(loadNotificationCount, 60000);
        initUserNotificationDropdown();
    }
});

function loadNotificationCount() {
    fetch('/api/notifications/unread-count', { credentials: 'same-origin' })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var badge = document.getElementById('sidebarNotifBadge');
            var dot   = document.getElementById('headerNotifDot');
            if (badge) badge.textContent = data.count > 0 ? data.count : '';
            if (dot)   dot.style.display = data.count > 0 ? 'block' : 'none';
        }
    })
    .catch(function() {});
}

// ── User-panel notification dropdown ─────────────────────────────────────────
function initUserNotificationDropdown() {

    function escHtml(str) {
        return String(str || '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function iconForType(type) {
        return ({request:'fa-file-alt',message:'fa-comment',
                 credit:'fa-coins',payment:'fa-credit-card'})[type] || 'fa-bell';
    }

    function renderUserNotifications(notifications) {
        var list = document.getElementById('notificationList');
        if (!list) return;

        if (!notifications || notifications.length === 0) {
            list.innerHTML = '<div class="text-center p-3 text-muted small">Bildirim yok</div>';
            return;
        }

        var html = '';
        notifications.forEach(function(n) {
            var unread = parseInt(n.is_read, 10) === 0;
            var link   = escHtml(n.link || '#');
            html += '<a href="' + link + '" ' +
                    'class="notification-item d-flex gap-2 align-items-start px-3 py-2' +
                    (unread ? ' unread' : '') + '" data-notif-id="' + n.id + '" ' +
                    'style="text-decoration:none;color:inherit;border-bottom:1px solid rgba(0,0,0,.05);">' +
                    '<div class="notification-type-icon flex-shrink-0 mt-1">' +
                    '<i class="fas ' + iconForType(n.type) + ' fa-sm"></i></div>' +
                    '<div class="flex-grow-1 overflow-hidden">' +
                    '<div class="small fw-semibold">' + escHtml(n.title) + '</div>' +
                    '<div class="small text-muted" style="line-height:1.3;white-space:normal;">' + escHtml(n.content) + '</div>' +
                    '</div></a>';
        });
        list.innerHTML = html;

        // Mark individual item as read on click
        list.querySelectorAll('[data-notif-id]').forEach(function(el) {
            el.addEventListener('click', function() {
                el.classList.remove('unread');
                fetch('/dashboard/notifications/read/' + el.dataset.notifId, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).catch(function(){});
            });
        });
    }

    function loadUserDropdown() {
        var list = document.getElementById('notificationList');
        if (!list) return;
        list.innerHTML = '<div class="text-center p-3 text-muted small"><i class="fas fa-spinner fa-spin me-1"></i>Yükleniyor...</div>';

        fetch('/api/notifications/recent', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.ok ? r.json() : null; })
        .then(function(data) {
            if (data && data.success) {
                renderUserNotifications(data.notifications);
            } else {
                list.innerHTML = '<div class="text-center p-3 text-muted small">Yüklenemedi</div>';
            }
        })
        .catch(function() {
            list.innerHTML = '<div class="text-center p-3 text-muted small">Bağlantı hatası</div>';
        });
    }

    // Three-strategy dropdown open detection (mirrors admin.js approach)
    var notifWrap = document.querySelector('.header-notification.dropdown');
    var notifBtn  = document.getElementById('notifDropdown');

    if (notifWrap) {
        notifWrap.addEventListener('show.bs.dropdown', function() { loadUserDropdown(); });
    }
    if (notifBtn) {
        notifBtn.addEventListener('shown.bs.dropdown', function() { loadUserDropdown(); });
        notifBtn.addEventListener('click', function() { setTimeout(loadUserDropdown, 80); });
    }

    // "Tümünü Okundu İşaretle" — user endpoint
    var markAllRead = document.getElementById('markAllRead');
    if (markAllRead) {
        // Remove any duplicate listeners by cloning
        var fresh = markAllRead.cloneNode(true);
        markAllRead.parentNode.replaceChild(fresh, markAllRead);
        fresh.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fetch('/dashboard/notifications/read-all', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function() {
                document.querySelectorAll('.notification-dot').forEach(function(d) { d.style.display = 'none'; });
                var badge = document.getElementById('sidebarNotifBadge');
                if (badge) badge.textContent = '';
                loadUserDropdown();
            })
            .catch(function(){});
        });
    }
}

function initCreateRequest() {
    var brandSelect = document.getElementById('brand_id');
    var modelSelect = document.getElementById('model_id');
    var genSelect = document.getElementById('generation_id');
    var engineSelect = document.getElementById('engine_id');
    var ecuSelect = document.getElementById('ecu_id');

    function resetFrom(level) {
        if (level <= 1) { modelSelect.innerHTML = '<option value="">Önce marka seçin</option>'; modelSelect.disabled = true; }
        if (level <= 2) { genSelect.innerHTML = '<option value="">Önce model seçin</option>'; genSelect.disabled = true; }
        if (level <= 3) { engineSelect.innerHTML = '<option value="">Önce jenerasyon seçin</option>'; engineSelect.disabled = true; }
        if (level <= 4) { ecuSelect.innerHTML = '<option value="">Önce motor seçin</option>'; ecuSelect.disabled = true; }
    }

    if (brandSelect) {
        brandSelect.addEventListener('change', function() {
            var brandId = this.value;
            resetFrom(1);
            if (!brandId) return;
            modelSelect.innerHTML = '<option value="">Yükleniyor...</option>';

            fetch('/api/vehicles/models/' + brandId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                modelSelect.innerHTML = '<option value="">Seçin...</option>';
                data.data.forEach(function(m) { modelSelect.innerHTML += '<option value="'+m.id+'">'+m.name+'</option>'; });
                modelSelect.disabled = false;
            });
        });
    }

    if (modelSelect) {
        modelSelect.addEventListener('change', function() {
            var modelId = this.value;
            resetFrom(2);
            if (!modelId) return;
            genSelect.innerHTML = '<option value="">Yükleniyor...</option>';

            fetch('/api/vehicles/generations/' + modelId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                genSelect.innerHTML = '<option value="">Seçin...</option>';
                data.data.forEach(function(g) { genSelect.innerHTML += '<option value="'+g.id+'">'+g.name+'</option>'; });
                genSelect.disabled = false;
            });
        });
    }

    if (genSelect) {
        genSelect.addEventListener('change', function() {
            var genId = this.value;
            resetFrom(3);
            if (!genId) return;
            engineSelect.innerHTML = '<option value="">Yükleniyor...</option>';

            fetch('/api/vehicles/engines/' + genId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                engineSelect.innerHTML = '<option value="">Seçin...</option>';
                data.data.forEach(function(e) {
                    var label = e.name;
                    if (e.displacement) label += ' (' + e.displacement + ')';
                    if (e.horsepower) label += ' ' + e.horsepower + 'HP';
                    engineSelect.innerHTML += '<option value="'+e.id+'">'+label+'</option>';
                });
                engineSelect.disabled = false;
            });
        });
    }

    if (engineSelect) {
        engineSelect.addEventListener('change', function() {
            var engineId = this.value;
            resetFrom(4);
            if (!engineId) return;
            ecuSelect.innerHTML = '<option value="">Yükleniyor...</option>';

            fetch('/api/vehicles/ecus?engine_id=' + engineId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                ecuSelect.innerHTML = '<option value="">Seçin...</option>';
                data.data.forEach(function(ecu) { ecuSelect.innerHTML += '<option value="'+ecu.id+'">'+ecu.name+'</option>'; });
                ecuSelect.disabled = false;
            });
        });
    }

    var stagePricingData = null;
    /** @type {Map<number, {baseCredit: number, slug: string, showServices: number}>} */
    var selectedStages = new Map(); // stageId -> {baseCredit, slug, showServices}

    fetch('/api/stages/pricing')
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            stagePricingData = data;
            initStageButtons();
        }
    });

    /**
     * Seçili stage ID'lerinden toplam base kredi hesaplar.
     * @returns {number}
     */
    function getTotalBaseCredit() {
        var total = 0;
        selectedStages.forEach(function(info) { total += info.baseCredit; });
        return total;
    }

    /**
     * stageIdsContainer'daki hidden input'ları günceller.
     * Her seçili stage için name="stage_ids[]" hidden input oluşturur.
     */
    function syncStageInputs() {
        var container = document.getElementById('stageIdsContainer');
        if (!container) return;
        container.innerHTML = '';
        selectedStages.forEach(function(info, id) {
            var inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'stage_ids[]';
            inp.value = id;
            container.appendChild(inp);
        });
    }

    /**
     * Seçili stage'leri servicesSection başlığında badge olarak gösterir.
     * Kullanıcı hangi ana işlemi seçtiğini açıkça görebilir.
     */
    function renderSelectedStageSummary() {
        var summaryEl = document.getElementById('selectedStageSummary');
        if (!summaryEl) return;

        if (selectedStages.size === 0) {
            summaryEl.style.display = 'none';
            summaryEl.innerHTML = '';
            return;
        }

        var html = '<div class="selected-stage-badges">';
        selectedStages.forEach(function(info) {
            html += '<span class="selected-stage-badge">' +
                '<i class="fas fa-check-circle"></i> ' +
                escapeHtml(info.name) +
                ' <span class="selected-stage-badge-credit">(' + info.baseCredit + ' Kredi)</span>' +
                '</span>';
        });
        html += '</div>';

        summaryEl.innerHTML = html;
        summaryEl.style.display = 'block';
    }

    /**
     * Servis grid'ini günceller: seçili tüm stage'lerin servislerini birleştirir.
     */
    function renderCombinedServices() {
        var servicesSection  = document.getElementById('servicesSection');
        var originalNotice   = document.getElementById('originalFileNotice');
        var hasOriginalFile  = false;
        var hasServicesStage = false;
        var combinedServices = {};

        selectedStages.forEach(function(info, id) {
            if (info.slug === 'original-file') {
                hasOriginalFile = true;
            } else if (info.showServices === 1) {
                hasServicesStage = true;
                var services = (stagePricingData.pricing && stagePricingData.pricing[id]) || [];
                services.forEach(function(svc) {
                    if (!combinedServices[svc.id]) {
                        combinedServices[svc.id] = svc;
                    }
                });
            }
        });

        renderSelectedStageSummary();

        if (selectedStages.size === 0) {
            servicesSection.style.display  = 'none';
            originalNotice.style.display   = 'none';
            renderServices([]);
            return;
        }

        if (hasOriginalFile && !hasServicesStage) {
            servicesSection.style.display  = 'none';
            originalNotice.style.display   = 'block';
            renderServices([]);
        } else if (hasServicesStage) {
            servicesSection.style.display  = 'block';
            originalNotice.style.display   = hasOriginalFile ? 'block' : 'none';
            renderServices(Object.values(combinedServices));
        } else {
            servicesSection.style.display  = 'none';
            originalNotice.style.display   = 'none';
            renderServices([]);
        }
    }

    function initStageButtons() {
        var stageBtns = document.querySelectorAll('.stage-btn');
        var moreBtn   = document.getElementById('moreOptionsBtn');

        /**
         * Tüm stage butonlarının disabled/enabled durumunu günceller.
         *
         * Kural 1: "only-options" seçiliyse → diğer tüm butonlar disabled.
         * Kural 2: Başka herhangi bir stage seçiliyse → "only-options" butonu disabled.
         * Kural 3: Stage 1, 2, 3 birlikte seçilebilir.
         */
        function updateStageButtonStates() {
            var onlyOptionsSelected = false;
            var otherStageSelected  = false;

            selectedStages.forEach(function(info) {
                if (info.slug === 'only-options') {
                    onlyOptionsSelected = true;
                } else {
                    otherStageSelected = true;
                }
            });

            // Her butonu kontrol et
            stageBtns.forEach(function(b) {
                var bSlug = b.dataset.slug;
                if (onlyOptionsSelected && bSlug !== 'only-options') {
                    b.disabled = true;
                    b.classList.add('stage-btn-disabled');
                } else if (otherStageSelected && bSlug === 'only-options') {
                    b.disabled = true;
                    b.classList.add('stage-btn-disabled');
                } else {
                    b.disabled = false;
                    b.classList.remove('stage-btn-disabled');
                }
            });

            // moreOptionsBtn (more-options slug) için aynı kural
            if (moreBtn) {
                var moreBtnSlug = moreBtn.dataset.slug || '';
                if (onlyOptionsSelected && moreBtnSlug !== 'only-options') {
                    moreBtn.disabled = true;
                    moreBtn.classList.add('stage-btn-disabled');
                } else if (otherStageSelected && moreBtnSlug === 'only-options') {
                    moreBtn.disabled = true;
                    moreBtn.classList.add('stage-btn-disabled');
                } else {
                    moreBtn.disabled = false;
                    moreBtn.classList.remove('stage-btn-disabled');
                }
            }
        }

        stageBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var stageId      = parseInt(btn.dataset.stageId);
                var baseCredit   = parseInt(btn.dataset.baseCredit);
                var showServices = parseInt(btn.dataset.showServices);
                var slug         = btn.dataset.slug;
                var name         = btn.querySelector('.stage-btn-name')
                                    ? btn.querySelector('.stage-btn-name').textContent.trim()
                                    : slug;

                // Toggle: seçiliyse kaldır, değilse ekle
                if (selectedStages.has(stageId)) {
                    selectedStages.delete(stageId);
                    btn.classList.remove('active');
                } else {
                    selectedStages.set(stageId, { baseCredit: baseCredit, slug: slug, showServices: showServices, name: name });
                    btn.classList.add('active');
                }

                updateStageButtonStates();
                syncStageInputs();
                renderCombinedServices();
                updateCreditDisplay();
                updateDropzoneState();
            });
        });

        if (moreBtn) {
            moreBtn.addEventListener('click', function() {
                var stageId      = parseInt(moreBtn.dataset.stageId);
                var baseCredit   = parseInt(moreBtn.dataset.baseCredit);
                var slug         = moreBtn.dataset.slug;
                var showServices = parseInt(moreBtn.dataset.showServices);
                var name         = Array.from(moreBtn.childNodes)
                                    .filter(function(n) { return n.nodeType === Node.TEXT_NODE; })
                                    .map(function(n) { return n.textContent.trim(); })
                                    .filter(Boolean).join(' ') || slug;

                if (selectedStages.has(stageId)) {
                    selectedStages.delete(stageId);
                    moreBtn.classList.remove('active');
                } else {
                    selectedStages.set(stageId, { baseCredit: baseCredit, slug: slug, showServices: showServices, name: name });
                    moreBtn.classList.add('active');
                }

                updateStageButtonStates();
                syncStageInputs();
                renderCombinedServices();
                updateCreditDisplay();
                updateDropzoneState();
            });
        }
    }

    function renderServices(services) {
        var grid = document.getElementById('servicesGrid');
        if (!grid) return;
        grid.innerHTML = '';

        services.forEach(function(svc) {
            if (!svc.is_visible) return;
            var div = document.createElement('div');
            div.className = 'service-grid-item';
            div.innerHTML =
                '<label class="service-grid-label">' +
                    '<input type="checkbox" name="services[]" value="' + svc.id + '" class="service-radio" data-cost="' + svc.credit_cost + '">' +
                    '<span class="service-grid-text">' + escapeHtml(svc.name) + ' (' + svc.credit_cost + ' Kredi)</span>' +
                '</label>';
            grid.appendChild(div);
        });

        grid.querySelectorAll('.service-radio').forEach(function(cb) {
            cb.addEventListener('change', function() {
                updateCreditDisplay();
                updateDropzoneState();
            });
        });

        // After rendering new services, re-check dropzone state
        updateDropzoneState();
    }

    /**
     * Enables or disables the Dropzone upload area based on stage selection.
     * Upload unlocks as soon as ANY stage is selected — service checkbox selection
     * is optional and does not gate the upload area.
     */
    function updateDropzoneState() {
        var dropzoneEl   = document.getElementById('fileDropzone');
        var dropzoneHint = document.getElementById('dropzoneHint');
        if (!dropzoneEl) return;

        // Any stage selected → unlock
        var allowed = selectedStages.size > 0;

        dropzoneEl.classList.toggle('dropzone-locked', !allowed);
        dropzoneEl.style.pointerEvents = allowed ? '' : 'none';
        dropzoneEl.style.opacity       = allowed ? '' : '0.45';
        dropzoneEl.title               = allowed ? '' : 'Lütfen önce servis türünü seçin.';

        if (dropzoneHint) {
            dropzoneHint.style.display = allowed ? 'none' : 'block';
        }
    }

    function updateCreditDisplay() {
        var serviceTotal = 0;
        document.querySelectorAll('.service-radio:checked').forEach(function(cb) {
            serviceTotal += parseInt(cb.dataset.cost || 0);
        });

        var total = getTotalBaseCredit() + serviceTotal;

        var totalVal = document.getElementById('totalCreditValue');
        var remainEl = document.getElementById('remainingBalance');
        var alertEl = document.getElementById('insufficientAlert');
        var submitBtn = document.getElementById('submitBtn');
        var remaining = creditBalance - total;

        if (totalVal) totalVal.textContent = total + '.0';
        if (remainEl) {
            remainEl.textContent = remaining + ' Kr';
            remainEl.style.color = remaining < 0 ? '#ef4444' : '';
        }
        if (alertEl) alertEl.style.display = remaining < 0 ? 'block' : 'none';
        if (submitBtn) submitBtn.disabled = remaining < 0 || selectedStages.size === 0;
    }

    function escapeHtml(text) {
        var d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    if (typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        var dz = document.getElementById('fileDropzone');
        if (dz) {
            new Dropzone('#fileDropzone', {
                url: '/api/files/upload',
                maxFilesize: 20,
                maxFiles: 5,
                acceptedFiles: '.rar',
                addRemoveLinks: true,
                dictRemoveFile: 'Kaldır',
                dictCancelUpload: 'İptal',
                dictMaxFilesExceeded: 'Maksimum dosya sayısına ulaşıldı.',
                dictInvalidFileType: 'Sadece .rar dosyası yüklenebilir.',
                dictFileTooBig: 'Dosya çok büyük ({{filesize}} MB). Maksimum izin verilen: {{maxFilesize}} MB.',
                init: function() {
                    this.on('error', function(file, errorMessage) {
                        var msg = typeof errorMessage === 'string'
                            ? errorMessage
                            : 'Dosya yüklenemedi. Lütfen geçerli bir .rar dosyası seçin.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Geçersiz Dosya Türü',
                            text: msg,
                            confirmButtonText: 'Tamam',
                            confirmButtonColor: '#0ea5e9',
                        });

                        this.removeFile(file);
                    });

                    this.on('success', function(file, response) {
                        if (response.success) {
                            file.serverFilename = response.filename;
                        }
                    });

                    this.on('removedfile', function(file) {
                        if (file.serverFilename) {
                            fetch('/api/files/delete', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ filename: file.serverFilename })
                            });
                        }
                    });
                }
            });
        }
    }
}

