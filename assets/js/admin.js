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

    var lastKnownCount  = null;
    var POLL_MS         = 30000; // 30 seconds

    /**
     * Synthesises a short two-tone bell chime using the Web Audio API.
     * No external audio file is needed.
     */
    function playBellSound() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();

            function tone(freq, startAt, duration, volume) {
                var osc  = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + startAt);
                gain.gain.setValueAtTime(volume, ctx.currentTime + startAt);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + startAt + duration);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + startAt);
                osc.stop(ctx.currentTime + startAt + duration);
            }

            tone(880,  0,    0.7, 0.45); // fundamental
            tone(1320, 0,    0.4, 0.20); // overtone
            tone(660,  0.25, 0.5, 0.25); // second note
        } catch (e) { /* AudioContext may be unavailable — safe to ignore */ }
    }

    /** Shows a SweetAlert2 toast in the bottom-right corner. */
    function showToast(count) {
        if (typeof Swal === 'undefined') return;
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'info',
            title: count + ' okunmamış bildiriminiz var',
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true
        });
    }

    /** Updates the red dot badge on the header bell icon. */
    function updateBadge(count) {
        var dot = document.getElementById('headerNotifDot');
        if (dot) dot.style.display = count > 0 ? 'block' : 'none';

        // Also update badge text if it exists (sidebar)
        var badge = document.getElementById('adminNotifBadge');
        if (badge) badge.textContent = count > 0 ? count : '';
    }

    /**
     * Renders notifications fetched from /admin/notifications/recent
     * into the header dropdown list (#notificationList).
     */
    function renderNotifications(notifications) {
        var list = document.getElementById('notificationList');
        if (!list) return;

        if (!notifications || notifications.length === 0) {
            list.innerHTML = '<div class="text-center p-3 text-muted small">Bildirim yok</div>';
            return;
        }

        var html = '';
        notifications.forEach(function (n) {
            var isUnread = n.is_read == 0 || n.is_read === '0' || n.is_read === false;
            var link     = n.link || '#';
            html += '<a href="' + link + '" class="notification-item d-flex gap-2 align-items-start' +
                    (isUnread ? ' unread' : '') + '" data-notif-id="' + n.id + '">' +
                    '<div class="notification-type-icon flex-shrink-0">' +
                    '<i class="fas ' + iconForType(n.type) + ' fa-sm"></i>' +
                    '</div>' +
                    '<div class="flex-grow-1 min-w-0">' +
                    '<div class="small fw-semibold text-truncate">' + escapeHtml(n.title) + '</div>' +
                    '<div class="small text-muted text-truncate">' + escapeHtml(n.content) + '</div>' +
                    '</div>' +
                    '</a>';
        });

        list.innerHTML = html;

        // Mark notification as read on click (without navigation change)
        list.querySelectorAll('[data-notif-id]').forEach(function (el) {
            el.addEventListener('click', function () {
                var nid = el.dataset.notifId;
                if (nid) {
                    fetch('/admin/notifications/read/' + nid, {
                        method: 'POST', credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).catch(function () {});
                    el.classList.remove('unread');
                }
            });
        });
    }

    function iconForType(type) {
        var map = {
            request: 'fa-file-alt', message: 'fa-comment', credit: 'fa-coins',
            payment: 'fa-credit-card', info: 'fa-info-circle'
        };
        return map[type] || 'fa-bell';
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /**
     * Fetches the unread count.
     * On first load: stores baseline. On subsequent polls: plays bell if count increased.
     */
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
                // First poll — just set baseline
                lastKnownCount = count;
            } else if (count > lastKnownCount) {
                // New notification(s) arrived since last poll
                playBellSound();
                showToast(count);
                loadDropdownNotifications(); // refresh the dropdown too
            }

            lastKnownCount = count;
            updateBadge(count);
        })
        .catch(function () { /* network error — retry next interval */ });
    }

    /** Loads recent notifications into the header dropdown. */
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

    // ── Load dropdown on bell click ──
    var notifDropdown = document.getElementById('notifDropdown');
    if (notifDropdown) {
        notifDropdown.addEventListener('click', function () {
            loadDropdownNotifications();
        });
    }

    // ── "Tümünü Okundu İşaretle" ──
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

    // ── Start polling ──
    pollNotifications();
    setInterval(pollNotifications, POLL_MS);

});
