(function () {
    'use strict';
    var KEY = 'es_theme';

    function apply(t) {
        if (t === 'dark') { document.documentElement.setAttribute('data-theme', 'dark'); }
        else { document.documentElement.removeAttribute('data-theme'); }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-theme').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                apply(next);
                try { localStorage.setItem(KEY, next); } catch (e) {}
            });
        });
    });
})();