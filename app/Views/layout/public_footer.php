    </div><!-- /.public-card -->
</div><!-- /.public-wrapper -->

<script>
// Utilitário global para telas públicas
(function() {
    // Selecionar option-card via clique
    document.querySelectorAll('.option-card').forEach(function(card) {
        card.addEventListener('click', function() {
            var name = this.querySelector('input[type="radio"]').name;
            document.querySelectorAll('.option-card input[name="' + name + '"]').forEach(function(r) {
                r.closest('.option-card').classList.remove('selected');
            });
            this.querySelector('input[type="radio"]').checked = true;
            this.classList.add('selected');
        });
    });

    // Token digit navigation
    var tokenDigits = document.querySelectorAll('.token-digit');
    tokenDigits.forEach(function(input, idx) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9A-Za-z]/g, '').toUpperCase().slice(0, 1);
            if (this.value && idx < tokenDigits.length - 1) {
                tokenDigits[idx + 1].focus();
            }
            updateTokenField();
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                tokenDigits[idx - 1].focus();
            }
        });
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            var paste = (e.clipboardData || window.clipboardData).getData('text').toUpperCase().replace(/\s/g,'');
            paste.split('').forEach(function(ch, i) {
                if (tokenDigits[idx + i]) tokenDigits[idx + i].value = ch;
            });
            updateTokenField();
            var next = Math.min(idx + paste.length, tokenDigits.length - 1);
            tokenDigits[next].focus();
        });
    });

    function updateTokenField() {
        var hidden = document.getElementById('token_hidden');
        if (!hidden) return;
        var val = '';
        tokenDigits.forEach(function(d) { val += d.value; });
        hidden.value = val;
    }
})();
</script>
</body>
</html>
