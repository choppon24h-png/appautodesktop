<?php $pageTitle = 'Bateria'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-battery-three-quarters me-2 text-success"></i>Bateria</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBateria">
        <i class="fas fa-plus me-2"></i>Registrar Bateria
    </button>
</div>

<?php if ($bateria): ?>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="fas fa-battery-full me-2 text-success"></i>Bateria Atual</h6>
            <?php
            $mesesUso = round((time() - strtotime($bateria['data_instalacao'])) / (30 * 86400));
            $vidaUtil = (int)($bateria['vida_util_meses'] ?? 48);
            $desgaste = $vidaUtil > 0 ? min(100, round($mesesUso / $vidaUtil * 100)) : 0;
            $cor = $desgaste < 50 ? '#16a34a' : ($desgaste < 80 ? '#f59e0b' : '#ef4444');
            ?>
            <div class="row g-2">
                <div class="col-6"><small class="text-muted">Marca</small><div class="fw-semibold"><?= htmlspecialchars($bateria['marca']) ?></div></div>
                <div class="col-6"><small class="text-muted">Modelo</small><div class="fw-semibold"><?= htmlspecialchars($bateria['modelo'] ?? '—') ?></div></div>
                <div class="col-6"><small class="text-muted">Amperagem</small><div class="fw-semibold"><?= htmlspecialchars($bateria['amperagem'] ?? '—') ?></div></div>
                <div class="col-6"><small class="text-muted">Instalação</small><div class="fw-semibold"><?= date('d/m/Y', strtotime($bateria['data_instalacao'])) ?></div></div>
                <div class="col-6"><small class="text-muted">Valor</small><div class="fw-semibold text-success">R$ <?= number_format($bateria['valor'], 2, ',', '.') ?></div></div>
                <div class="col-6"><small class="text-muted">Garantia</small><div class="fw-semibold"><?= $bateria['garantia_meses'] ?> meses</div></div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Desgaste estimado</small>
                    <small style="color:<?= $cor ?>"><?= $desgaste ?>%</small>
                </div>
                <div class="progress" style="height:10px">
                    <div class="progress-bar" style="width:<?= $desgaste ?>%;background:<?= $cor ?>"></div>
                </div>
                <small class="text-muted"><?= $mesesUso ?> meses de uso / <?= $vidaUtil ?> meses de vida útil</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="fas fa-phone me-2 text-primary"></i>Contatos de Emergência</h6>
            <div class="d-flex flex-column gap-2">
                <?php if (!empty($bateria['observacao'])): ?>
                <div class="p-3 rounded" style="background:#f0fdf4;border-left:3px solid #16a34a">
                    <small class="text-muted">Observações</small>
                    <div><?= htmlspecialchars($bateria['observacao']) ?></div>
                </div>
                <?php endif; ?>
                <a href="https://wa.me/55" class="btn btn-outline-success btn-sm">
                    <i class="fab fa-whatsapp me-2"></i>Chamar Assistência
                </a>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-battery-empty fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhuma bateria cadastrada</h5>
    <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#modalBateria">Registrar Bateria</button>
</div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="modalBateria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Registrar Bateria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/portal/bateria/salvar">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold">Marca *</label><input type="text" name="marca" class="form-control" required placeholder="Ex: Moura"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Modelo</label><input type="text" name="modelo" class="form-control" placeholder="Ex: MF60HD"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Amperagem</label><input type="text" name="amperagem" class="form-control" placeholder="Ex: 60Ah"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Data Instalação</label><input type="date" name="data_instalacao" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">KM Instalação</label><input type="number" name="km_instalacao" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Valor (R$)</label><input type="text" name="valor" class="form-control" placeholder="0,00"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Garantia (meses)</label><input type="number" name="garantia_meses" class="form-control" placeholder="Ex: 24"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Vida Útil (meses)</label><input type="number" name="vida_util_meses" class="form-control" value="48"></div>
                        <div class="col-12"><label class="form-label fw-semibold">Observações</label><textarea name="observacao" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4"><i class="fas fa-save me-2"></i>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
