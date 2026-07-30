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

    // ─── Toggle Password visibility ──────────────────────────────────────────
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.getElementById(btn.dataset.target);
            if (!target) return;
            var icon = btn.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                if (icon) { icon.classList.replace('fa-eye', 'fa-eye-slash'); }
            } else {
                target.type = 'password';
                if (icon) { icon.classList.replace('fa-eye-slash', 'fa-eye'); }
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

    // ═══════════════════════════════════════════════════════════════════════════
    // Admin Notification System
    // Polls /admin/notifications/unread-count every 5 seconds.
    // Loads the 15 most recent notifications into the header dropdown on demand.
    // When the unread count increases → plays bell + shows toast + reloads page.
    // ═══════════════════════════════════════════════════════════════════════════

    var lastKnownCount = null;
    var POLL_MS        = 5000;  // 5-second polling

    // ── Audio: two-tone bell chime synthesised via Web Audio API ─────────────
    function playBellSound() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
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

    // ── Update the red-dot badge ─────────────────────────────────────────────
    function updateBadge(count) {
        var dot   = document.getElementById('headerNotifDot');
        var badge = document.getElementById('adminNotifBadge');
        if (dot)   dot.style.display = count > 0 ? 'block' : 'none';
        if (badge) badge.textContent  = count > 0 ? String(count) : '';
    }

    // ── HTML helpers ─────────────────────────────────────────────────────────
    function esc(str) {
        return String(str || '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function iconForType(type) {
        return ({request:'fa-file-alt',message:'fa-comment',
                 credit:'fa-coins',payment:'fa-credit-card'})[type] || 'fa-bell';
    }

    // ── Render notifications list into dropdown ──────────────────────────────
    function renderNotifications(list, notifications) {
        if (!list) return;
        if (!notifications || notifications.length === 0) {
            list.innerHTML = '<div class="text-center p-3 text-muted small">Bildirim yok</div>';
            return;
        }
        var html = '';
        notifications.forEach(function (n) {
            var unread = parseInt(n.is_read, 10) === 0;
            var link   = esc(n.link || '#');
            html += '<a href="' + link + '" ' +
                    'class="notification-item d-flex gap-2 align-items-start px-3 py-2' +
                    (unread ? ' unread' : '') + '" data-notif-id="' + n.id + '" ' +
                    'style="text-decoration:none;color:inherit;border-bottom:1px solid rgba(0,0,0,.05);">' +
                    '<div class="notification-type-icon flex-shrink-0 mt-1">' +
                    '<i class="fas ' + iconForType(n.type) + ' fa-sm"></i></div>' +
                    '<div class="flex-grow-1 overflow-hidden">' +
                    '<div class="small fw-semibold">' + esc(n.title) + '</div>' +
                    '<div class="small text-muted" style="line-height:1.3;white-space:normal;">' + esc(n.content) + '</div>' +
                    '</div></a>';
        });
        list.innerHTML = html;

        // Mark individual item as read on click
        list.querySelectorAll('[data-notif-id]').forEach(function (el) {
            el.addEventListener('click', function () {
                el.classList.remove('unread');
                fetch('/admin/notifications/read/' + el.dataset.notifId, {
                    method:'POST', credentials:'same-origin',
                    headers:{'X-Requested-With':'XMLHttpRequest'}
                }).catch(function(){});
            });
        });
    }

    // ── Fetch and display recent notifications ────────────────────────────────
    function loadDropdownNotifications() {
        var listEl = document.getElementById('notificationList');
        if (!listEl) return;
        listEl.innerHTML = '<div class="text-center p-3 text-muted small"><i class="fas fa-spinner fa-spin me-1"></i>Yükleniyor...</div>';

        fetch('/admin/notifications/recent', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (data && data.success) {
                renderNotifications(listEl, data.notifications);
            } else {
                listEl.innerHTML = '<div class="text-center p-3 text-muted small">Yüklenemedi</div>';
            }
        })
        .catch(function () {
            listEl.innerHTML = '<div class="text-center p-3 text-muted small">Bağlantı hatası</div>';
        });
    }

    // ── Poll for new unread count ─────────────────────────────────────────────
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
                lastKnownCount = count; // Baseline on first poll
            } else if (count > lastKnownCount) {
                // New notification(s) arrived since last check
                playBellSound();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'info',
                        title: '🔔 Yeni bildiriminiz var!',
                        text: count + ' okunmamış bildirim',
                        showConfirmButton: false, timer: 4000, timerProgressBar: true
                    }).then(function () {
                        // Reload page after toast so admin sees updated state
                        window.location.reload();
                    });
                } else {
                    window.location.reload();
                }

                lastKnownCount = count;
            }

            updateBadge(count);
        })
        .catch(function () {});
    }

    // ── Attach dropdown open listeners (multiple strategies for reliability) ──
    var notifWrap = document.querySelector('.header-notification.dropdown');
    var notifBtn  = document.getElementById('notifDropdown');

    // Strategy 1: Bootstrap 5 'show.bs.dropdown' on wrapper
    if (notifWrap) {
        notifWrap.addEventListener('show.bs.dropdown', function () {
            loadDropdownNotifications();
        });
    }

    // Strategy 2: Fallback — Bootstrap 'shown.bs.dropdown' on toggle button
    if (notifBtn) {
        notifBtn.addEventListener('shown.bs.dropdown', function () {
            loadDropdownNotifications();
        });
        // Strategy 3: Plain click as last resort (slight delay so dropdown is visible)
        notifBtn.addEventListener('click', function () {
            setTimeout(loadDropdownNotifications, 80);
        });
    }

    // ── "Tümünü Okundu İşaretle" ─────────────────────────────────────────────
    var markAllRead = document.getElementById('markAllRead');
    if (markAllRead) {
        markAllRead.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent Bootstrap closing dropdown
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

    // ── Kick off polling ──────────────────────────────────────────────────────
    pollNotifications();                     // immediate first check
    setInterval(pollNotifications, POLL_MS); // then every 5 seconds
});
