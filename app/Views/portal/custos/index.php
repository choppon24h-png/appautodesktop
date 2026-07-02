<?php $pageTitle = 'Custos'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-chart-pie me-2 text-purple" style="color:#8b5cf6"></i>Custos do Veículo</h4>
        <p class="text-muted mb-0">Análise financeira — <?= $anoAtual ?></p>
    </div>
</div>

<!-- Total geral -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto" style="background:#fdf4ff;color:#9333ea"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-value">R$ <?= number_format($totalGeral ?? 0, 2, ',', '.') ?></div>
            <div class="stat-label">Total em <?= $anoAtual ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-calendar-month"></i></div>
            <div class="stat-value">R$ <?= number_format(($totalGeral ?? 0) / 12, 2, ',', '.') ?></div>
            <div class="stat-label">Média mensal</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-list"></i></div>
            <div class="stat-value"><?= count($custos ?? []) ?></div>
            <div class="stat-label">Lançamentos</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Gráfico de pizza -->
    <div class="col-md-5">
        <div class="stat-card h-100">
            <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2" style="color:#8b5cf6"></i>Por Categoria</h6>
            <?php if (!empty($totalPorCategoria)): ?>
            <canvas id="graficoCustos" height="220"></canvas>
            <?php else: ?>
            <div class="text-center py-4 text-muted"><i class="fas fa-chart-pie fa-3x mb-2"></i><p>Sem dados</p></div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Tabela por categoria -->
    <div class="col-md-7">
        <div class="stat-card h-100">
            <h6 class="fw-bold mb-3"><i class="fas fa-table me-2 text-primary"></i>Resumo por Categoria</h6>
            <?php if (!empty($totalPorCategoria)): ?>
            <?php
            $cores = ['manutencao'=>'#3b82f6','combustivel'=>'#f59e0b','pneus'=>'#64748b','bateria'=>'#10b981','seguro'=>'#ef4444','ipva'=>'#8b5cf6','multa'=>'#f97316','outros'=>'#94a3b8'];
            $labelsCategoria = ['manutencao'=>'Manutenção','combustivel'=>'Combustível','pneus'=>'Pneus','bateria'=>'Bateria','seguro'=>'Seguro','ipva'=>'IPVA/Licenciamento','multa'=>'Multas','outros'=>'Outros'];
            foreach ($totalPorCategoria as $cat):
                $pct = $totalGeral > 0 ? round($cat['total'] / $totalGeral * 100) : 0;
                $cor = $cores[$cat['categoria']] ?? '#94a3b8';
                $label = $labelsCategoria[$cat['categoria']] ?? ucfirst($cat['categoria']);
            ?>
            <div class="d-flex align-items-center mb-2">
                <div style="width:12px;height:12px;background:<?= $cor ?>;border-radius:50%;flex-shrink:0"></div>
                <span class="ms-2 flex-grow-1 small"><?= $label ?></span>
                <div class="progress mx-2 flex-grow-1" style="height:6px">
                    <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $cor ?>"></div>
                </div>
                <span class="fw-bold small" style="min-width:80px;text-align:right">R$ <?= number_format($cat['total'], 2, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="text-center py-4 text-muted">Nenhum custo registrado em <?= $anoAtual ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Últimos lançamentos -->
<?php if (!empty($custos)): ?>
<div class="stat-card p-0 overflow-hidden">
    <div class="px-4 py-3 border-bottom"><h6 class="fw-bold mb-0">Últimos Lançamentos</h6></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8fafc">
                <tr>
                    <th class="px-4 py-3">Data</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($custos as $c): ?>
                <tr>
                    <td class="px-4 py-3"><?= date('d/m/Y', strtotime($c['data_custo'])) ?></td>
                    <td><span class="badge" style="background:<?= $cores[$c['categoria']] ?? '#94a3b8' ?>;color:#fff"><?= $labelsCategoria[$c['categoria']] ?? ucfirst($c['categoria']) ?></span></td>
                    <td><?= htmlspecialchars($c['descricao']) ?></td>
                    <td class="fw-bold text-danger">R$ <?= number_format($c['valor'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($totalPorCategoria)):
    $labelsJson = json_encode(array_map(fn($c) => $labelsCategoria[$c['categoria']] ?? ucfirst($c['categoria']), $totalPorCategoria));
    $valoresJson = json_encode(array_column($totalPorCategoria, 'total'));
    $coresJson = json_encode(array_map(fn($c) => $cores[$c['categoria']] ?? '#94a3b8', $totalPorCategoria));
?>
<script>
new Chart(document.getElementById('graficoCustos'), {
    type: 'doughnut',
    data: {
        labels: <?= $labelsJson ?>,
        datasets: [{ data: <?= $valoresJson ?>, backgroundColor: <?= $coresJson ?> }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
