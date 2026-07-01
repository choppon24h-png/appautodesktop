<?php require_once dirname(__DIR__) . '/layout/public_header.php'; ?>

<?php $error = isset($error) ? htmlspecialchars($error) : ''; ?>

<!-- Steps -->
<div class="steps" id="stepsBar">
    <div class="step-dot active" id="dot1"></div>
    <div class="step-dot" id="dot2"></div>
    <div class="step-dot" id="dot3"></div>
</div>

<!-- STEP 1: Tipo de conta -->
<div id="step1">
    <h1 class="card-title">Criar sua conta</h1>
    <p class="card-subtitle">Como você vai usar o AppAuto?</p>

    <?php if ($error): ?>
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <span><?php echo $error; ?></span>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <label class="form-label">Tipo de conta</label>
        <div class="option-group">
            <div class="option-card selected" id="opt-pessoal">
                <input type="radio" name="tipo_conta_sel" value="pessoal" checked>
                <div class="option-icon"><i class="fa fa-user"></i></div>
                <div class="option-label">Pessoal</div>
                <div class="option-desc">Para uso próprio</div>
            </div>
            <div class="option-card" id="opt-negocio">
                <input type="radio" name="tipo_conta_sel" value="negocio">
                <div class="option-icon"><i class="fa fa-building"></i></div>
                <div class="option-label">Negócio</div>
                <div class="option-desc">Para minha empresa</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Tipo de documento</label>
        <div class="option-group">
            <div class="option-card selected" id="opt-cpf">
                <input type="radio" name="tipo_doc_sel" value="cpf" checked>
                <div class="option-icon"><i class="fa fa-id-card"></i></div>
                <div class="option-label">CPF</div>
                <div class="option-desc">Pessoa Física</div>
            </div>
            <div class="option-card" id="opt-cnpj">
                <input type="radio" name="tipo_doc_sel" value="cnpj">
                <div class="option-icon"><i class="fa fa-briefcase"></i></div>
                <div class="option-label">CNPJ</div>
                <div class="option-desc">Pessoa Jurídica</div>
            </div>
        </div>
    </div>

    <button type="button" class="btn-primary" onclick="irStep2()">
        Continuar <i class="fa fa-arrow-right"></i>
    </button>
</div>

<!-- STEP 2: Dados pessoais -->
<div id="step2" class="hidden">
    <h1 class="card-title">Seus dados</h1>
    <p class="card-subtitle">Preencha as informações básicas</p>

    <div class="form-group">
        <label class="form-label" for="nome_completo">Nome completo *</label>
        <div class="input-group">
            <i class="fa fa-user input-icon"></i>
            <input type="text" id="nome_completo" name="nome_completo" class="form-control"
                   placeholder="Seu nome completo" required>
        </div>
    </div>

    <div class="form-group" id="campo-cpf">
        <label class="form-label" for="cpf">CPF *</label>
        <div class="input-group">
            <i class="fa fa-id-card input-icon"></i>
            <input type="text" id="cpf" name="cpf" class="form-control"
                   placeholder="000.000.000-00" maxlength="14">
        </div>
    </div>

    <div class="form-group hidden" id="campo-cnpj">
        <label class="form-label" for="cnpj">CNPJ *</label>
        <div class="input-group">
            <i class="fa fa-briefcase input-icon"></i>
            <input type="text" id="cnpj" name="cnpj" class="form-control"
                   placeholder="00.000.000/0000-00" maxlength="18">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="telefone">Telefone / WhatsApp *</label>
        <div class="input-group">
            <i class="fa fa-phone input-icon"></i>
            <input type="tel" id="telefone" name="telefone" class="form-control"
                   placeholder="(00) 00000-0000" maxlength="16">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="email">E-mail *</label>
        <div class="input-group">
            <i class="fa fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="seu@email.com.br" required>
        </div>
        <p class="text-sm text-muted mt-2">
            <i class="fa fa-info-circle"></i>
            Você receberá um código de validação neste e-mail
        </p>
    </div>

    <div class="form-group">
        <label class="form-label" for="senha">Senha *</label>
        <div class="input-group">
            <i class="fa fa-lock input-icon"></i>
            <input type="password" id="senha" name="senha" class="form-control"
                   placeholder="Mínimo 8 caracteres" required minlength="8">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="senha_conf">Confirmar senha *</label>
        <div class="input-group">
            <i class="fa fa-lock input-icon"></i>
            <input type="password" id="senha_conf" name="senha_conf" class="form-control"
                   placeholder="Repita a senha" required>
        </div>
    </div>

    <div style="display:flex; gap:10px; margin-top:8px;">
        <button type="button" class="btn-secondary" onclick="irStep1()" style="width:auto; padding:12px 20px;">
            <i class="fa fa-arrow-left"></i> Voltar
        </button>
        <button type="button" class="btn-primary" onclick="irStep3()" style="flex:1;">
            Continuar <i class="fa fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- STEP 3: Ramo de atividade (apenas para negócios) / Confirmação -->
