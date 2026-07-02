<?php $pageTitle = 'Seguro'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-shield-alt me-2 text-danger"></i>Seguro do Veículo</h4>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalSeguro">
        <i class="fas fa-plus me-2"></i>Adicionar Seguro
    </button>
</div>

<?php if (empty($seguros)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhum seguro cadastrado</h5>
    <button class="btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#modalSeguro">Adicionar Seguro</button>
</div>
<?php else: ?>
<?php foreach ($seguros as $s):
    $hoje = time();
    $venc = strtotime($s['data_vencimento']);
    $diasRestantes = round(($venc - $hoje) / 86400);
    $statusCor = $diasRestantes < 0 ? '#ef4444' : ($diasRestantes < 30 ? '#f59e0b' : '#16a34a');
    $statusLabel = $diasRestantes < 0 ? 'Vencido' : ($diasRestantes < 30 ? "Vence em {$diasRestantes} dias" : 'Ativo');
?>
<div class="stat-card mb-3">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($s['seguradora']) ?></h5>
            <span class="badge" style="background:<?= $statusCor ?>;color:#fff"><?= $statusLabel ?></span>
        </div>
        <div class="text-end">
            <div class="fw-bold text-success" style="font-size:1.2rem">R$ <?= number_format($s['valor_premio'], 2, ',', '.') ?></div>
            <small class="text-muted">Prêmio anual</small>
        </div>
    </div>
    <div class="row g-2">
        <div class="col-md-3"><small class="text-muted">Apólice</small><div class="fw-semibold"><?= htmlspecialchars($s['apolice'] ?? '—') ?></div></div>
        <div class="col-md-3"><small class="text-muted">Vigência</small><div class="fw-semibold"><?= date('d/m/Y', strtotime($s['data_inicio'])) ?> a <?= date('d/m/Y', strtotime($s['data_vencimento'])) ?></div></div>
        <div class="col-md-3"><small class="text-muted">Franquia</small><div class="fw-semibold">R$ <?= number_format($s['franquia'], 2, ',', '.') ?></div></div>
        <div class="col-md-3"><small class="text-muted">Corretor</small><div class="fw-semibold"><?= htmlspecialchars($s['corretor_nome'] ?? '—') ?></div></div>
    </div>
    <?php if (!empty($s['assistencia_tel']) || !empty($s['guincho_tel'])): ?>
    <div class="mt-3 d-flex gap-2">
        <?php if (!empty($s['assistencia_tel'])): ?>
        <a href="tel:<?= $s['assistencia_tel'] ?>" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-phone me-1"></i>Assistência: <?= htmlspecialchars($s['assistencia_tel']) ?>
        </a>
        <?php endif; ?>
        <?php if (!empty($s['guincho_tel'])): ?>
        <a href="tel:<?= $s['guincho_tel'] ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-truck me-1"></i>Guincho: <?= htmlspecialchars($s['guincho_tel']) ?>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="modalSeguro" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-shield-alt me-2"></i>Adicionar Seguro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/portal/seguro/salvar">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold">Seguradora *</label><input type="text" name="seguradora" class="form-control" required placeholder="Ex: Porto Seguro"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Número da Apólice</label><input type="text" name="apolice" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Início da Vigência</label><input type="date" name="data_inicio" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Vencimento</label><input type="date" name="data_vencimento" class="form-control" value="<?= date('Y-m-d', strtotime('+1 year')) ?>"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Prêmio (R$)</label><input type="text" name="valor_premio" class="form-control" placeholder="0,00"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Franquia (R$)</label><input type="text" name="franquia" class="form-control" placeholder="0,00"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Corretor</label><input type="text" name="corretor_nome" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Tel. Corretor</label><input type="text" name="corretor_tel" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Tel. Assistência 24h</label><input type="text" name="assistencia_tel" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Tel. Guincho</label><input type="text" name="guincho_tel" class="form-control"></div>
                        <div class="col-12"><label class="form-label fw-semibold">Observações</label><textarea name="observacao" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4"><i class="fas fa-save me-2"></i>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
