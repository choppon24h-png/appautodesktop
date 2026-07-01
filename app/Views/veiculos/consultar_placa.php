<?php require_once dirname(__DIR__) . '/layout/app_header.php'; ?>

<div class="card" style="max-width:600px; margin:0 auto;">
    <div class="card-header">
        <span class="card-title-sm"><i class="fa fa-search"></i> Consultar Placa</span>
    </div>
    <div class="card-body">
        <p class="text-muted" style="margin-bottom:20px; font-size:13px;">
            Digite a placa do veículo para consultar dados básicos via APIs gratuitas.
            O sistema detecta automaticamente se é placa padrão ou Mercosul.
        </p>

        <div style="display:flex; gap:12px; align-items:flex-end; margin-bottom:20px;">
            <div style="flex:1;">
                <label class="form-label">Placa do veículo</label>
                <input type="text" id="inputPlaca" class="form-control"
                       placeholder="ABC1234 ou ABC1D23" maxlength="7"
                       style="font-family:monospace; font-size:22px; font-weight:700; letter-spacing:3px; text-transform:uppercase; text-align:center;">
                <p class="text-sm mt-2" id="msgFormato" style="text-align:center;"></p>
            </div>
        </div>

        <button type="button" class="btn btn-primary" style="width:100%;" onclick="consultar()">
            <i class="fa fa-search"></i> Consultar Placa
        </button>

        <div id="resultado" style="margin-top:20px;"></div>
    </div>
</div>

<script>
document.getElementById('inputPlaca').addEventListener('input', function() {
    var v = this.value.replace(/[^A-Z0-9]/gi,'').toUpperCase().slice(0,7);
    this.value = v;
    var el = document.getElementById('msgFormato');
    if (v.length < 7) { el.textContent=''; return; }
    if (/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/.test(v)) {
        el.innerHTML = '<span style="color:var(--success)">✓ Placa Mercosul</span>';
    } else if (/^[A-Z]{3}[0-9]{4}$/.test(v)) {
        el.innerHTML = '<span style="color:var(--success)">✓ Placa Padrão (Antiga)</span>';
    } else {
        el.innerHTML = '<span style="color:var(--danger)">⚠ Formato não reconhecido</span>';
    }
});

document.getElementById('inputPlaca').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') consultar();
});

function consultar() {
    var placa = document.getElementById('inputPlaca').value.replace(/[^A-Z0-9]/gi,'').toUpperCase();
    if (placa.length < 7) { alert('Informe a placa completa.'); return; }

    var res = document.getElementById('resultado');
    res.innerHTML = '<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Consultando...</div>';

    fetch('/veiculos/api/consultar-placa?placa=' + placa, {
        headers: {'X-Requested-With':'XMLHttpRequest'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            var d = data.dados;
            var html = '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Dados encontrados (fonte: ' + data.fonte + ')</div>';
            html += '<div style="background:#f8fafc; border-radius:8px; padding:20px;">';
            html += '<table style="width:100%; font-size:14px; border-collapse:collapse;">';
            var campos = {placa:'Placa',marca:'Marca',modelo:'Modelo',versao:'Versão',ano:'Ano',cor:'Cor',combustivel:'Combustível',municipio:'Município',uf:'UF',situacao:'Situação',chassi:'Chassi',renavam:'RENAVAM'};
            for (var k in campos) {
                if (d[k]) html += '<tr><td style="padding:8px 12px; font-weight:600; width:140px; border-bottom:1px solid var(--border);">' + campos[k] + '</td><td style="padding:8px 12px; border-bottom:1px solid var(--border);">' + d[k] + '</td></tr>';
            }
            html += '</table>';
            html += '<div style="margin-top:16px; display:flex; gap:10px;">';
            html += '<a href="/veiculos/adicionar?placa=' + placa + '" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Adicionar à minha garagem</a>';
            html += '</div></div>';
            res.innerHTML = html;
        } else {
            res.innerHTML = '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' + (data.message || 'Placa não encontrada.') + '</div>';
        }
    })
    .catch(() => {
        res.innerHTML = '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Erro ao consultar. Tente novamente.</div>';
    });
}
</script>

<?php require_once dirname(__DIR__) . '/layout/app_footer.php'; ?>
