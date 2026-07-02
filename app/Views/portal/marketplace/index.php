<?php $pageTitle = 'Marketplace'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="mb-4">
    <h4 class="fw-bold mb-1"><i class="fas fa-store me-2 text-orange" style="color:#f97316"></i>Marketplace Automotivo</h4>
    <p class="text-muted mb-0">Encontre serviços e produtos para seu veículo</p>
</div>

<!-- Filtros -->
<div class="stat-card mb-4">
    <div class="d-flex flex-wrap gap-2">
        <button class="btn btn-sm btn-dark active" onclick="filtrar(this, 'todos')">Todos</button>
        <button class="btn btn-sm btn-outline-secondary" onclick="filtrar(this, 'oficina')"><i class="fas fa-wrench me-1"></i>Oficinas</button>
        <button class="btn btn-sm btn-outline-secondary" onclick="filtrar(this, 'pneus')"><i class="fas fa-circle-notch me-1"></i>Pneus</button>
        <button class="btn btn-sm btn-outline-secondary" onclick="filtrar(this, 'seguro')"><i class="fas fa-shield-alt me-1"></i>Seguros</button>
        <button class="btn btn-sm btn-outline-secondary" onclick="filtrar(this, 'lava_jato')"><i class="fas fa-car-wash me-1"></i>Lava Jato</button>
        <button class="btn btn-sm btn-outline-secondary" onclick="filtrar(this, 'pecas')"><i class="fas fa-cogs me-1"></i>Peças</button>
    </div>
</div>

<?php if (empty($ofertas)): ?>
<!-- Placeholder com parceiros sugeridos -->
<div class="row g-3">
    <?php
    $parceiros = [
        ['Oficina Mecânica', 'fas fa-wrench', '#3b82f6', 'Encontre oficinas próximas com avaliações verificadas', 'oficina'],
        ['Borracharia / Pneus', 'fas fa-circle-notch', '#64748b', 'Troca e balanceamento de pneus com garantia', 'pneus'],
        ['Seguro Auto', 'fas fa-shield-alt', '#ef4444', 'Cotação de seguros com as melhores seguradoras', 'seguro'],
        ['Lava Jato', 'fas fa-car', '#06b6d4', 'Higienização completa do seu veículo', 'lava_jato'],
        ['Peças e Acessórios', 'fas fa-cogs', '#f59e0b', 'Peças originais e paralelas com entrega rápida', 'pecas'],
        ['Elétrica Automotiva', 'fas fa-bolt', '#8b5cf6', 'Especialistas em sistema elétrico veicular', 'eletrica'],
    ];
    foreach ($parceiros as [$nome, $icone, $cor, $desc, $tipo]): ?>
    <div class="col-md-6 col-lg-4 marketplace-item" data-tipo="<?= $tipo ?>">
        <div class="stat-card h-100" style="border-top:3px solid <?= $cor ?>">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;background:<?= $cor ?>22;border-radius:12px;display:flex;align-items:center;justify-content:center">
                    <i class="<?= $icone ?>" style="color:<?= $cor ?>;font-size:1.2rem"></i>
                </div>
                <div>
                    <div class="fw-bold"><?= $nome ?></div>
                    <div class="d-flex gap-1">
                        <?php for ($i=0;$i<5;$i++): ?>
                        <i class="fas fa-star" style="color:#f59e0b;font-size:.7rem"></i>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <p class="text-muted small mb-3"><?= $desc ?></p>
            <button class="btn btn-sm w-100" style="background:<?= $cor ?>;color:#fff">
                <i class="fas fa-search me-2"></i>Encontrar próximos
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Em breve:</strong> Parceiros cadastrados aparecerão aqui. Se você tem um negócio automotivo, <a href="/portal/negocio">cadastre-se como negócio</a> para aparecer no marketplace.
</div>
<?php else: ?>
<div class="row g-3" id="listaOfertas">
    <?php foreach ($ofertas as $o): ?>
    <div class="col-md-6 col-lg-4 marketplace-item" data-tipo="<?= htmlspecialchars($o['tipo']) ?>">
        <div class="stat-card h-100">
            <div class="fw-bold mb-1"><?= htmlspecialchars($o['titulo']) ?></div>
            <p class="text-muted small"><?= htmlspecialchars($o['descricao'] ?? '') ?></p>
            <?php if (!empty($o['preco'])): ?>
            <div class="fw-bold text-success">R$ <?= number_format($o['preco'], 2, ',', '.') ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
function filtrar(btn, tipo) {
    document.querySelectorAll('.marketplace-item').forEach(el => {
        el.style.display = (tipo === 'todos' || el.dataset.tipo === tipo) ? '' : 'none';
    });
    document.querySelectorAll('[onclick^="filtrar"]').forEach(b => b.classList.remove('btn-dark','active'));
    btn.classList.add('btn-dark','active');
}
</script>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
