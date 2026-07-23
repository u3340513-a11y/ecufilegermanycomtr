document.addEventListener('DOMContentLoaded', function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        var tables = ['#usersTable', '#faultCodesTable', '#boschTable', '#logsTable'];
        tables.forEach(function(selector) {
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

    document.querySelectorAll('form[onsubmit]').forEach(function(form) {
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Emin misiniz?', text: 'Bu işlem geri alınamaz.',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Evet, sil', cancelButtonText: 'İptal'
            }).then(function(result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
