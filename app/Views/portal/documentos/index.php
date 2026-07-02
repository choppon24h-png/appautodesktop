<?php $pageTitle = 'Documentos'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-folder-open me-2" style="color:#8b5cf6"></i>Documentos do Veículo</h4>
    <a href="/portal/documentos/adicionar" class="btn" style="background:#8b5cf6;color:#fff">
        <i class="fas fa-upload me-2"></i>Enviar Documento
    </a>
</div>

<?php
$tipos = ['crlv'=>'CRLV','cnh'=>'CNH','seguro'=>'Seguro','nota_fiscal'=>'Nota Fiscal','laudo'=>'Laudo','ipva'=>'IPVA','outro'=>'Outros'];
$icones = ['crlv'=>'fas fa-id-card','cnh'=>'fas fa-id-badge','seguro'=>'fas fa-shield-alt','nota_fiscal'=>'fas fa-receipt','laudo'=>'fas fa-file-medical','ipva'=>'fas fa-file-invoice-dollar','outro'=>'fas fa-file-alt'];
?>

<?php if (empty($documentos)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhum documento cadastrado</h5>
    <a href="/portal/documentos/adicionar" class="btn mt-2" style="background:#8b5cf6;color:#fff">Enviar Documento</a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($documentos as $d):
        $icone = $icones[$d['tipo']] ?? 'fas fa-file-alt';
        $label = $tipos[$d['tipo']] ?? ucfirst($d['tipo']);
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start gap-3">
                <div style="width:44px;height:44px;background:#f3f0ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="<?= $icone ?>" style="color:#8b5cf6;font-size:1.1rem"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?= htmlspecialchars($d['titulo'] ?: $label) ?></div>
                    <span class="badge" style="background:#f3f0ff;color:#8b5cf6;font-size:.7rem"><?= $label ?></span>
                    <?php if ($d['tamanho_kb'] > 0): ?>
                    <div class="text-muted" style="font-size:.7rem"><?= $d['tamanho_kb'] > 1024 ? round($d['tamanho_kb']/1024, 1).'MB' : $d['tamanho_kb'].'KB' ?></div>
                    <?php endif; ?>
                    <div class="text-muted" style="font-size:.7rem"><?= date('d/m/Y', strtotime($d['criado_em'])) ?></div>
                </div>
                <?php if (!empty($d['arquivo'])): ?>
                <a href="<?= htmlspecialchars($d['arquivo']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php if (!empty($d['observacao'])): ?>
            <p class="text-muted small mt-2 mb-0"><?= htmlspecialchars($d['observacao']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
