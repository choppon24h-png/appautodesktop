<?php $pageTitle = 'Agenda Inteligente'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-2 text-info"></i>Agenda Inteligente</h4>
    <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalAgenda">
        <i class="fas fa-plus me-2"></i>Novo Agendamento
    </button>
</div>

<?php if (empty($agendas)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhum agendamento cadastrado</h5>
    <p class="text-muted">Programe revisões, trocas de óleo e outros serviços.</p>
    <button class="btn btn-info text-white mt-2" data-bs-toggle="modal" data-bs-target="#modalAgenda">Criar Agendamento</button>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($agendas as $a):
        $hoje = time();
        $previsto = !empty($a['data_prevista']) ? strtotime($a['data_prevista']) : null;
        $diasRestantes = $previsto ? round(($previsto - $hoje) / 86400) : null;
        $cor = $a['concluido'] ? '#16a34a' : ($diasRestantes !== null && $diasRestantes < 0 ? '#ef4444' : ($diasRestantes !== null && $diasRestantes <= 7 ? '#f59e0b' : '#3b82f6'));
        $statusLabel = $a['concluido'] ? 'Concluído' : ($diasRestantes !== null && $diasRestantes < 0 ? 'Atrasado' : ($diasRestantes !== null && $diasRestantes <= 7 ? 'Urgente' : 'Pendente'));
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="stat-card h-100" style="border-left:4px solid <?= $cor ?>">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="fw-bold mb-0"><?= htmlspecialchars($a['tipo_servico']) ?></h6>
                <span class="badge" style="background:<?= $cor ?>;color:#fff"><?= $statusLabel ?></span>
            </div>
            <?php if (!empty($a['descricao'])): ?>
            <p class="text-muted small mb-2"><?= htmlspecialchars($a['descricao']) ?></p>
            <?php endif; ?>
            <div class="d-flex gap-3 mt-2">
                <?php if (!empty($a['data_prevista'])): ?>
                <div><small class="text-muted">Data</small><div class="fw-semibold small"><?= date('d/m/Y', strtotime($a['data_prevista'])) ?></div></div>
                <?php endif; ?>
                <?php if ($a['km_previsto'] > 0): ?>
                <div><small class="text-muted">KM</small><div class="fw-semibold small"><?= number_format($a['km_previsto']) ?> km</div></div>
                <?php endif; ?>
                <?php if ($a['intervalo_km'] > 0): ?>
                <div><small class="text-muted">Intervalo</small><div class="fw-semibold small">a cada <?= number_format($a['intervalo_km']) ?> km</div></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="modalAgenda" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-calendar-alt me-2"></i>Novo Agendamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/portal/agenda/salvar">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tipo de Serviço *</label>
                            <select name="tipo_servico" class="form-select" required>
                                <option>Troca de Óleo</option>
                                <option>Revisão Geral</option>
                                <option>Troca de Pneus</option>
                                <option>Alinhamento e Balanceamento</option>
                                <option>Correia Dentada</option>
                                <option>Pastilha de Freio</option>
                                <option>Renovação do Seguro</option>
                                <option>IPVA / Licenciamento</option>
                                <option>Vistoria</option>
                                <option>Outros</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data Prevista</label>
                            <input type="date" name="data_prevista" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">KM Previsto</label>
                            <input type="number" name="km_previsto" class="form-control" placeholder="Ex: 90000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Intervalo (km)</label>
                            <input type="number" name="intervalo_km" class="form-control" placeholder="Ex: 10000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Intervalo (dias)</label>
                            <input type="number" name="intervalo_dias" class="form-control" placeholder="Ex: 180">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white px-4"><i class="fas fa-save me-2"></i>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
