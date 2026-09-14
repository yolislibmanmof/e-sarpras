document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('photo');
    var note = document.getElementById('photoName');

    if (input && note) {
        input.addEventListener('change', function () {
            note.textContent = input.files.length ? 'File terpilih: ' + input.files[0].name : '';
        });
    }

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var button = form.querySelector('button[type="submit"]');
            if (button && !button.disabled) {
                button.disabled = true;
                button.textContent = 'Mengirim...';
                setTimeout(function () { button.disabled = false; }, 4000);
            }
        });
    });
});