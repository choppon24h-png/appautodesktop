<?php require_once dirname(__DIR__) . '/layout/public_header.php'; ?>

<?php $error      = isset($_GET['error'])      ? htmlspecialchars($_GET['error']) : ''; ?>
<?php $timeout    = isset($_GET['timeout'])    ? true : false; ?>
<?php $registered = isset($_GET['registered']) ? true : false; ?>
<?php $validated  = isset($_GET['validated'])  ? true : false; ?>

<h1 class="card-title">Bem-vindo ao AppAuto</h1>
<p class="card-subtitle">Acesse sua conta para continuar</p>

<?php if ($timeout): ?>
<div class="alert alert-info">
    <i class="fa fa-clock-o"></i>
    <span>Sua sessão expirou por inatividade. Faça login novamente.</span>
</div>
<?php endif; ?>

<?php if ($registered): ?>
<div class="alert alert-success">
    <i class="fa fa-check-circle"></i>
    <span>Cadastro realizado! Verifique seu e-mail e insira o token de validação.</span>
</div>
<?php endif; ?>

<?php if ($validated): ?>
<div class="alert alert-success">
    <i class="fa fa-check-circle"></i>
    <span>E-mail validado com sucesso! Faça login para continuar.</span>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger">
    <i class="fa fa-exclamation-circle"></i>
    <span><?php echo $error; ?></span>
</div>
<?php endif; ?>

<form action="/login" method="POST" id="formLogin">
    <?php echo \App\Core\View::csrfField(); ?>

    <div class="form-group">
        <label class="form-label" for="email">E-mail</label>
        <div class="input-group">
            <i class="fa fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="seu@email.com.br" required autofocus
                   value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="senha">Senha</label>
        <div class="input-group">
            <i class="fa fa-lock input-icon"></i>
            <input type="password" id="senha" name="senha" class="form-control"
                   placeholder="••••••••" required>
        </div>
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <label class="form-check">
            <input type="checkbox" name="lembrar"> Lembrar-me
        </label>
        <a href="/recuperar-senha" class="text-link">Esqueci minha senha</a>
    </div>

    <button type="submit" class="btn-primary" id="btnLogin">
        <i class="fa fa-sign-in"></i> Entrar
    </button>
</form>

<div class="divider">ou</div>

<a href="/cadastro" style="display:block; text-align:center; text-decoration:none; padding:12px 16px; border-radius:8px; border:1.5px solid #1a56db; color:#1a56db; font-weight:600; font-size:14px; transition:background .2s;" onmouseover="this.style.background='rgba(26,86,219,.06)'" onmouseout="this.style.background='transparent'">
    <i class="fa fa-user-plus"></i> Cadastre-se agora — é grátis!
</a>

<div class="card-footer-links mt-4">
    <span class="text-muted text-sm">Problemas para acessar?</span>
    <a href="mailto:suporte@appauto.com.br" class="text-link"> Fale conosco</a>
</div>

<script>
document.getElementById('formLogin').addEventListener('submit', function() {
    var btn = document.getElementById('btnLogin');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Entrando...';
});
</script>

<?php require_once dirname(__DIR__) . '/layout/public_footer.php'; ?>
