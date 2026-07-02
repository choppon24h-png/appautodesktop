<?php $pageTitle = 'Novo Checklist'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/portal/checklist" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><i class="fas fa-clipboard-check me-2 text-info"></i>
        <?= $tipo === 'viagem' ? 'Checklist de Viagem' : 'Checklist Preventivo' ?>
    </h4>
</div>

<div class="stat-card" style="max-width:600px">
    <form method="POST" action="/portal/checklist/salvar">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo) ?>">

        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Título</label>
                <input type="text" name="titulo" class="form-control" value="Checklist <?= ucfirst($tipo) ?> — <?= date('d/m/Y') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Data</label>
                <input type="date" name="data_checklist" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <h6 class="fw-bold mb-3">Itens de Verificação</h6>
        <div id="listaItens">
            <?php foreach ($itens as $i => $item): ?>
            <div class="d-flex align-items-center gap-2 mb-2 item-row">
                <input type="hidden" name="itens[<?= $i ?>]" value="<?= htmlspecialchars($item) ?>">
                <input type="checkbox" name="marcados[<?= $i ?>]" class="form-check-input mt-0" style="width:20px;height:20px">
                <span class="flex-grow-1"><?= htmlspecialchars($item) ?></span>
                <select name="status[<?= $i ?>]" class="form-select form-select-sm" style="width:100px">
                    <option value="ok">OK</option>
                    <option value="atencao">Atenção</option>
                    <option value="critico">Crítico</option>
                    <option value="na">N/A</option>
                </select>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-3">
            <label class="form-label fw-semibold">Observações Gerais</label>
            <textarea name="observacao" class="form-control" rows="3" placeholder="Observações sobre o checklist..."></textarea>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-info text-white px-4"><i class="fas fa-save me-2"></i>Salvar Checklist</button>
            <a href="/portal/checklist" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
