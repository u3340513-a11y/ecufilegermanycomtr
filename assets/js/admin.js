'use strict';

document.addEventListener('DOMContentLoaded', function () {

    // ─── DataTables ──────────────────────────────────────────────────────────
    if (typeof $.fn.DataTable !== 'undefined') {
        var tables = ['#usersTable', '#faultCodesTable', '#boschTable', '#logsTable'];
        tables.forEach(function (selector) {
            var el = document.querySelector(selector);
            if (el && el.querySelector('tbody tr td')) {
                $(selector).DataTable({
                    language: {
                        search: 'Ara:', lengthMenu: '_MENU_ kayıt göster',
                        info: '_TOTAL_ kayıttan _START_ - _END_ arası',
                        paginate: { previous: '‹', next: '›' },
                        zeroRecords: 'Kayıt bulunamadı', emptyTable: 'Henüz kayıt yok'
                    },
                    pageLength: 25, order: [], responsive: true
                });
            }
        });
    }

    // ─── Toggle Password ─────────────────────────────────────────────────────
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.getElementById(btn.dataset.target);
            if (!target) return;
            var icon = btn.querySelector('i');
            target.type = target.type === 'password' ? 'text' : 'password';
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    });

    // ─── Delete Confirmation ─────────────────────────────────────────────────
    document.querySelectorAll('form[onsubmit]').forEach(function (form) {
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Emin misiniz?', text: 'Bu işlem geri alınamaz.',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Evet, sil', cancelButtonText: 'İptal'
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // ═══════════════════════════════════════════════════════════════════════
    // ADMIN NOTIFICATION SYSTEM
    // ═══════════════════════════════════════════════════════════════════════

    var lastKnownCount = null;
    var POLL_MS        = 5000;

    // ── AudioContext: must be created/resumed after a user gesture ───────────
    // Browsers block AudioContext.resume() until user has interacted with page.
    // We pre-warm it on the first click anywhere, so the bell works later.
    var audioCtx = null;

    function getAudioCtx() {
        if (!audioCtx) {
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            } catch (e) { return null; }
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume().catch(function () {});
        }
        return audioCtx;
    }

    // Pre-warm on first user click
    document.addEventListener('click', function warmAudio() {
        getAudioCtx();
        document.removeEventListener('click', warmAudio);
    }, { once: true });

    function playBellSound() {
        var ctx = getAudioCtx();
        if (!ctx) return;
        try {
            function tone(freq, delay, dur, vol) {
                var osc  = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + delay);
                gain.gain.setValueAtTime(vol,  ctx.currentTime + delay);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + delay + dur);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + delay);
                osc.stop(ctx.currentTime  + delay + dur);
            }
            tone(880,  0,    0.7, 0.45);
            tone(1320, 0,    0.4, 0.20);
            tone(660,  0.30, 0.5, 0.25);
        } catch (e) {}
    }

    // ── Badge ────────────────────────────────────────────────────────────────
    function updateBadge(count) {
        var dot   = document.getElementById('headerNotifDot');
        var badge = document.getElementById('adminNotifBadge');
        if (dot)   dot.style.display = count > 0 ? 'block' : 'none';
        if (badge) badge.textContent  = count > 0 ? String(count) : '';
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    function esc(str) {
        return String(str || '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function iconForType(type) {
        return ({
            request: 'fa-file-alt', message: 'fa-comment',
            credit:  'fa-coins',    payment: 'fa-credit-card'
        })[type] || 'fa-bell';
    }

    // ── Render notifications into #notificationList ───────────────────────────
    function renderNotifications(notifications) {
        var listEl = document.getElementById('notificationList');
        if (!listEl) return;

        if (!notifications || notifications.length === 0) {
            listEl.innerHTML = '<div class="text-center p-3 text-muted small">Bildirim yok</div>';
            return;
        }

        var html = '';
        notifications.forEach(function (n) {
            var unread = parseInt(n.is_read, 10) === 0;
            var link   = esc(n.link || '#');
            html +=
                '<a href="' + link + '" ' +
                'class="notification-item d-flex gap-2 align-items-start px-3 py-2' +
                (unread ? ' unread' : '') + '" ' +
                'data-notif-id="' + n.id + '" ' +
                'style="text-decoration:none;color:inherit;border-bottom:1px solid rgba(0,0,0,.06);">' +
                '<div class="notification-type-icon flex-shrink-0 mt-1">' +
                '<i class="fas ' + iconForType(n.type) + ' fa-sm"></i>' +
                '</div>' +
                '<div class="flex-grow-1 overflow-hidden">' +
                '<div class="small fw-semibold">' + esc(n.title) + '</div>' +
                '<div class="small text-muted" style="line-height:1.3;white-space:normal;">' +
                esc(n.content) + '</div>' +
                '</div>' +
                '</a>';
        });
        listEl.innerHTML = html;

        listEl.querySelectorAll('[data-notif-id]').forEach(function (el) {
            el.addEventListener('click', function () {
                el.classList.remove('unread');
                fetch('/admin/notifications/read/' + el.dataset.notifId, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).catch(function () {});
            });
        });
    }

    // ── Fetch and populate dropdown ───────────────────────────────────────────
    function loadDropdownNotifications() {
        var listEl = document.getElementById('notificationList');
        if (listEl) {
            listEl.innerHTML =
                '<div class="text-center p-3 text-muted small">' +
                '<i class="fas fa-spinner fa-spin me-1"></i>Yükleniyor...</div>';
        }

        fetch('/admin/notifications/recent', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (data && data.success) {
                renderNotifications(data.notifications);
            } else if (listEl) {
                listEl.innerHTML =
                    '<div class="text-center p-3 text-muted small">Yüklenemedi</div>';
            }
        })
        .catch(function () {
            if (listEl) {
                listEl.innerHTML =
                    '<div class="text-center p-3 text-muted small">Bağlantı hatası</div>';
            }
        });
    }

    // ── Poll unread count ─────────────────────────────────────────────────────
    function pollNotifications() {
        fetch('/admin/notifications/unread-count', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (!data || !data.success) return;
            var count = parseInt(data.count, 10);

            if (lastKnownCount === null) {
                lastKnownCount = count; // Baseline — no alert on first load
            } else if (count > lastKnownCount) {
                // New notification(s) since last poll
                lastKnownCount = count;
                playBellSound();
                loadDropdownNotifications(); // Refresh dropdown immediately

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'info',
                        title: '🔔 Yeni bildiriminiz var!',
                        text: count + ' okunmamış bildirim',
                        showConfirmButton: false, timer: 5000, timerProgressBar: true
                    }).then(function () { window.location.reload(); });
                } else {
                    window.location.reload();
                }
            }

            updateBadge(count);
        })
        .catch(function () {});
    }

    // ── Dropdown open: three strategies ──────────────────────────────────────
    var notifWrap = document.querySelector('.header-notification.dropdown');
    var notifBtn  = document.getElementById('notifDropdown');

    if (notifWrap) {
        notifWrap.addEventListener('show.bs.dropdown', loadDropdownNotifications);
    }
    if (notifBtn) {
        notifBtn.addEventListener('shown.bs.dropdown', loadDropdownNotifications);
        notifBtn.addEventListener('click', function () {
            setTimeout(loadDropdownNotifications, 100);
        });
    }

    // ── "Tümünü Okundu İşaretle" ─────────────────────────────────────────────
    var markAllRead = document.getElementById('markAllRead');
    if (markAllRead) {
        // Clone to remove any duplicate listeners from app.js
        var freshBtn = markAllRead.cloneNode(true);
        markAllRead.parentNode.replaceChild(freshBtn, markAllRead);

        freshBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            fetch('/admin/notifications/read-all', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function () {
                lastKnownCount = 0;
                updateBadge(0);
                loadDropdownNotifications();
            })
            .catch(function () {});
        });
    }

    // ── Pre-populate dropdown on page load so first click is instant ──────────
    loadDropdownNotifications();

    // ── Start polling ─────────────────────────────────────────────────────────
    pollNotifications();
    setInterval(pollNotifications, POLL_MS);
});
