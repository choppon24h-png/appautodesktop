<?php
$pageTitle = 'Consultar Placa';
require_once __DIR__ . '/../../layout/portal_header.php';
?>

<div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
    <a href="/portal/veiculos" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h4 style="font-weight:700; margin:0;">Consultar Placa</h4>
        <p style="color:var(--text-light); font-size:13px; margin:4px 0 0;">Consulte dados de qualquer veículo pela placa (gratuito)</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div style="max-width:500px; margin:0 auto; text-align:center; padding:20px 0;">
            <div style="font-size:48px; margin-bottom:16px;">🔍</div>
            <h5 style="font-weight:700; margin-bottom:8px;">Consulta de Placa</h5>
            <p style="color:var(--text-light); margin-bottom:24px; font-size:14px;">
                Digite a placa no formato antigo (ABC-1234) ou Mercosul (ABC1D23)
            </p>
            <div style="display:flex; gap:10px; justify-content:center;">
                <input type="text" id="inputPlaca" class="form-control" placeholder="ABC1D23 ou ABC-1234"
                       maxlength="8" style="font-family:monospace; font-size:20px; font-weight:700;
                       text-transform:uppercase; letter-spacing:3px; text-align:center; max-width:220px;"
                       oninput="detectarFormato(this.value)" onkeypress="if(event.key==='Enter')consultar()">
                <button class="btn btn-primary" onclick="consultar()" id="btnConsultar">
                    <i class="fas fa-search"></i> Consultar
                </button>
            </div>
            <div id="infoFormato" style="margin-top:10px; font-size:13px;"></div>
        </div>
    </div>
</div>

<div id="resultado" style="display:none;"></div>

<script>
function detectarFormato(placa) {
    var p = placa.replace(/[^A-Z0-9]/gi,'').toUpperCase();
    var el = document.getElementById('infoFormato');
    if (p.length < 7) { el.innerHTML=''; return; }
    var isMercosul = /^[A-Z]{3}[0-9][A-Z][0-9]{2}$/.test(p);
    el.innerHTML = isMercosul
        ? '<span style="color:#16a34a; font-weight:600;"><i class="fas fa-check-circle"></i> Placa Mercosul detectada</span>'
        : '<span style="color:#1a56db; font-weight:600;"><i class="fas fa-check-circle"></i> Placa Padrão detectada</span>';
}

function consultar() {
    var placa = document.getElementById('inputPlaca').value.replace(/[^A-Z0-9]/gi,'').toUpperCase();
    if (placa.length !== 7) {
        document.getElementById('resultado').style.display='block';
        document.getElementById('resultado').innerHTML =
            '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Informe 7 caracteres.</div>';
        return;
    }
    var btn = document.getElementById('btnConsultar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    document.getElementById('resultado').style.display='block';
    document.getElementById('resultado').innerHTML =
        '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Consultando base de dados...</div>';

    fetch('/portal/veiculos/api/consultar-placa?placa=' + placa)
        .then(function(r){ return r.json(); })
        .then(function(d){
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search"></i> Consultar';
            renderResultado(d, placa);
        })
        .catch(function(){
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search"></i> Consultar';
            document.getElementById('resultado').innerHTML =
                '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Erro na consulta. Tente novamente.</div>';
        });
}

function renderResultado(d, placa) {
    var isMercosul = /^[A-Z]{3}[0-9][A-Z][0-9]{2}$/.test(placa);
    var placaFmt = isMercosul ? placa : placa.substring(0,3)+'-'+placa.substring(3);

    if (d.erro) {
        document.getElementById('resultado').innerHTML =
            '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> '+d.erro+'</div>';
        return;
    }

    var campos = [
        ['Placa', placaFmt], ['Marca', d.marca||d.MARCA], ['Modelo', d.modelo||d.MODELO],
        ['Versão', d.versao||d.VERSAO], ['Ano', d.ano||d.ANO],
        ['Cor', d.cor||d.COR], ['Combustível', d.combustivel||d.COMBUSTIVEL],
        ['Município', d.municipio||d.MUNICIPIO], ['UF', d.uf||d.UF],
        ['Situação', d.situacao||d.SITUACAO], ['Chassi', d.chassi||d.CHASSI],
        ['RENAVAM', d.renavam||d.RENAVAM],
    ];

    var rows = '';
    campos.forEach(function(c){
        if (c[1]) rows += '<tr><td style="padding:10px 16px; font-weight:600; width:140px; color:var(--text-light);">'+c[0]+'</td><td style="padding:10px 16px; font-weight:500;">'+c[1]+'</td></tr>';
    });

    var aviso = d.aviso ? '<div class="alert alert-warning mb-3"><i class="fas fa-exclamation-triangle"></i> '+d.aviso+'</div>' : '';

    document.getElementById('resultado').innerHTML = aviso +
        '<div class="card">' +
        '<div class="card-header"><span class="card-title-sm"><i class="fas fa-car me-2"></i>Resultado para '+placaFmt+
        ' <span style="font-size:11px; font-weight:400; color:var(--text-light);">('+
        (isMercosul?'Mercosul':'Padrão')+')</span></span></div>' +
        '<div style="overflow-x:auto;"><table style="width:100%; border-collapse:collapse;">'+rows+'</table></div>' +
        '<div class="card-body" style="border-top:1px solid var(--border);">' +
        '<a href="/portal/veiculos/adicionar" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Adicionar este veículo</a>' +
        '</div></div>';
}
</script>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
