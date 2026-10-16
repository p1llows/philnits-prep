<script>
    document.addEventListener('keydown', function (e) {
        if (e.altKey && (e.key === 'a' || e.key === 'A' || e.code === 'KeyA')) {
            var target = document.activeElement;
            if (target && (['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName) || target.isContentEditable)) {
                return;
            }
            e.preventDefault();
            window.location.href = '/dashboard';
        }
    });
</script>
