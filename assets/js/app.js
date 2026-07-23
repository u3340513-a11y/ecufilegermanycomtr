document.addEventListener('DOMContentLoaded', function() {
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

    loadNotificationCount();
    setInterval(loadNotificationCount, 60000);
});

function loadNotificationCount() {
    fetch('/api/notifications/unread-count')
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var badge = document.getElementById('sidebarNotifBadge');
            var dot = document.getElementById('headerNotifDot');
            if (badge) { badge.textContent = data.count > 0 ? data.count : ''; }
            if (dot) { dot.style.display = data.count > 0 ? 'block' : 'none'; }
        }
    })
    .catch(function() {});
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
    var currentStageId = null;
    var currentBaseCredit = 0;

    fetch('/api/stages/pricing')
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            stagePricingData = data;
            initStageButtons();
        }
    });

    function initStageButtons() {
        var stageBtns = document.querySelectorAll('.stage-btn');
        var moreBtn = document.getElementById('moreOptionsBtn');

        stageBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                stageBtns.forEach(function(b) { b.classList.remove('active'); });
                if (moreBtn) moreBtn.classList.remove('active');
                btn.classList.add('active');

                currentStageId = parseInt(btn.dataset.stageId);
                currentBaseCredit = parseInt(btn.dataset.baseCredit);
                var showServices = parseInt(btn.dataset.showServices);
                var slug = btn.dataset.slug;

                document.getElementById('stageIdInput').value = currentStageId;

                var servicesSection = document.getElementById('servicesSection');
                var originalNotice = document.getElementById('originalFileNotice');

                if (slug === 'original-file') {
                    servicesSection.style.display = 'none';
                    originalNotice.style.display = 'block';
                    renderServices([]);
                } else if (showServices === 1) {
                    servicesSection.style.display = 'block';
                    originalNotice.style.display = 'none';
                    var services = stagePricingData.pricing[currentStageId] || [];
                    renderServices(services);
                } else {
                    servicesSection.style.display = 'none';
                    originalNotice.style.display = 'none';
                    renderServices([]);
                }

                updateCreditDisplay();
            });
        });

        if (moreBtn) {
            moreBtn.addEventListener('click', function() {
                stageBtns.forEach(function(b) { b.classList.remove('active'); });
                moreBtn.classList.toggle('active');

                if (moreBtn.classList.contains('active')) {
                    currentStageId = parseInt(moreBtn.dataset.stageId);
                    currentBaseCredit = parseInt(moreBtn.dataset.baseCredit);
                    document.getElementById('stageIdInput').value = currentStageId;

                    var services = stagePricingData.pricing[currentStageId] || [];
                    document.getElementById('servicesSection').style.display = 'block';
                    document.getElementById('originalFileNotice').style.display = 'none';
                    renderServices(services);
                } else {
                    currentStageId = null;
                    currentBaseCredit = 0;
                    document.getElementById('stageIdInput').value = '';
                    document.getElementById('servicesSection').style.display = 'none';
                    renderServices([]);
                }

                updateCreditDisplay();
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
            cb.addEventListener('change', updateCreditDisplay);
        });
    }

    function updateCreditDisplay() {
        var serviceTotal = 0;
        document.querySelectorAll('.service-radio:checked').forEach(function(cb) {
            serviceTotal += parseInt(cb.dataset.cost || 0);
        });

        var total = currentBaseCredit + serviceTotal;

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
        if (submitBtn) submitBtn.disabled = remaining < 0 || !currentStageId;
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
                maxFilesize: 10,
                maxFiles: 5,
                acceptedFiles: '.bin,.ori,.mod,.zip,.rar,.7z',
                addRemoveLinks: true,
                dictRemoveFile: 'Kaldır',
                dictCancelUpload: 'İptal',
                dictMaxFilesExceeded: 'Maksimum dosya sayısına ulaşıldı.',
                init: function() {
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

