<?php require_once dirname(__DIR__) . '/layout/app_header.php'; ?>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Consulta de Placa -->
<div class="card mb-4">
    <div class="card-header">
        <span class="card-title-sm"><i class="fa fa-search"></i> Consultar Placa</span>
        <span class="text-sm text-muted">Preencha automaticamente os dados</span>
    </div>
    <div class="card-body">
        <div style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px;">
                <label class="form-label">Placa do veículo</label>
                <input type="text" id="inputPlacaBusca" class="form-control"
                       placeholder="ABC-1234 ou ABC1D23" maxlength="8"
                       style="font-family:monospace; font-size:18px; font-weight:700; letter-spacing:2px; text-transform:uppercase;">
                <p class="text-sm text-muted mt-2" id="msgFormatoPlaca"></p>
            </div>
            <button type="button" class="btn btn-primary" id="btnConsultarPlaca" onclick="consultarPlaca()">
                <i class="fa fa-search"></i> Consultar
            </button>
        </div>
        <div id="resultadoConsulta" class="hidden" style="margin-top:16px;"></div>
    </div>
</div>

<!-- OCR de Documento -->
<div class="card mb-4">
    <div class="card-header">
        <span class="card-title-sm"><i class="fa fa-camera"></i> Ler Documento (OCR)</span>
        <span class="text-sm text-muted">Tire foto do CRLV para preencher automaticamente</span>
    </div>
    <div class="card-body">
        <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
            <label style="cursor:pointer; border:2px dashed var(--border); border-radius:8px; padding:20px 32px; text-align:center; transition:border-color .2s; flex:1; min-width:200px;" id="dropZoneOCR">
                <input type="file" id="inputOCR" accept="image/*" capture="environment" style="display:none;" onchange="processarOCR(this)">
                <i class="fa fa-file-image-o" style="font-size:32px; color:var(--text-light); display:block; margin-bottom:8px;"></i>
                <span class="fw-600">Clique para tirar foto ou selecionar imagem</span><br>
                <span class="text-sm text-muted">CRLV, CRV ou documento do veículo</span>
            </label>
            <div id="previewOCR" class="hidden" style="width:120px; height:80px; border-radius:8px; overflow:hidden; flex-shrink:0;">
                <img id="imgPreviewOCR" style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
        <div id="statusOCR" class="hidden" style="margin-top:12px;"></div>
    </div>
</div>