<div id="step3" class="hidden">
    <h1 class="card-title" id="step3Title">Quase lá!</h1>
    <p class="card-subtitle" id="step3Subtitle">Confirme seu cadastro</p>

    <!-- Ramo de atividade (só para negócio) -->
    <div id="bloco-ramo" class="hidden">
        <div class="form-group">
            <label class="form-label" for="ramo_atividade_id">Ramo de atividade *</label>
            <div class="input-group">
                <i class="fa fa-industry input-icon"></i>
                <select id="ramo_atividade_id" name="ramo_atividade_id" class="form-control">
                    <option value="">Selecione o ramo...</option>
                    <?php if (!empty($ramos)): ?>
                        <?php foreach ($ramos as $ramo): ?>
                        <option value="<?php echo $ramo->id; ?>"><?php echo htmlspecialchars($ramo->nome); ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">Oficina Mecânica</option>
                        <option value="3">Pintura Automotiva</option>
                        <option value="4">Lava Jato</option>
                        <option value="5">Borracharia</option>
                        <option value="8">Estética Automotiva</option>
                        <option value="9">Funilaria</option>
                        <option value="14">Autopeças</option>
                        <option value="25">Outros</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="nome_negocio">Nome do negócio *</label>
            <div class="input-group">
                <i class="fa fa-building input-icon"></i>
                <input type="text" id="nome_negocio" name="nome_negocio" class="form-control"
                       placeholder="Nome da sua empresa">
            </div>
        </div>
    </div>

    <!-- Resumo -->
    <div class="alert alert-info" id="resumoCadastro">
        <i class="fa fa-info-circle"></i>
        <div>
            <strong>Resumo do cadastro</strong><br>
            <span id="resumoTexto" class="text-sm"></span>
        </div>
    </div>

    <div class="form-group">
        <label class="form-check">
            <input type="checkbox" id="aceite_termos" required>
            Aceito os <a href="/termos" class="text-link" target="_blank">Termos de Uso</a>
            e a <a href="/privacidade" class="text-link" target="_blank">Política de Privacidade</a>
        </label>
    </div>

    <!-- Formulário real enviado ao servidor -->
    <form action="/cadastro" method="POST" id="formCadastro">
        <?php echo \App\Core\View::csrfField(); ?>
        <input type="hidden" id="h_tipo_conta"        name="tipo_conta">
        <input type="hidden" id="h_tipo_documento"    name="tipo_documento">
        <input type="hidden" id="h_nome_completo"     name="nome_completo">
        <input type="hidden" id="h_cpf"               name="cpf">
        <input type="hidden" id="h_cnpj"              name="cnpj">
        <input type="hidden" id="h_telefone"          name="telefone">
        <input type="hidden" id="h_email"             name="email">
        <input type="hidden" id="h_senha"             name="senha">
        <input type="hidden" id="h_ramo_atividade_id" name="ramo_atividade_id">
        <input type="hidden" id="h_nome_negocio"      name="nome_negocio">

        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="button" class="btn-secondary" onclick="irStep2()" style="width:auto; padding:12px 20px;">
                <i class="fa fa-arrow-left"></i> Voltar
            </button>
            <button type="submit" class="btn-primary" id="btnCadastrar" style="flex:1;">
                <i class="fa fa-check"></i> Criar minha conta
            </button>
        </div>
    </form>
</div>

<div class="card-footer-links mt-4">
    <span class="text-muted">Já tem conta?</span>
    <a href="/login" class="text-link"> Fazer login</a>
</div>

<script>
var tipoConta = 'pessoal';
var tipoDoc   = 'cpf';

function irStep1() {
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.add('hidden');
    atualizarDots(1);
}

function irStep2() {
    tipoConta = document.querySelector('input[name="tipo_conta_sel"]:checked').value;
    tipoDoc   = document.querySelector('input[name="tipo_doc_sel"]:checked').value;

    // Mostrar/ocultar campos de documento
    if (tipoDoc === 'cnpj') {
        document.getElementById('campo-cpf').classList.add('hidden');
        document.getElementById('campo-cnpj').classList.remove('hidden');
    } else {
        document.getElementById('campo-cpf').classList.remove('hidden');
        document.getElementById('campo-cnpj').classList.add('hidden');
    }

    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
    document.getElementById('step3').classList.add('hidden');
    atualizarDots(2);
    document.getElementById('nome_completo').focus();
}

