<?php require_once dirname(__DIR__) . '/layout/app_header.php'; ?>

<div class="flex justify-between items-center mb-4">
    <p class="text-muted text-sm">Você tem <strong><?php echo count($veiculos ?? []); ?></strong> veículo(s) cadastrado(s)</p>
    <div class="flex gap-2">
        <a href="/veiculos/consultar-placa" class="btn btn-outline btn-sm">
            <i class="fa fa-search"></i> Consultar Placa
        </a>
        <a href="/veiculos/adicionar" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Adicionar Veículo
        </a>
    </div>
</div>

<?php if (empty($veiculos)): ?>
<div class="card" style="text-align:center; padding:60px 24px;">
    <i class="fa fa-car" style="font-size:48px; color:var(--border); margin-bottom:16px; display:block;"></i>
    <h3 style="font-size:18px; font-weight:700; margin-bottom:8px;">Nenhum veículo cadastrado</h3>
    <p class="text-muted" style="margin-bottom:24px;">Adicione seu primeiro veículo para começar a usar o AppAuto.</p>
    <a href="/veiculos/adicionar" class="btn btn-primary">
        <i class="fa fa-plus"></i> Adicionar meu primeiro veículo
    </a>
</div>
<?php else: ?>
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px;">
    <?php foreach ($veiculos as $v): ?>
    <div class="card" style="overflow:visible;">
        <!-- Foto ou placeholder -->
        <div style="height:160px; background:linear-gradient(135deg,#1e293b,#0f172a); border-radius:10px 10px 0 0; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
            <?php if ($v->foto_principal): ?>
            <img src="<?php echo htmlspecialchars($v->foto_principal); ?>" alt="Foto do veículo"
                 style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
            <i class="fa fa-car" style="font-size:48px; color:rgba(255,255,255,.2);"></i>
            <?php endif; ?>
            <!-- Badge formato placa -->
            <span style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,.6); color:#fff; font-size:10px; font-weight:700; padding:3px 8px; border-radius:4px;">
                <?php echo $v->formato_placa === 'mercosul' ? 'MERCOSUL' : 'PADRÃO'; ?>
            </span>
        </div>

        <div style="padding:16px 20px;">
            <!-- Placa -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <div style="background:#1e293b; color:#fff; font-family:monospace; font-size:18px; font-weight:700; padding:6px 14px; border-radius:6px; letter-spacing:2px;">
                    <?php echo htmlspecialchars(self_format_placa($v->placa)); ?>
                </div>
                <?php if ($v->total_fotos > 0): ?>
                <span class="badge badge-info"><i class="fa fa-camera"></i> <?php echo $v->total_fotos; ?></span>
                <?php endif; ?>
            </div>

            <!-- Dados -->
            <div style="font-size:14px; font-weight:700; color:var(--text); margin-bottom:4px;">
                <?php echo htmlspecialchars(($v->marca ?? '') . ' ' . ($v->modelo ?? '')); ?>
                <?php if ($v->ano_modelo): ?>
                <span class="text-muted" style="font-weight:400;"><?php echo $v->ano_fabricacao . '/' . $v->ano_modelo; ?></span>
                <?php endif; ?>
            </div>

            <div class="flex gap-2" style="flex-wrap:wrap; margin-bottom:14px;">
                <?php if ($v->cor): ?>
                <span class="badge badge-gray"><i class="fa fa-circle"></i> <?php echo htmlspecialchars($v->cor); ?></span>
                <?php endif; ?>
                <?php if ($v->combustivel): ?>
                <span class="badge badge-gray"><i class="fa fa-tint"></i> <?php echo ucfirst($v->combustivel); ?></span>
                <?php endif; ?>
                <?php if ($v->categoria): ?>
                <span class="badge badge-gray"><?php echo ucfirst(str_replace('_',' ',$v->categoria)); ?></span>
                <?php endif; ?>
            </div>

            <div class="flex gap-2">
                <a href="/veiculos/<?php echo $v->id; ?>" class="btn btn-outline btn-sm" style="flex:1; justify-content:center;">
                    <i class="fa fa-eye"></i> Ver
                </a>
                <a href="/veiculos/<?php echo $v->id; ?>/editar" class="btn btn-primary btn-sm" style="flex:1; justify-content:center;">
                    <i class="fa fa-pencil"></i> Editar
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php
function self_format_placa(string $placa): string {
    $p = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $placa));
    if (strlen($p) === 7) return substr($p,0,3).'-'.substr($p,3);
    return $p;
}
?>

<?php require_once dirname(__DIR__) . '/layout/app_footer.php'; ?>
