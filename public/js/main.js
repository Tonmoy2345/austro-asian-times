// small ui helpers for the site

document.addEventListener('DOMContentLoaded', function () {

    // filter tabs on editor dashboard
    var tabBtns = document.querySelectorAll('.tab-btn');
    var articleRows = document.querySelectorAll('.article-row');

    if (tabBtns.length > 0 && articleRows.length > 0) {
        tabBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                tabBtns.forEach(function (b) { b.classList.remove('tab-active'); });
                btn.classList.add('tab-active');

                var filter = btn.getAttribute('data-filter');

                articleRows.forEach(function (row) {
                    if (filter === 'all') {
                        row.style.display = '';
                    } else {
                        row.style.display = row.getAttribute('data-status') === filter ? '' : 'none';
                    }
                });
            });
        });
    }

    // hide flash alerts after 5 sec
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            if (alert.parentNode) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function () {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        }, 5000);
    });

    // tidy up keyword input on blur
    var keywordsInput = document.getElementById('keywords');
    if (keywordsInput) {
        keywordsInput.addEventListener('blur', function () {
            var val = keywordsInput.value;
            var tags = val.split(',').map(function (t) { return t.trim(); }).filter(Boolean);
            keywordsInput.value = tags.join(', ');
        });
    }

    // basic client-side image check before upload
    var imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            var file = imageInput.files[0];
            if (!file) return;

            var maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('This image is too large. Maximum size is 2MB.');
                imageInput.value = '';
                return;
            }

            var allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (allowed.indexOf(file.type) === -1) {
                alert('Invalid file type. Only JPG, PNG, GIF and WEBP are allowed.');
                imageInput.value = '';
                return;
            }
        });
    }

});
