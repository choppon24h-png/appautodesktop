<?php require_once dirname(__DIR__) . '/layout/public_header.php'; ?>

<?php $error = isset($error) ? htmlspecialchars($error) : ''; ?>
<?php $email = isset($email) ? htmlspecialchars($email) : ''; ?>

<h1 class="card-title">Validar seu e-mail</h1>
<p class="card-subtitle">
    Enviamos um código de 6 dígitos para<br>
    <strong><?php echo $email ?: 'seu e-mail'; ?></strong>
</p>

<?php if ($error): ?>
<div class="alert alert-danger">
    <i class="fa fa-exclamation-circle"></i>
    <span><?php echo $error; ?></span>
</div>
<?php endif; ?>

<div class="alert alert-info">
    <i class="fa fa-envelope"></i>
    <span>Verifique sua caixa de entrada e spam. O código expira em <strong>30 minutos</strong>.</span>
</div>

<form action="/validar-token" method="POST" id="formToken">
    <?php echo \App\Core\View::csrfField(); ?>
    <input type="hidden" name="email" value="<?php echo $email; ?>">
    <input type="hidden" id="token_hidden" name="token" value="">

    <label class="form-label text-center" style="display:block; margin-bottom:12px;">
        Digite o código recebido:
    </label>

    <div class="token-input-group">
        <input type="text" class="token-digit" maxlength="1" inputmode="numeric" autocomplete="off">
        <input type="text" class="token-digit" maxlength="1" inputmode="numeric" autocomplete="off">
        <input type="text" class="token-digit" maxlength="1" inputmode="numeric" autocomplete="off">
        <input type="text" class="token-digit" maxlength="1" inputmode="numeric" autocomplete="off">
        <input type="text" class="token-digit" maxlength="1" inputmode="numeric" autocomplete="off">
        <input type="text" class="token-digit" maxlength="1" inputmode="numeric" autocomplete="off">
    </div>

    <button type="submit" class="btn-primary" id="btnValidar">
        <i class="fa fa-check-circle"></i> Validar código
    </button>
</form>

<div class="card-footer-links mt-4">
    <span class="text-muted">Não recebeu o código?</span>
    <a href="/reenviar-token?email=<?php echo urlencode($email); ?>" class="text-link"> Reenviar código</a>
</div>

<div class="card-footer-links mt-2">
    <a href="/login" class="text-link"><i class="fa fa-arrow-left"></i> Voltar ao login</a>
</div>

<script>
document.getElementById('formToken').addEventListener('submit', function() {
    var btn = document.getElementById('btnValidar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Validando...';
});
// Focar no primeiro dígito ao carregar
window.addEventListener('load', function() {
    var first = document.querySelector('.token-digit');
    if (first) first.focus();
});
</script>

<?php require_once dirname(__DIR__) . '/layout/public_footer.php'; ?>
