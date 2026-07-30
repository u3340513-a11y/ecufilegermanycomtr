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

    // ─── Admin Notification System ───────────────────────────────────────────

    var lastKnownCount = null;
    var POLL_MS        = 5000; // Poll every 5 seconds

    // ── Audio ────────────────────────────────────────────────────────────────
    function playBellSound() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            function tone(freq, startAt, dur, vol) {
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + startAt);
                gain.gain.setValueAtTime(vol, ctx.currentTime + startAt);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + startAt + dur);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + startAt);
                osc.stop(ctx.currentTime + startAt + dur);
            }
            tone(880,  0,    0.7, 0.45);
            tone(1320, 0,    0.4, 0.20);
            tone(660,  0.30, 0.5, 0.25);
        } catch (e) {}
    }

    // ── Toast ─────────────────────────────────────────────────────────────────
    function showToast(count) {
        if (typeof Swal === 'undefined') return;
        Swal.fire({
            toast: true, position: 'bottom-end', icon: 'info',
            title: count + ' okunmamış bildiriminiz var',
            showConfirmButton: false, timer: 6000, timerProgressBar: true
        });
    }

    // ── Badge ─────────────────────────────────────────────────────────────────
    function updateBadge(count) {
        var dot   = document.getElementById('headerNotifDot');
        var badge = document.getElementById('adminNotifBadge');
        if (dot)   dot.style.display   = count > 0 ? 'block' : 'none';
        if (badge) badge.textContent   = count > 0 ? String(count) : '';
    }

    // ── HTML helpers ──────────────────────────────────────────────────────────
    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function iconForType(type) {
        return { request:'fa-file-alt', message:'fa-comment',
                 credit:'fa-coins', payment:'fa-credit-card' }[type] || 'fa-bell';
    }

    // ── Render notifications into dropdown ────────────────────────────────────
    function renderNotifications(notifications) {
        var list = document.getElementById('notificationList');
        if (!list) return;

        if (!notifications || notifications.length === 0) {
            list.innerHTML = '<div class="text-center p-3 text-muted small">Bildirim yok</div>';
            return;
        }

        var html = '';
        notifications.forEach(function (n) {
            var unread = parseInt(n.is_read, 10) === 0;
            var link   = escapeHtml(n.link || '#');
            html += '<a href="' + link + '" ' +
                    'class="notification-item d-flex gap-2 align-items-start px-3 py-2' +
                    (unread ? ' unread' : '') + '" ' +
                    'data-notif-id="' + n.id + '" style="text-decoration:none;">' +
                    '<div class="notification-type-icon flex-shrink-0 mt-1">' +
                    '<i class="fas ' + iconForType(n.type) + ' fa-sm"></i>' +
                    '</div>' +
                    '<div class="flex-grow-1 overflow-hidden">' +
                    '<div class="small fw-semibold text-truncate">' + escapeHtml(n.title) + '</div>' +
                    '<div class="small text-muted" style="white-space:normal;line-height:1.3;">' + escapeHtml(n.content) + '</div>' +
                    '</div>' +
                    '</a>';
        });
        list.innerHTML = html;

        // Mark individual notification as read on click
        list.querySelectorAll('[data-notif-id]').forEach(function (el) {
            el.addEventListener('click', function () {
                el.classList.remove('unread');
                fetch('/admin/notifications/read/' + el.dataset.notifId, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).catch(function () {});
            });
        });
    }

    // ── Fetch and display recent notifications ────────────────────────────────
    function loadDropdownNotifications() {
        fetch('/admin/notifications/recent', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (data && data.success) renderNotifications(data.notifications);
        })
        .catch(function () {});
    }

    // ── Poll for new notifications ────────────────────────────────────────────
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
                // First poll — record baseline, no alert
                lastKnownCount = count;
            } else if (count > lastKnownCount) {
                // New notification(s) arrived
                playBellSound();
                showToast(count);
                loadDropdownNotifications();
                lastKnownCount = count;
            }

            updateBadge(count);
        })
        .catch(function () {});
    }

    // ── Open dropdown: load via Bootstrap "show" event (avoids click conflict) ─
    var notifWrap = document.querySelector('.header-notification.dropdown');
    if (notifWrap) {
        // Bootstrap 5 fires this just before the dropdown opens
        notifWrap.addEventListener('show.bs.dropdown', function () {
            loadDropdownNotifications();
        });
    }

    // ── "Tümünü Okundu İşaretle" ─────────────────────────────────────────────
    var markAllRead = document.getElementById('markAllRead');
    if (markAllRead) {
        markAllRead.addEventListener('click', function (e) {
            e.preventDefault();
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

    // ── Kick off ──────────────────────────────────────────────────────────────
    pollNotifications();                    // immediate first check
    setInterval(pollNotifications, POLL_MS); // then every 5 s
});
