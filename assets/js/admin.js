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

    // ─── Admin Notification Polling + Bell Sound ─────────────────────────────
    /**
     * Polls /admin/notifications/unread-count every 30 seconds.
     * When the unread count increases compared to the last known value,
     * plays a soft bell chime generated via the Web Audio API and shows
     * a SweetAlert2 toast in the bottom-right corner.
     *
     * Requires: SweetAlert2 (already loaded in admin layout)
     */
    (function initAdminNotifPoller() {

        /** Synthesises a short bell chime using the Web Audio API (no external file needed). */
        function playBellSound() {
            try {
                var ctx = new (window.AudioContext || window.webkitAudioContext)();

                // Oscillator 1 — fundamental
                var osc1 = ctx.createOscillator();
                var gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(880, ctx.currentTime);
                osc1.frequency.exponentialRampToValueAtTime(660, ctx.currentTime + 0.4);
                gain1.gain.setValueAtTime(0.5, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(ctx.currentTime);
                osc1.stop(ctx.currentTime + 0.8);

                // Oscillator 2 — harmonic overtone
                var osc2 = ctx.createOscillator();
                var gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1320, ctx.currentTime);
                gain2.gain.setValueAtTime(0.25, ctx.currentTime);
                gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime);
                osc2.stop(ctx.currentTime + 0.5);
            } catch (e) {
                // AudioContext may be blocked on some browsers — safe to ignore
            }
        }

        /** Shows a non-blocking toast notification in the bottom-right corner. */
        function showToast(count) {
            if (typeof Swal === 'undefined') return;
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'info',
                title: count + ' okunmamış bildiriminiz var',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                background: '#0f172a',
                color: '#e0f2fe',
                iconColor: '#38bdf8',
                customClass: { popup: 'admin-notif-toast' }
            });
        }

        var lastKnownCount = null;
        var POLL_INTERVAL_MS = 30000; // 30 seconds

        function poll() {
            fetch('/admin/notifications/unread-count', {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (res) { return res.ok ? res.json() : null; })
            .then(function (data) {
                if (!data || !data.success) return;

                var count = parseInt(data.count, 10);

                // First load: just store the baseline without alerting
                if (lastKnownCount === null) {
                    lastKnownCount = count;
                    updateBadge(count);
                    return;
                }

                // Count increased → new notification(s) arrived
                if (count > lastKnownCount) {
                    playBellSound();
                    showToast(count);
                }

                lastKnownCount = count;
                updateBadge(count);
            })
            .catch(function () { /* Network error — silently retry next interval */ });
        }

        /** Updates the red dot visibility on the header bell icon. */
        function updateBadge(count) {
            var dot = document.getElementById('headerNotifDot');
            if (dot) {
                dot.style.display = count > 0 ? 'block' : 'none';
            }
        }

        // Initial poll immediately, then every POLL_INTERVAL_MS
        poll();
        setInterval(poll, POLL_INTERVAL_MS);

    })();

});
