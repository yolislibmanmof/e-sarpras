(function () {
    'use strict';

    function optimize() {
        /* Gambar: decoding asinkron agar tidak memblokir render */
        document.querySelectorAll('img').forEach(function (img) {
            if (!img.hasAttribute('decoding')) { img.setAttribute('decoding', 'async'); }
        });

        /* Kartu di bawah lipatan: tunda render hingga mendekati layar */
        var nodes = document.querySelectorAll('.card, .stat-card, .flip');
        nodes.forEach(function (el, i) {
            if (i > 6 && CSS.supports('content-visibility', 'auto')) {
                el.style.contentVisibility = 'auto';
                el.style.containIntrinsicSize = 'auto 280px';
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', optimize);
    } else {
        optimize();
    }
})();