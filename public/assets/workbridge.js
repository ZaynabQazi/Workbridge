/* WorkBridge — Enhanced JS */

$(function () {

    /* ── TOAST HELPER ─────────────────────────────── */
    function showToast(msg, type = 'success') {
        var toast = $('#liveToast');
        toast.removeClass('bg-success bg-danger bg-warning text-white border-0');
        if (type === 'success') toast.addClass('bg-success text-white border-0');
        else if (type === 'error') toast.addClass('bg-danger text-white border-0');
        else toast.addClass('bg-warning border-0');
        toast.find('.toast-body').text(msg);
        new bootstrap.Toast(toast[0], { delay: 3000 }).show();
    }

    /* ── NOTIFICATIONS ────────────────────────────── */
    function loadNotifications() {
        $.get('/notifications', function (data) {
            var count = data.unread_count || 0;
            var $badge = $('.bell-count');
            if (count > 0) {
                $badge.text(count).show();
            } else {
                $badge.hide();
            }
            var $list = $('#notifList');
            if (!data.notifications || data.notifications.length === 0) {
                $list.html('<div class="p-4 text-center text-muted small"><i class="bi bi-bell-slash d-block mb-1 fs-4 opacity-50"></i>No new notifications</div>');
                return;
            }
            var html = '';
            data.notifications.forEach(function (n) {
                html += '<div class="notif-item" data-id="' + n.id + '">';
                html += '<strong>' + n.title + '</strong>';
                html += '<p>' + n.message + '</p>';
                html += '</div>';
            });
            $list.html(html);
        }).fail(function () { /* not logged in */ });
    }

    $('#notificationBell').on('click', function (e) {
        e.stopPropagation();
        $('#notifDropdown').toggleClass('show');
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#notifDropdown, #notificationBell').length) {
            $('#notifDropdown').removeClass('show');
        }
    });

    $(document).on('click', '.notif-item', function () {
        var id = $(this).data('id');
        $.post('/notifications/' + id + '/read', { _token: $('meta[name=csrf-token]').attr('content') }, function () {
            loadNotifications();
        });
    });

    $('#markAllRead').on('click', function () {
        $('#notifList .notif-item').each(function () {
            var id = $(this).data('id');
            if (id) {
                $.post('/notifications/' + id + '/read', { _token: $('meta[name=csrf-token]').attr('content') });
            }
        });
        setTimeout(loadNotifications, 400);
    });

    loadNotifications();
    setInterval(loadNotifications, 30000);

    /* ── LIVE AJAX JOB SEARCH ─────────────────────── */
    var searchTimer;

    function runSearch() {
        var q        = $('#liveSearch').val();
        var category = $('#categoryFilter').val();
        var type     = $('#typeFilter').val();

        if (!q && !category && !type) {
            $('#ajaxJobs').empty();
            $('#staticJobs').show();
            return;
        }

        $('#staticJobs').hide();
        $('#ajaxJobs').html(
            '<div class="d-flex align-items-center gap-2 text-muted small py-3">' +
            '<div class="spinner-border spinner-border-sm text-primary"></div> Searching…</div>'
        );

        $.get('/jobs/search', { q: q, category: category, type: type }, function (html) {
            if (!html || !html.trim()) {
                $('#ajaxJobs').html(
                    '<div class="text-center py-5 text-muted">' +
                    '<i class="bi bi-search fs-1 opacity-30 d-block mb-2"></i>' +
                    '<p>No results for those filters. Try different keywords.</p></div>'
                );
            } else {
                $('#ajaxJobs').html(html);
            }
        }).fail(function () {
            $('#staticJobs').show();
            $('#ajaxJobs').empty();
        });
    }

    $('#liveSearch').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(runSearch, 350);
    });

    $('#categoryFilter, #typeFilter').on('change', runSearch);

    /* ── FILE PREVIEW ─────────────────────────────── */
    $(document).on('change', '.file-preview-input', function () {
        var name = this.files[0] ? this.files[0].name : '';
        $(this).closest('label').next('.selected-file').html(
            name ? '<i class="bi bi-check-circle text-success me-1"></i>' + name : ''
        );
    });

    /* ── PASSWORD STRENGTH (register) ────────────── */
    $('#password').on('input', function () {
        var v = this.value, s = 0;
        if (v.length >= 8)            s += 25;
        if (/[A-Z]/.test(v))          s += 25;
        if (/[0-9]/.test(v))          s += 25;
        if (/[^A-Za-z0-9]/.test(v))   s += 25;
        var bar = $('#strength');
        bar.css('width', s + '%');
        if      (s <= 25) bar.removeClass().addClass('progress-bar bg-danger');
        else if (s <= 50) bar.removeClass().addClass('progress-bar bg-warning');
        else if (s <= 75) bar.removeClass().addClass('progress-bar bg-info');
        else              bar.removeClass().addClass('progress-bar bg-success');
    });

    /* ── ADMIN CHART ──────────────────────────────── */
    if (window.weeklyLabels && document.getElementById('weeklyChart')) {
        var ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: window.weeklyLabels,
                datasets: [{
                    label: 'Applications',
                    data: window.weeklyValues,
                    backgroundColor: 'rgba(79,70,229,.2)',
                    borderColor: 'rgba(79,70,229,.8)',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: function(c){ return ' '+c.raw+' applications'; } } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 12 } } },
                    y: { beginAtZero: true, ticks: { precision: 0, font: { size: 12 } }, grid: { color: 'rgba(0,0,0,.05)' } }
                }
            }
        });
    }

    /* ── RESUME ANALYZER DRAG-DROP ────────────────── */
    var $zone = $('#analyzerZone');
    if ($zone.length) {
        $zone.on('dragover', function (e) {
            e.preventDefault();
            $(this).addClass('drag-over');
        }).on('dragleave drop', function (e) {
            e.preventDefault();
            $(this).removeClass('drag-over');
            if (e.type === 'drop') {
                var file = e.originalEvent.dataTransfer.files[0];
                if (file) {
                    $('#resumeInput')[0].files = e.originalEvent.dataTransfer.files;
                    $('#analyzerZone .selected-file').html('<i class="bi bi-check-circle text-success me-1"></i>' + file.name);
                }
            }
        });
    }

    /* ── AUTO-DISMISS ALERTS ──────────────────────── */
    setTimeout(function () {
        $('.alert').fadeOut(400);
    }, 4500);

});