function irStep3() {
    // Validações básicas
    var nome  = document.getElementById('nome_completo').value.trim();
    var email = document.getElementById('email').value.trim();
    var senha = document.getElementById('senha').value;
    var conf  = document.getElementById('senha_conf').value;
    var tel   = document.getElementById('telefone').value.trim();

    if (!nome)  { alert('Informe seu nome completo.'); return; }
    if (!email) { alert('Informe seu e-mail.'); return; }
    if (!tel)   { alert('Informe seu telefone.'); return; }
    if (senha.length < 8) { alert('A senha deve ter pelo menos 8 caracteres.'); return; }
    if (senha !== conf)   { alert('As senhas não coincidem.'); return; }

    // Mostrar ramo se for negócio
    if (tipoConta === 'negocio') {
        document.getElementById('bloco-ramo').classList.remove('hidden');
        document.getElementById('step3Title').textContent = 'Seu negócio';
        document.getElementById('step3Subtitle').textContent = 'Informe os dados da sua empresa';
    } else {
        document.getElementById('bloco-ramo').classList.add('hidden');
        document.getElementById('step3Title').textContent = 'Quase lá!';
        document.getElementById('step3Subtitle').textContent = 'Confirme seu cadastro';
    }

    // Resumo
    var docVal = tipoDoc === 'cpf'
        ? document.getElementById('cpf').value
        : document.getElementById('cnpj').value;
    document.getElementById('resumoTexto').innerHTML =
        '<b>Nome:</b> ' + nome + '<br>' +
        '<b>E-mail:</b> ' + email + '<br>' +
        '<b>Telefone:</b> ' + tel + '<br>' +
        '<b>Tipo:</b> ' + (tipoConta === 'negocio' ? 'Negócio' : 'Pessoal') + ' (' + tipoDoc.toUpperCase() + ')';

    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.remove('hidden');
    atualizarDots(3);
}

function atualizarDots(step) {
    for (var i = 1; i <= 3; i++) {
        var dot = document.getElementById('dot' + i);
        dot.classList.remove('active', 'done');
        if (i < step)  dot.classList.add('done');
        if (i === step) dot.classList.add('active');
    }
}

// Preencher hidden inputs antes de enviar
document.getElementById('formCadastro').addEventListener('submit', function(e) {
    if (!document.getElementById('aceite_termos').checked) {
        e.preventDefault();
        alert('Você precisa aceitar os Termos de Uso para continuar.');
        return;
    }

    document.getElementById('h_tipo_conta').value        = tipoConta;
    document.getElementById('h_tipo_documento').value    = tipoDoc;
    document.getElementById('h_nome_completo').value     = document.getElementById('nome_completo').value;
    document.getElementById('h_cpf').value               = document.getElementById('cpf').value;
    document.getElementById('h_cnpj').value              = document.getElementById('cnpj').value;
    document.getElementById('h_telefone').value          = document.getElementById('telefone').value;
    document.getElementById('h_email').value             = document.getElementById('email').value;
    document.getElementById('h_senha').value             = document.getElementById('senha').value;
    document.getElementById('h_ramo_atividade_id').value = document.getElementById('ramo_atividade_id').value;
    document.getElementById('h_nome_negocio').value      = document.getElementById('nome_negocio').value;

    var btn = document.getElementById('btnCadastrar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Criando conta...';
});

// Máscaras simples
document.getElementById('cpf').addEventListener('input', function() {
    var v = this.value.replace(/\D/g,'').slice(0,11);
    v = v.replace(/(\d{3})(\d)/,'$1.$2');
    v = v.replace(/(\d{3})(\d)/,'$1.$2');
    v = v.replace(/(\d{3})(\d{1,2})$/,'$1-$2');
    this.value = v;
});
document.getElementById('cnpj').addEventListener('input', function() {
    var v = this.value.replace(/\D/g,'').slice(0,14);
    v = v.replace(/^(\d{2})(\d)/,'$1.$2');
    v = v.replace(/^(\d{2})\.(\d{3})(\d)/,'$1.$2.$3');
    v = v.replace(/\.(\d{3})(\d)/,'.$1/$2');
    v = v.replace(/(\d{4})(\d)/,'$1-$2');
    this.value = v;
});
document.getElementById('telefone').addEventListener('input', function() {
    var v = this.value.replace(/\D/g,'').slice(0,11);
    if (v.length > 10) {
        v = v.replace(/^(\d{2})(\d{5})(\d{4})/,'($1) $2-$3');
    } else {
        v = v.replace(/^(\d{2})(\d{4})(\d{4})/,'($1) $2-$3');
    }
    this.value = v;
});
</script>

<?php require_once dirname(__DIR__) . '/layout/public_footer.php'; ?>