<!-- Formulário de Cadastro -->
<div class="card">
    <div class="card-header">
        <span class="card-title-sm"><i class="fa fa-car"></i> Dados do Veículo</span>
    </div>
    <div class="card-body">
        <form action="/veiculos/adicionar" method="POST" enctype="multipart/form-data" id="formVeiculo">
            <?php echo \App\Core\View::csrfField(); ?>

            <!-- Placa e Formato -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Placa *</label>
                    <input type="text" name="placa" id="campoPlaca" class="form-control"
                           placeholder="ABC-1234" maxlength="8" required
                           style="font-family:monospace; font-size:16px; font-weight:700; letter-spacing:2px; text-transform:uppercase;">
                    <p class="text-sm mt-2" id="infoFormatoPlaca"></p>
                </div>
                <div class="form-group">
                    <label class="form-label">RENAVAM</label>
                    <input type="text" name="renavam" id="campoRenavam" class="form-control"
                           placeholder="00000000000" maxlength="11">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Chassi</label>
                <input type="text" name="chassi" id="campoChassi" class="form-control"
                       placeholder="17 caracteres" maxlength="17" style="text-transform:uppercase;">
            </div>

            <!-- Marca, Modelo, Versão -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Marca</label>
                    <input type="text" name="marca" id="campoMarca" class="form-control" placeholder="Ex: Volkswagen">
                </div>
                <div class="form-group">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" id="campoModelo" class="form-control" placeholder="Ex: Gol">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Versão</label>
                    <input type="text" name="versao" id="campoVersao" class="form-control" placeholder="Ex: 1.0 Trendline">
                </div>
                <div class="form-group">
                    <label class="form-label">Ano Fabricação / Modelo</label>
                    <div style="display:flex; gap:8px;">
                        <input type="number" name="ano_fabricacao" id="campoAnoFab" class="form-control"
                               placeholder="2020" min="1900" max="2030" style="flex:1;">
                        <span style="align-self:center; color:var(--text-light);">/</span>
                        <input type="number" name="ano_modelo" id="campoAnoMod" class="form-control"
                               placeholder="2021" min="1900" max="2031" style="flex:1;">
                    </div>
                </div>
            </div>

            <!-- Cor, Combustível, Categoria -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Cor</label>
                    <select name="cor" id="campoCor" class="form-control">
                        <option value="">Selecione...</option>
                        <option>Branca</option><option>Preta</option><option>Prata</option>
                        <option>Cinza</option><option>Vermelha</option><option>Azul</option>
                        <option>Verde</option><option>Amarela</option><option>Marrom</option>
                        <option>Bege</option><option>Laranja</option><option>Roxa</option>
                        <option>Vinho</option><option>Dourada</option><option>Outra</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Combustível</label>
                    <select name="combustivel" id="campoCombustivel" class="form-control">
                        <option value="">Selecione...</option>
                        <option value="gasolina">Gasolina</option>
                        <option value="etanol">Etanol</option>
                        <option value="flex">Flex (Gasolina/Etanol)</option>
                        <option value="diesel">Diesel</option>
                        <option value="gnv">GNV</option>
                        <option value="eletrico">Elétrico</option>
                        <option value="hibrido">Híbrido</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-control">
                        <option value="">Selecione...</option>
                        <option value="passeio">Passeio</option>
                        <option value="comercial_leve">Comercial Leve</option>
                        <option value="comercial_pesado">Comercial Pesado</option>
                        <option value="moto">Moto</option>
                        <option value="caminhao">Caminhão</option>
                        <option value="onibus">Ônibus</option>
                        <option value="especial">Especial</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipo de Veículo</label>
                    <select name="tipo_veiculo" class="form-control">
                        <option value="carro">Carro</option>
                        <option value="moto">Moto</option>
                        <option value="caminhao">Caminhão</option>
                        <option value="van">Van</option>
                        <option value="onibus">Ônibus</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
            </div>

            <!-- Fotos -->
            <div class="form-group">
                <label class="form-label"><i class="fa fa-camera"></i> Fotos do Veículo</label>
                <input type="file" name="fotos[]" id="inputFotos" class="form-control"
                       accept="image/*" multiple>
                <p class="text-sm text-muted mt-2">Você pode selecionar múltiplas fotos. A primeira será a foto principal.</p>
            </div>

            <!-- Observações -->
            <div class="form-group">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"
                          placeholder="Informações adicionais sobre o veículo..."></textarea>
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px;">
                <a href="/veiculos" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary" id="btnSalvar">
                    <i class="fa fa-check"></i> Salvar Veículo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Detectar formato de placa em tempo real
document.getElementById('campoPlaca').addEventListener('input', function() {
    var v = this.value.replace(/[^A-Z0-9]/gi,'').toUpperCase().slice(0,7);
    this.value = v;
    detectarFormato(v, 'infoFormatoPlaca');
});
document.getElementById('inputPlacaBusca').addEventListener('input', function() {
    var v = this.value.replace(/[^A-Z0-9]/gi,'').toUpperCase().slice(0,7);
    this.value = v;
    detectarFormato(v, 'msgFormatoPlaca');
});

function detectarFormato(placa, elId) {
    var el = document.getElementById(elId);
    if (!el) return;
    if (placa.length < 7) { el.textContent = ''; el.className = 'text-sm mt-2'; return; }
    if (/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/.test(placa)) {
        el.textContent = '✓ Placa Mercosul detectada';
        el.className = 'text-sm mt-2 text-success';
    } else if (/^[A-Z]{3}[0-9]{4}$/.test(placa)) {
        el.textContent = '✓ Placa padrão (antiga) detectada';
        el.className = 'text-sm mt-2 text-success';
    } else {
        el.textContent = '⚠ Formato de placa não reconhecido';
        el.className = 'text-sm mt-2 text-danger';
    }
}

// Consultar placa via AJAX
function consultarPlaca() {
    var placa = document.getElementById('inputPlacaBusca').value.replace(/[^A-Z0-9]/gi,'').toUpperCase();
    if (placa.length < 7) { alert('Informe a placa completa (7 caracteres).'); return; }

    var btn = document.getElementById('btnConsultarPlaca');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Consultando...';

    var resultado = document.getElementById('resultadoConsulta');
    resultado.className = '';
    resultado.innerHTML = '<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Consultando APIs gratuitas...</div>';

    fetch('/veiculos/api/consultar-placa?placa=' + placa, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-search"></i> Consultar';

        if (data.success && data.dados) {
            var d = data.dados;
            resultado.innerHTML =
                '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Dados encontrados via ' + data.fonte + '</div>' +
                '<div style="background:#f8fafc; border-radius:8px; padding:16px; font-size:13px;">' +
                renderDadosVeiculo(d) +
                '<button type="button" class="btn btn-primary btn-sm" style="margin-top:12px;" onclick="preencherFormulario(' + JSON.stringify(d) + ')"><i class="fa fa-arrow-down"></i> Usar estes dados</button>' +
                '</div>';
        } else {
            resultado.innerHTML = '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' + (data.message || 'Placa não encontrada nas APIs gratuitas.') + '</div>';
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-search"></i> Consultar';
        resultado.innerHTML = '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Erro ao consultar. Preencha os dados manualmente.</div>';
    });
}

