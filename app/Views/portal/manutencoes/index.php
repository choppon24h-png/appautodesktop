<?php $pageTitle = 'Manutenções'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-wrench me-2 text-success"></i>Oficina / Manutenções</h4>
        <p class="text-muted mb-0">Histórico completo de manutenções do veículo</p>
    </div>
    <a href="/portal/manutencoes/adicionar" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Nova Manutenção
    </a>
</div>

<?php if (empty($veiculoId)): ?>
<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>Selecione um veículo em <a href="/portal/veiculos">Meus Veículos</a>.</div>
<?php elseif (empty($manutencoes)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-wrench fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhuma manutenção registrada</h5>
    <p class="text-muted">Registre a primeira manutenção do seu veículo.</p>
    <a href="/portal/manutencoes/adicionar" class="btn btn-success"><i class="fas fa-plus me-2"></i>Registrar Manutenção</a>
</div>
<?php else: ?>
<div class="stat-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8fafc">
                <tr>
                    <th class="px-4 py-3">Tipo / Serviço</th>
                    <th>Data</th>
                    <th>KM</th>
                    <th>Oficina</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($manutencoes as $m): ?>
                <tr>
                    <td class="px-4 py-3">
                        <div class="fw-semibold"><?= htmlspecialchars($m['tipo']) ?></div>
                        <?php if (!empty($m['descricao'])): ?>
                        <small class="text-muted"><?= htmlspecialchars(substr($m['descricao'], 0, 60)) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($m['data_servico'])) ?></td>
                    <td><?= number_format($m['km_servico']) ?> km</td>
                    <td><?= htmlspecialchars($m['oficina_nome'] ?? '—') ?></td>
                    <td class="fw-bold text-success">R$ <?= number_format($m['valor'], 2, ',', '.') ?></td>
                    <td>
                        <a href="/portal/manutencoes/<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
