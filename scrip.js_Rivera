window.addEventListener('load', function() {
    const inputs = document.querySelectorAll('input');

    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'violet';
            this.style.boxShadow = '0 0 8px rgba(238, 130, 238, 0.4)';
        });

        input.addEventListener('blur', function() {
            this.style.borderColor = '#ccc';
            this.style.boxShadow = 'none';
        });
    });
});