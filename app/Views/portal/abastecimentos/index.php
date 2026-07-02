<?php $pageTitle = 'Abastecimentos'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-gas-pump me-2 text-warning"></i>Abastecimentos</h4>
        <p class="text-muted mb-0">Controle de combustível e consumo</p>
    </div>
    <a href="/portal/abastecimentos/adicionar" class="btn btn-warning text-dark">
        <i class="fas fa-plus me-2"></i>Registrar Abastecimento
    </a>
</div>

<!-- Cards de resumo -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto" style="background:#fefce8;color:#ca8a04"><i class="fas fa-gas-pump"></i></div>
            <div class="stat-value"><?= count($abastecimentos ?? []) ?></div>
            <div class="stat-label">Abastecimentos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-tachometer-alt"></i></div>
            <div class="stat-value"><?= $mediaConsumo ?? 0 ?> <small style="font-size:.9rem">km/L</small></div>
            <div class="stat-label">Média de Consumo</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto" style="background:#fdf4ff;color:#9333ea"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-value" style="font-size:1.2rem">R$ <?= number_format($totalGasto ?? 0, 2, ',', '.') ?></div>
            <div class="stat-label">Total Gasto</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-road"></i></div>
            <div class="stat-value">Cidade / Rodovia</div>
            <div class="stat-label">Consumo por tipo</div>
        </div>
    </div>
</div>

<?php if (!empty($abastecimentos)): ?>
<!-- Gráfico de consumo -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="fas fa-chart-line me-2 text-primary"></i>Evolução do Consumo (km/L)</h6>
            <canvas id="graficoConsumo" height="120"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2 text-warning"></i>Por Combustível</h6>
            <canvas id="graficoCombustivel" height="180"></canvas>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabela -->
<?php if (empty($abastecimentos)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-gas-pump fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhum abastecimento registrado</h5>
    <a href="/portal/abastecimentos/adicionar" class="btn btn-warning mt-2">Registrar Abastecimento</a>
</div>
<?php else: ?>
<div class="stat-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8fafc">
                <tr>
                    <th class="px-4 py-3">Data</th>
                    <th>Posto / Cidade</th>
                    <th>Combustível</th>
                    <th>Litros</th>
                    <th>R$/L</th>
                    <th>Total</th>
                    <th>KM</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($abastecimentos as $a): ?>
                <tr>
                    <td class="px-4 py-3"><?= date('d/m/Y', strtotime($a['data_abast'])) ?></td>
                    <td>
                        <div><?= htmlspecialchars($a['posto_nome'] ?? '—') ?></div>
                        <small class="text-muted"><?= htmlspecialchars($a['cidade'] ?? '') ?></small>
                    </td>
                    <td>
                        <span class="badge" style="background:<?= $a['combustivel'] === 'gasolina' ? '#fbbf24' : ($a['combustivel'] === 'etanol' ? '#34d399' : '#60a5fa') ?>;color:#fff">
                            <?= ucfirst($a['combustivel']) ?>
                        </span>
                    </td>
                    <td><?= number_format($a['litros'], 2, ',', '.') ?> L</td>
                    <td>R$ <?= number_format($a['valor_litro'], 3, ',', '.') ?></td>
                    <td class="fw-bold text-success">R$ <?= number_format($a['valor_total'], 2, ',', '.') ?></td>
                    <td><?= number_format($a['km_abastecido']) ?> km</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($abastecimentos)):
    $labels = array_reverse(array_map(fn($a) => date('d/m', strtotime($a['data_abast'])), $abastecimentos));
    $valores = array_reverse(array_column($abastecimentos, 'valor_total'));
    $combustiveis = array_count_values(array_column($abastecimentos, 'combustivel'));
    $labelsJson = json_encode($labels);
    $valoresJson = json_encode($valores);
    $combLabels = json_encode(array_keys($combustiveis));
    $combData = json_encode(array_values($combustiveis));
?>
<script>
new Chart(document.getElementById('graficoConsumo'), {
    type: 'line',
    data: {
        labels: <?= $labelsJson ?>,
        datasets: [{
            label: 'Gasto (R$)',
            data: <?= $valoresJson ?>,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,.1)',
            tension: 0.4, fill: true,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('graficoCombustivel'), {
    type: 'doughnut',
    data: {
        labels: <?= $combLabels ?>,
        datasets: [{ data: <?= $combData ?>, backgroundColor: ['#fbbf24','#34d399','#60a5fa','#f87171','#a78bfa'] }]
    },
    options: { responsive: true }
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