function renderDadosVeiculo(d) {
    var html = '<table style="width:100%; border-collapse:collapse;">';
    var campos = {
        'marca':'Marca','modelo':'Modelo','versao':'Versão',
        'ano':'Ano','cor':'Cor','combustivel':'Combustível',
        'municipio':'Município','uf':'UF','situacao':'Situação'
    };
    for (var k in campos) {
        if (d[k]) html += '<tr><td style="padding:4px 8px; font-weight:600; width:120px;">' + campos[k] + ':</td><td style="padding:4px 8px;">' + d[k] + '</td></tr>';
    }
    html += '</table>';
    return html;
}

function preencherFormulario(d) {
    if (d.placa)    document.getElementById('campoPlaca').value  = d.placa;
    if (d.marca)    document.getElementById('campoMarca').value  = d.marca;
    if (d.modelo)   document.getElementById('campoModelo').value = d.modelo;
    if (d.versao)   document.getElementById('campoVersao').value = d.versao;
    if (d.renavam)  document.getElementById('campoRenavam').value= d.renavam;
    if (d.chassi)   document.getElementById('campoChassi').value = d.chassi;
    if (d.cor) {
        var sel = document.getElementById('campoCor');
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].text.toLowerCase() === d.cor.toLowerCase()) {
                sel.selectedIndex = i; break;
            }
        }
    }
    if (d.combustivel) {
        var sel2 = document.getElementById('campoCombustivel');
        for (var j = 0; j < sel2.options.length; j++) {
            if (sel2.options[j].value === d.combustivel.toLowerCase()) {
                sel2.selectedIndex = j; break;
            }
        }
    }
    if (d.ano) {
        var anos = d.ano.toString().split('/');
        if (anos[0]) document.getElementById('campoAnoFab').value = anos[0].trim();
        if (anos[1]) document.getElementById('campoAnoMod').value = anos[1].trim();
    }
    // Copiar placa para campo de busca também
    document.getElementById('inputPlacaBusca').value = d.placa || '';
    document.getElementById('campoPlaca').value = d.placa || '';
    detectarFormato(d.placa || '', 'infoFormatoPlaca');
}

// OCR de documento
function processarOCR(input) {
    if (!input.files || !input.files[0]) return;
    var file = input.files[0];

    // Preview
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('imgPreviewOCR').src = e.target.result;
        document.getElementById('previewOCR').classList.remove('hidden');
    };
    reader.readAsDataURL(file);

    var status = document.getElementById('statusOCR');
    status.className = 'alert alert-info';
    status.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processando imagem com OCR...';

    var formData = new FormData();
    formData.append('imagem', file);
    formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

    fetch('/veiculos/api/ocr', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success && data.extraido) {
            status.className = 'alert alert-success';
            status.innerHTML = '<i class="fa fa-check-circle"></i> OCR concluído! Dados extraídos automaticamente.';
            preencherFormulario(data.extraido);
        } else {
            status.className = 'alert alert-warning';
            status.innerHTML = '<i class="fa fa-exclamation-triangle"></i> ' + (data.message || 'Não foi possível extrair dados. Preencha manualmente.');
        }
    })
    .catch(function() {
        status.className = 'alert alert-danger';
        status.innerHTML = '<i class="fa fa-times-circle"></i> Erro no OCR. Preencha os dados manualmente.';
    });
}

// Drag and drop na zona OCR
var dropZone = document.getElementById('dropZoneOCR');
dropZone.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor='var(--primary)'; });
dropZone.addEventListener('dragleave', function() { this.style.borderColor='var(--border)'; });
dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border)';
    var files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('inputOCR').files = files;
        processarOCR(document.getElementById('inputOCR'));
    }
});

// Submit
document.getElementById('formVeiculo').addEventListener('submit', function() {
    var btn = document.getElementById('btnSalvar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Salvando...';
});
</script>

<?php require_once dirname(__DIR__) . '/layout/app_footer.php'; ?>
