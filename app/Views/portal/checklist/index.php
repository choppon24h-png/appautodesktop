<?php $pageTitle = 'Checklist'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-clipboard-check me-2 text-info"></i>Checklist</h4>
    <div class="d-flex gap-2">
        <a href="/portal/checklist/novo?tipo=viagem" class="btn btn-info text-white">
            <i class="fas fa-road me-2"></i>Checklist Viagem
        </a>
        <a href="/portal/checklist/novo?tipo=preventivo" class="btn btn-outline-info">
            <i class="fas fa-tools me-2"></i>Preventivo
        </a>
    </div>
</div>

<?php if (empty($checklists)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhum checklist realizado</h5>
    <a href="/portal/checklist/novo?tipo=viagem" class="btn btn-info text-white mt-2">Criar Checklist de Viagem</a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($checklists as $c): ?>
    <div class="col-md-6 col-lg-4">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="fw-bold mb-0"><?= htmlspecialchars($c['titulo']) ?></h6>
                <span class="badge" style="background:<?= $c['tipo'] === 'viagem' ? '#06b6d4' : '#8b5cf6' ?>;color:#fff">
                    <?= ucfirst($c['tipo']) ?>
                </span>
            </div>
            <div class="text-muted small"><?= date('d/m/Y', strtotime($c['data_checklist'])) ?></div>
            <?php if (!empty($c['observacao'])): ?>
            <p class="text-muted small mt-2"><?= htmlspecialchars($c['observacao']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
