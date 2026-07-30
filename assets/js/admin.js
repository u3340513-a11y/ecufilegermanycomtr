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
            target.type = target.type === 'password' ? 'text' : 'password';
            var icon = btn.querySelector('i');
            if (icon) { icon.classList.toggle('fa-eye'); icon.classList.toggle('fa-eye-slash'); }
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
            }).then(function (result) { if (result.isConfirmed) form.submit(); });
        });
    });

    // ═══════════════════════════════════════════════════════════════════════════
    //  ADMIN NOTIFICATION SYSTEM
    //
    //  The dropdown is now rendered SERVER-SIDE in admin-header.php.
    //  This JS only handles:
    //    1. Polling unread count every 5 seconds
    //    2. Playing a bell sound when new notifications arrive
    //    3. Reloading the page so the server-side dropdown refreshes
    //    4. "Tümünü Okundu İşaretle" button via AJAX
    //    5. Individual notification mark-as-read on click
    // ═══════════════════════════════════════════════════════════════════════════

    var lastKnownCount = null;
    var POLL_MS        = 5000; // 5 seconds

    // ── AudioContext (pre-warm on first user gesture to satisfy autoplay policy) ──
    var audioCtx = null;
    function ensureAudioCtx() {
        if (!audioCtx) {
            try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }
            catch (e) { return null; }
        }
        if (audioCtx.state === 'suspended') { audioCtx.resume().catch(function(){}); }
        return audioCtx;
    }
    document.addEventListener('click', function warmUp() {
        ensureAudioCtx();
        document.removeEventListener('click', warmUp);
    }, { once: true });

    function playBellSound() {
        var ctx = ensureAudioCtx();
        if (!ctx) return;
        try {
            function t(f, d, dur, v) {
                var o = ctx.createOscillator(), g = ctx.createGain();
                o.type = 'sine';
                o.frequency.setValueAtTime(f, ctx.currentTime + d);
                g.gain.setValueAtTime(v, ctx.currentTime + d);
                g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + d + dur);
                o.connect(g); g.connect(ctx.destination);
                o.start(ctx.currentTime + d); o.stop(ctx.currentTime + d + dur);
            }
            t(880, 0, 0.7, 0.45);
            t(1320, 0, 0.4, 0.2);
            t(660, 0.3, 0.5, 0.25);
        } catch (e) {}
    }

    // ── Poll for new unread count ─────────────────────────────────────────────
    function poll() {
        fetch('/admin/notifications/unread-count', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (!data || !data.success) return;
            var count = parseInt(data.count, 10);

            // First poll — just store the baseline
            if (lastKnownCount === null) {
                lastKnownCount = count;
                return;
            }

            // New notification(s) arrived
            if (count > lastKnownCount) {
                lastKnownCount = count;
                playBellSound();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'info',
                        title: '🔔 Yeni bildiriminiz var!',
                        text: count + ' okunmamış bildirim',
                        showConfirmButton: false, timer: 3000, timerProgressBar: true
                    }).then(function () {
                        // Reload page to refresh server-rendered dropdown
                        window.location.reload();
                    });
                } else {
                    window.location.reload();
                }
            }
        })
        .catch(function () {});
    }

    // ── "Tümünü Okundu İşaretle" ──────────────────────────────────────────────
    var markAllBtn = document.getElementById('markAllRead');
    if (markAllBtn) {
        // Clone to strip any listeners from app.js
        var freshBtn = markAllBtn.cloneNode(true);
        markAllBtn.parentNode.replaceChild(freshBtn, markAllBtn);

        freshBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            fetch('/admin/notifications/read-all', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function () {
                window.location.reload();
            }).catch(function () {});
        });
    }

    // ── Mark individual notification as read on click ──────────────────────────
    document.querySelectorAll('#notificationList [data-notif-id]').forEach(function (el) {
        el.addEventListener('click', function () {
            fetch('/admin/notifications/read/' + el.dataset.notifId, {
                method: 'POST', credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).catch(function () {});
        });
    });

    // ── Kick off polling ──────────────────────────────────────────────────────
    poll();
    setInterval(poll, POLL_MS);
});
