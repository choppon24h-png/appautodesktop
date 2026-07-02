<?php $pageTitle = 'Relatórios'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="mb-4">
    <h4 class="fw-bold mb-1"><i class="fas fa-chart-bar me-2 text-primary"></i>Relatórios</h4>
    <p class="text-muted mb-0">Análises detalhadas do seu veículo</p>
</div>

<div class="row g-3">
    <?php
    $relatorios = [
        ['Custo Anual Completo', 'fas fa-dollar-sign', '#8b5cf6', 'Resumo de todos os gastos do ano por categoria', '/portal/custos'],
        ['Histórico de Manutenções', 'fas fa-wrench', '#3b82f6', 'Lista completa de serviços realizados', '/portal/manutencoes'],
        ['Consumo de Combustível', 'fas fa-gas-pump', '#f59e0b', 'Evolução do consumo e gastos com combustível', '/portal/abastecimentos'],
        ['Linha do Tempo', 'fas fa-history', '#10b981', 'Todos os eventos registrados cronologicamente', '/portal/timeline'],
        ['Checklist de Viagem', 'fas fa-clipboard-check', '#06b6d4', 'Verificações pré-viagem e preventivas', '/portal/checklist'],
        ['Score do Veículo', 'fas fa-star', '#f59e0b', 'Pontuação geral de saúde do veículo', '/portal/dashboard'],
    ];
    foreach ($relatorios as [$titulo, $icone, $cor, $desc, $link]): ?>
    <div class="col-md-6 col-lg-4">
        <a href="<?= $link ?>" class="text-decoration-none">
            <div class="stat-card h-100" style="border-left:4px solid <?= $cor ?>;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:44px;height:44px;background:<?= $cor ?>22;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="<?= $icone ?>" style="color:<?= $cor ?>;font-size:1.1rem"></i>
                    </div>
                    <div class="fw-bold text-dark"><?= $titulo ?></div>
                </div>
                <p class="text-muted small mb-0"><?= $desc ?></p>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
