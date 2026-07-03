<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../layout/portal_header.php';
?>

<!-- Auto-selecionar primeiro veículo se não houver ativo -->
<?php
if (empty($_SESSION['veiculo_ativo_id']) && !empty($totalVeiculos) && $totalVeiculos > 0) {
    // Buscar o primeiro veículo do usuário e ativá-lo automaticamente
    try {
        $dbAuto = \App\Core\Database::getInstance();
        $stmtAuto = $dbAuto->prepare("SELECT id, placa, modelo FROM veiculos WHERE usuario_id = ? ORDER BY criado_em ASC LIMIT 1");
        $stmtAuto->execute([$_SESSION['user_id']]);
        $vAuto = $stmtAuto->fetch(\PDO::FETCH_ASSOC);
        if ($vAuto) {
            $_SESSION['veiculo_ativo_id']    = $vAuto['id'];
            $_SESSION['veiculo_ativo_placa'] = $vAuto['placa'];
            $_SESSION['veiculo_ativo_modelo']= $vAuto['modelo'] ?? '';
        }
    } catch (\Exception $e) {}
}
?>
<!-- Alerta de veículo não selecionado -->
<?php if (empty($_SESSION['veiculo_ativo_id'])): ?>
<div class="alert alert-warning d-flex align-items-center gap-3 mb-4" role="alert">
    <i class="fas fa-car fa-2x"></i>
    <div>
        <strong>Nenhum veículo selecionado.</strong>
        Selecione ou cadastre um veículo para acessar todos os módulos.
        <a href="/portal/veiculos" class="btn btn-sm btn-warning ms-2">
            <i class="fas fa-car me-1"></i>Meus Veículos
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Cards de resumo -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-car"></i></div>
            <div class="stat-value"><?= $totalVeiculos ?? 0 ?></div>
            <div class="stat-label">Veículos cadastrados</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-wrench"></i></div>
            <div class="stat-value"><?= $totalManutencoes ?? 0 ?></div>
            <div class="stat-label">Manutenções registradas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fefce8;color:#ca8a04"><i class="fas fa-gas-pump"></i></div>
            <div class="stat-value"><?= $totalAbastecimentos ?? 0 ?></div>
            <div class="stat-label">Abastecimentos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;color:#9333ea"><i class="fas fa-folder-open"></i></div>
            <div class="stat-value"><?= $totalDocumentos ?? 0 ?></div>
            <div class="stat-label">Documentos</div>
        </div>
    </div>
</div>

<div class="row g-3">

    <!-- Score do veículo -->
    <div class="col-md-4">
        <div class="stat-card h-100">
            <h6 class="fw-bold mb-3"><i class="fas fa-star me-2 text-warning"></i>AppAuto Score</h6>
            <?php
            $score = $scoreVeiculo ?? 0;
            $scoreClass = $score >= 80 ? 'score-excelente' : ($score >= 60 ? 'score-bom' : ($score >= 40 ? 'score-regular' : 'score-ruim'));
            $scoreLabel = $score >= 80 ? 'Excelente' : ($score >= 60 ? 'Bom' : ($score >= 40 ? 'Regular' : 'Precisa atenção'));
            ?>
            <div class="text-center py-3">
                <div class="score-badge <?= $scoreClass ?> mx-auto mb-3"><?= $score ?></div>
                <div class="fw-bold"><?= $scoreLabel ?></div>
                <small class="text-muted">Calculado com base em manutenção, documentos, pneus, bateria e seguro</small>
            </div>
            <div class="mt-3">
                <?php
                $itens = [
                    ['Manutenção', $ptsManu ?? 0, '#3b82f6'],
                    ['Documentos', $ptsDocs ?? 0, '#8b5cf6'],
                    ['Pneus', $ptsPneus ?? 0, '#f59e0b'],
                    ['Bateria', $ptsBat ?? 0, '#10b981'],
                    ['Seguro', $ptsSeg ?? 0, '#ef4444'],
                ];
                foreach ($itens as $item): ?>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small><?= $item[0] ?></small>
                    <div class="progress flex-grow-1 mx-2" style="height:6px">
                        <div class="progress-bar" style="width:<?= $item[1] ?>%;background:<?= $item[2] ?>"></div>
                    </div>
                    <small class="text-muted"><?= $item[1] ?>%</small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Alertas e agenda -->
    <div class="col-md-4">
        <div class="stat-card h-100">
            <h6 class="fw-bold mb-3"><i class="fas fa-bell me-2 text-danger"></i>Alertas Pendentes</h6>
            <?php if (!empty($alertas)): ?>
                <?php foreach ($alertas as $alerta): ?>
                <div class="d-flex align-items-start gap-2 mb-3 p-2 rounded" style="background:#fef2f2;border-left:3px solid #ef4444">
                    <i class="fas fa-exclamation-triangle text-danger mt-1"></i>
                    <div>
                        <div class="fw-semibold small"><?= htmlspecialchars($alerta['titulo']) ?></div>
                        <div class="text-muted" style="font-size:.75rem"><?= htmlspecialchars($alerta['descricao']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <p class="mb-0 small">Nenhum alerta pendente</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Últimas manutenções -->
    <div class="col-md-4">
        <div class="stat-card h-100">
            <h6 class="fw-bold mb-3"><i class="fas fa-wrench me-2 text-primary"></i>Últimas Manutenções</h6>
            <?php if (!empty($ultimasManutencoes)): ?>
                <?php foreach ($ultimasManutencoes as $m): ?>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <div class="fw-semibold small"><?= htmlspecialchars($m['tipo']) ?></div>
                        <div class="text-muted" style="font-size:.75rem">
                            <?= date('d/m/Y', strtotime($m['data_servico'])) ?> &bull;
                            <?= number_format($m['km_servico']) ?> km
                        </div>
                    </div>
                    <span class="fw-bold text-success small">R$ <?= number_format($m['valor'], 2, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-wrench fa-2x mb-2"></i>
                    <p class="mb-0 small">Nenhuma manutenção registrada</p>
                    <a href="/portal/manutencoes/adicionar" class="btn btn-sm btn-primary mt-2">Registrar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Ações rápidas -->
<div class="row g-3 mt-2">
    <div class="col-12">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="fas fa-bolt me-2 text-warning"></i>Ações Rápidas</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="/portal/veiculos/adicionar" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Novo Veículo
                </a>
                <a href="/portal/manutencoes/adicionar" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-wrench me-1"></i>Registrar Manutenção
                </a>
                <a href="/portal/abastecimentos/adicionar" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-gas-pump me-1"></i>Registrar Abastecimento
                </a>
                <a href="/portal/documentos/adicionar" class="btn btn-outline-purple btn-sm" style="border-color:#8b5cf6;color:#8b5cf6">
                    <i class="fas fa-upload me-1"></i>Enviar Documento
                </a>
                <a href="/portal/checklist/novo" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-clipboard-check me-1"></i>Checklist Viagem
                </a>
                <a href="/portal/ia" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-robot me-1"></i>Perguntar à IA
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/portal_footer.php'; ?>
