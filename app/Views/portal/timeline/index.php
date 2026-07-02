<?php $pageTitle = 'Linha do Tempo'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="mb-4">
    <h4 class="fw-bold mb-1"><i class="fas fa-history me-2 text-purple" style="color:#8b5cf6"></i>Linha do Tempo do Veículo</h4>
    <p class="text-muted mb-0">Histórico completo de todos os eventos registrados</p>
</div>

<?php
$icones = ['manutencao'=>['fas fa-wrench','#3b82f6'],'combustivel'=>['fas fa-gas-pump','#f59e0b'],'pneus'=>['fas fa-circle-notch','#64748b'],'bateria'=>['fas fa-battery-full','#10b981'],'seguro'=>['fas fa-shield-alt','#ef4444'],'documento'=>['fas fa-file-alt','#8b5cf6'],'foto'=>['fas fa-camera','#ec4899'],'compra'=>['fas fa-shopping-cart','#06b6d4'],'outro'=>['fas fa-circle','#94a3b8']];
?>

<?php if (empty($eventos)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-history fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhum evento registrado ainda</h5>
    <p class="text-muted">Os eventos são criados automaticamente ao registrar manutenções, abastecimentos e outros dados.</p>
</div>
<?php else: ?>
<div class="timeline-container" style="position:relative;padding-left:40px">
    <div style="position:absolute;left:19px;top:0;bottom:0;width:2px;background:#e2e8f0"></div>
    <?php foreach ($eventos as $e):
        $tipo = $e['tipo'] ?? 'outro';
        [$icone, $cor] = $icones[$tipo] ?? ['fas fa-circle', '#94a3b8'];
    ?>
    <div class="timeline-item mb-4" style="position:relative">
        <div style="position:absolute;left:-30px;top:4px;width:22px;height:22px;background:<?= $cor ?>;border-radius:50%;display:flex;align-items:center;justify-content:center">
            <i class="<?= $icone ?>" style="color:#fff;font-size:.6rem"></i>
        </div>
        <div class="stat-card py-3 px-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold"><?= htmlspecialchars($e['titulo']) ?></div>
                    <?php if (!empty($e['descricao'])): ?>
                    <div class="text-muted small"><?= htmlspecialchars($e['descricao']) ?></div>
                    <?php endif; ?>
                    <div class="d-flex gap-3 mt-1">
                        <?php if ($e['km_evento'] > 0): ?>
                        <small class="text-muted"><i class="fas fa-tachometer-alt me-1"></i><?= number_format($e['km_evento']) ?> km</small>
                        <?php endif; ?>
                        <?php if ($e['valor'] > 0): ?>
                        <small class="text-success fw-semibold"><i class="fas fa-dollar-sign me-1"></i>R$ <?= number_format($e['valor'], 2, ',', '.') ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-muted"><?= date('d/m/Y', strtotime($e['data_evento'])) ?></small>
                    <div><span class="badge" style="background:<?= $cor ?>;color:#fff;font-size:.65rem"><?= ucfirst($tipo) ?></span></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
