<?php
$pageTitle = 'Editar Veículo';
require_once __DIR__ . '/../../layout/portal_header.php';
$v = $veiculo;
?>

<div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
    <a href="/portal/veiculos" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h4 style="font-weight:700; margin:0;">Editar Veículo</h4>
        <p style="color:var(--text-light); font-size:13px; margin:4px 0 0;">
            <?php
            $p = strtoupper(preg_replace('/[^A-Z0-9]/i','', $v->placa ?? ''));
            echo htmlspecialchars((strlen($p)===7) ? substr($p,0,3).'-'.substr($p,3) : $p);
            ?>
            — <?php echo htmlspecialchars(trim(($v->marca ?? '') . ' ' . ($v->modelo ?? ''))); ?>
        </p>
    </div>
</div>

<?php if (!empty($erros)): ?>
<div class="alert alert-danger mb-4">
    <i class="fas fa-exclamation-circle"></i>
    <ul style="margin:0; padding-left:18px;">
        <?php foreach ($erros as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" action="/portal/veiculos/<?php echo $v->id; ?>/editar" enctype="multipart/form-data">
    <?php echo \App\Core\View::csrfField(); ?>

    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title-sm"><i class="fas fa-id-card me-2"></i>Identificação</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Placa <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="placa" class="form-control" required
                           value="<?php echo htmlspecialchars($dados['placa'] ?? $v->placa ?? ''); ?>"
                           style="font-family:monospace; font-size:16px; font-weight:700; text-transform:uppercase; letter-spacing:2px;" maxlength="8">
                </div>
                <div class="form-group">
                    <label class="form-label">RENAVAM</label>
                    <input type="text" name="renavam" class="form-control"
                           value="<?php echo htmlspecialchars($dados['renavam'] ?? $v->renavam ?? ''); ?>" maxlength="11">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Chassi</label>
                    <input type="text" name="chassi" class="form-control"
                           value="<?php echo htmlspecialchars($dados['chassi'] ?? $v->chassi ?? ''); ?>"
                           maxlength="17" style="text-transform:uppercase;">
                </div>
                <div class="form-group">
                    <label class="form-label">Cor</label>
                    <select name="cor" class="form-control">
                        <option value="">Selecione...</option>
                        <?php foreach (['Branca','Preta','Prata','Cinza','Vermelha','Azul','Verde','Amarela','Laranja','Marrom','Bege','Vinho','Dourada','Rosa','Roxa','Outra'] as $cor): ?>
                        <?php $sel = ($dados['cor'] ?? $v->cor ?? '') === $cor ? 'selected' : ''; ?>
                        <option value="<?php echo $cor; ?>" <?php echo $sel; ?>><?php echo $cor; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title-sm"><i class="fas fa-car me-2"></i>Dados do Veículo</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Marca</label>
                    <input type="text" name="marca" class="form-control"
                           value="<?php echo htmlspecialchars($dados['marca'] ?? $v->marca ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" class="form-control"
                           value="<?php echo htmlspecialchars($dados['modelo'] ?? $v->modelo ?? ''); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Versão</label>
                    <input type="text" name="versao" class="form-control"
                           value="<?php echo htmlspecialchars($dados['versao'] ?? $v->versao ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Combustível</label>
                    <select name="combustivel" class="form-control">
                        <option value="">Selecione...</option>
                        <?php foreach (['gasolina'=>'Gasolina','etanol'=>'Etanol','flex'=>'Flex','diesel'=>'Diesel','gnv'=>'GNV','eletrico'=>'Elétrico','hibrido'=>'Híbrido'] as $val=>$lbl): ?>
                        <?php $sel = ($dados['combustivel'] ?? $v->combustivel ?? '') === $val ? 'selected' : ''; ?>
                        <option value="<?php echo $val; ?>" <?php echo $sel; ?>><?php echo $lbl; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ano Fabricação</label>
                    <input type="number" name="ano_fabricacao" class="form-control"
                           value="<?php echo htmlspecialchars($dados['ano_fabricacao'] ?? $v->ano_fabricacao ?? ''); ?>"
                           min="1900" max="<?php echo date('Y')+1; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Ano Modelo</label>
                    <input type="number" name="ano_modelo" class="form-control"
                           value="<?php echo htmlspecialchars($dados['ano_modelo'] ?? $v->ano_modelo ?? ''); ?>"
                           min="1900" max="<?php echo date('Y')+2; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-control">
                        <option value="">Selecione...</option>
                        <?php foreach (['passeio'=>'Passeio','utilitario'=>'Utilitário','suv'=>'SUV','pickup'=>'Pickup','caminhao'=>'Caminhão','moto'=>'Moto','onibus'=>'Ônibus','van'=>'Van','outro'=>'Outro'] as $val=>$lbl): ?>
                        <?php $sel = ($dados['categoria'] ?? $v->categoria ?? '') === $val ? 'selected' : ''; ?>
                        <option value="<?php echo $val; ?>" <?php echo $sel; ?>><?php echo $lbl; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">KM Atual</label>
                    <input type="number" name="km_atual" class="form-control"
                           value="<?php echo htmlspecialchars($dados['km_atual'] ?? $v->km_atual ?? ''); ?>" min="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($dados['observacoes'] ?? $v->observacoes ?? ''); ?></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title-sm"><i class="fas fa-images me-2"></i>Adicionar Fotos</span>
        </div>
        <div class="card-body">
            <input type="file" name="fotos[]" class="form-control" accept="image/*" multiple>
            <p style="font-size:12px; color:var(--text-light); margin-top:6px;">Selecione novas fotos para adicionar ao veículo</p>
        </div>
    </div>

    <div style="display:flex; gap:12px; justify-content:space-between;">
        <form method="POST" action="/portal/veiculos/<?php echo $v->id; ?>/excluir" onsubmit="return confirm('Excluir este veículo? Esta ação não pode ser desfeita.');">
            <?php echo \App\Core\View::csrfField(); ?>
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Excluir Veículo
            </button>
        </form>
        <div style="display:flex; gap:12px;">
            <a href="/portal/veiculos" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Salvar Alterações
            </button>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
