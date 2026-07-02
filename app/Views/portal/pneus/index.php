<?php $pageTitle = 'Pneus'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-circle-notch me-2 text-secondary"></i>Pneus</h4>
        <p class="text-muted mb-0">Controle e diagrama visual dos pneus</p>
    </div>
    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalPneu">
        <i class="fas fa-plus me-2"></i>Registrar Pneu
    </button>
</div>

<?php if (empty($veiculoId)): ?>
<div class="alert alert-warning">Selecione um veículo em <a href="/portal/veiculos">Meus Veículos</a>.</div>
<?php else: ?>

<!-- Diagrama visual do veículo -->
<div class="stat-card mb-4">
    <h6 class="fw-bold mb-4 text-center"><i class="fas fa-car me-2"></i>Diagrama dos Pneus</h6>
    <?php
    $pneuMap = [];
    foreach ($pneus as $p) { $pneuMap[$p['posicao']] = $p; }
    $posicoes = [
        ['dianteiro_esq', 'Dianteiro Esq.', 'top-left'],
        ['dianteiro_dir', 'Dianteiro Dir.', 'top-right'],
        ['traseiro_esq', 'Traseiro Esq.', 'bottom-left'],
        ['traseiro_dir', 'Traseiro Dir.', 'bottom-right'],
        ['estepe', 'Estepe', 'center'],
    ];
    ?>
    <div class="car-diagram mx-auto" style="max-width:420px;position:relative;min-height:300px">
        <!-- Corpo do carro -->
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:140px;height:220px;background:linear-gradient(135deg,#1e3a5f,#2563eb);border-radius:20px;opacity:.15"></div>
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;color:#1e3a5f;font-size:.7rem;font-weight:600;line-height:1.3">
            <i class="fas fa-car fa-2x"></i><br>Veículo
        </div>

        <?php
        $posStyle = [
            'dianteiro_esq' => 'top:10%;left:5%',
            'dianteiro_dir' => 'top:10%;right:5%',
            'traseiro_esq'  => 'bottom:10%;left:5%',
            'traseiro_dir'  => 'bottom:10%;right:5%',
            'estepe'        => 'bottom:5%;left:50%;transform:translateX(-50%)',
        ];
        foreach ($posicoes as [$pos, $label, $cls]):
            $p = $pneuMap[$pos] ?? null;
            $kmAtual = 80000; // Seria buscado do veículo
            $vidaUtil = $p ? (int)($p['vida_util_km'] ?? 50000) : 50000;
            $kmInst = $p ? (int)($p['km_instalacao'] ?? 0) : 0;
            $kmRodados = $kmAtual - $kmInst;
            $desgaste = $vidaUtil > 0 ? min(100, round($kmRodados / $vidaUtil * 100)) : 0;
            $cor = $desgaste < 50 ? '#16a34a' : ($desgaste < 80 ? '#f59e0b' : '#ef4444');
        ?>
        <div style="position:absolute;<?= $posStyle[$pos] ?>;width:90px;cursor:pointer"
             onclick="selecionarPosicao('<?= $pos ?>', '<?= $label ?>')">
            <div style="background:#fff;border:2px solid <?= $cor ?>;border-radius:8px;padding:6px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.1)">
                <div style="font-size:.6rem;font-weight:700;color:#64748b;margin-bottom:2px"><?= $label ?></div>
                <?php if ($p): ?>
                    <div style="font-size:.7rem;font-weight:600;color:#1e293b"><?= htmlspecialchars($p['marca']) ?></div>
                    <div style="font-size:.6rem;color:#64748b"><?= htmlspecialchars($p['medida'] ?? '') ?></div>
                    <div class="mt-1" style="height:4px;background:#e2e8f0;border-radius:2px">
                        <div style="height:4px;width:<?= $desgaste ?>%;background:<?= $cor ?>;border-radius:2px"></div>
                    </div>
                    <div style="font-size:.55rem;color:<?= $cor ?>"><?= $desgaste ?>% desgaste</div>
                <?php else: ?>
                    <div style="font-size:.65rem;color:#94a3b8">Não cadastrado</div>
                    <div style="font-size:1.2rem;color:#cbd5e1">+</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Lista de pneus -->
<?php if (!empty($pneus)): ?>
<div class="stat-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8fafc">
                <tr>
                    <th class="px-4 py-3">Posição</th>
                    <th>Marca / Modelo</th>
                    <th>Medida</th>
                    <th>Instalação</th>
                    <th>Calibragem</th>
                    <th>Valor</th>
                    <th>Garantia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pneus as $p): ?>
                <tr>
                    <td class="px-4 py-3 fw-semibold"><?= htmlspecialchars($p['posicao']) ?></td>
                    <td><?= htmlspecialchars($p['marca']) ?> <?= htmlspecialchars($p['modelo'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['medida'] ?? '—') ?></td>
                    <td><?= date('d/m/Y', strtotime($p['data_instalacao'])) ?></td>
                    <td><?= $p['calibragem'] ?? '—' ?> PSI</td>
                    <td>R$ <?= number_format($p['valor'], 2, ',', '.') ?></td>
                    <td><?= $p['garantia_meses'] ?? '—' ?> meses</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Modal de cadastro de pneu -->
<div class="modal fade" id="modalPneu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-circle-notch me-2"></i>Registrar Pneu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/portal/pneus/salvar">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Posição *</label>
                            <select name="posicao" id="posicaoSelect" class="form-select" required>
                                <option value="dianteiro_esq">Dianteiro Esquerdo</option>
                                <option value="dianteiro_dir">Dianteiro Direito</option>
                                <option value="traseiro_esq">Traseiro Esquerdo</option>
                                <option value="traseiro_dir">Traseiro Direito</option>
                                <option value="estepe">Estepe</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Marca *</label>
                            <input type="text" name="marca" class="form-control" required placeholder="Ex: Pirelli">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Modelo</label>
                            <input type="text" name="modelo" class="form-control" placeholder="Ex: Cinturato P1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Medida</label>
                            <input type="text" name="medida" class="form-control" placeholder="Ex: 195/65 R15">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data Instalação</label>
                            <input type="date" name="data_instalacao" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">KM Instalação</label>
                            <input type="number" name="km_instalacao" class="form-control" placeholder="Ex: 82000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Valor (R$)</label>
                            <input type="text" name="valor" class="form-control" placeholder="0,00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Calibragem (PSI)</label>
                            <input type="number" name="calibragem" class="form-control" value="32" step="0.5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Garantia (meses)</label>
                            <input type="number" name="garantia_meses" class="form-control" placeholder="Ex: 24">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Vida Útil (km)</label>
                            <input type="number" name="vida_util_km" class="form-control" placeholder="Ex: 50000">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary px-4"><i class="fas fa-save me-2"></i>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selecionarPosicao(pos, label) {
    document.getElementById('posicaoSelect').value = pos;
    new bootstrap.Modal(document.getElementById('modalPneu')).show();
}
</script>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
