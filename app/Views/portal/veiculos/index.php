<?php
$pageTitle = 'Meus Veículos';
require_once __DIR__ . '/../../layout/portal_header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div>
        <h4 style="font-weight:700; margin:0;">Meus Veículos</h4>
        <p style="color:var(--text-light); font-size:13px; margin:4px 0 0;">
            Você tem <strong><?php echo count($veiculos ?? []); ?></strong> veículo(s) cadastrado(s)
        </p>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="/portal/veiculos/consultar-placa" class="btn btn-outline btn-sm">
            <i class="fas fa-search me-1"></i> Consultar Placa
        </a>
        <a href="/portal/veiculos/adicionar" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Adicionar Veículo
        </a>
    </div>
</div>

<?php if (!empty($_GET['success'])): ?>
<div class="alert alert-success mb-4">
    <i class="fas fa-check-circle"></i>
    <?php echo htmlspecialchars($_GET['success']); ?>
</div>
<?php endif; ?>

<?php if (empty($veiculos)): ?>
<div class="card text-center" style="padding:60px 24px;">
    <i class="fas fa-car" style="font-size:56px; color:#e2e8f0; margin-bottom:16px; display:block;"></i>
    <h5 style="font-weight:700; margin-bottom:8px;">Nenhum veículo cadastrado</h5>
    <p style="color:var(--text-light); margin-bottom:24px;">Adicione seu primeiro veículo para acessar todos os módulos do AppAuto.</p>
    <a href="/portal/veiculos/adicionar" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Adicionar meu primeiro veículo
    </a>
</div>
<?php else: ?>

<!-- Seletor de veículo ativo -->
<?php if (!empty($_SESSION['veiculo_ativo_id'])): ?>
<div class="alert alert-info mb-4" style="background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; border-radius:10px; padding:12px 18px; display:flex; align-items:center; gap:10px;">
    <i class="fas fa-car-side"></i>
    <span>Veículo ativo: <strong><?php echo htmlspecialchars($_SESSION['veiculo_ativo_placa'] ?? ''); ?></strong>
    <?php if (!empty($_SESSION['veiculo_ativo_modelo'])): ?>
        — <?php echo htmlspecialchars($_SESSION['veiculo_ativo_modelo']); ?>
    <?php endif; ?>
    </span>
</div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px;">
    <?php foreach ($veiculos as $v): ?>
    <?php
        $isAtivo = (!empty($_SESSION['veiculo_ativo_id']) && (int)$_SESSION['veiculo_ativo_id'] === (int)$v->id);
        $p = strtoupper(preg_replace('/[^A-Z0-9]/i','', $v->placa ?? ''));
        $placaFormatada = (strlen($p)===7) ? substr($p,0,3).'-'.substr($p,3) : $p;
    ?>
    <div class="card" style="overflow:visible; <?php echo $isAtivo ? 'border:2px solid var(--primary); box-shadow:0 0 0 3px rgba(26,86,219,.15);' : ''; ?>">
        <!-- Foto / placeholder -->
        <div style="height:160px; background:linear-gradient(135deg,#1e293b,#0f172a); border-radius:10px 10px 0 0; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
            <?php if (!empty($v->foto_principal)): ?>
            <img src="<?php echo htmlspecialchars($v->foto_principal); ?>" alt="Foto"
                 style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
            <i class="fas fa-car" style="font-size:48px; color:rgba(255,255,255,.2);"></i>
            <?php endif; ?>

            <!-- Badge Mercosul / Padrão -->
            <span style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,.6); color:#fff; font-size:10px; font-weight:700; padding:3px 8px; border-radius:4px;">
                <?php echo ($v->formato_placa ?? '') === 'mercosul' ? 'MERCOSUL' : 'PADRÃO'; ?>
            </span>

            <!-- Badge ativo -->
            <?php if ($isAtivo): ?>
            <span style="position:absolute; top:10px; left:10px; background:#1a56db; color:#fff; font-size:10px; font-weight:700; padding:3px 8px; border-radius:4px;">
                <i class="fas fa-check-circle"></i> ATIVO
            </span>
            <?php endif; ?>
        </div>

        <div style="padding:16px 20px;">
            <!-- Placa -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <div style="background:#1e293b; color:#fff; font-family:monospace; font-size:18px; font-weight:700; padding:6px 14px; border-radius:6px; letter-spacing:2px;">
                    <?php echo htmlspecialchars($placaFormatada); ?>
                </div>
                <?php if (!empty($v->total_fotos) && $v->total_fotos > 0): ?>
                <span class="badge badge-info"><i class="fas fa-camera"></i> <?php echo $v->total_fotos; ?></span>
                <?php endif; ?>
            </div>

            <!-- Dados -->
            <div style="font-size:14px; font-weight:700; color:var(--text); margin-bottom:4px;">
                <?php echo htmlspecialchars(trim(($v->marca ?? '') . ' ' . ($v->modelo ?? ''))); ?>
                <?php if (!empty($v->ano_fabricacao)): ?>
                <span style="color:var(--text-light); font-weight:400; font-size:13px;">
                    <?php echo $v->ano_fabricacao; ?><?php echo !empty($v->ano_modelo) ? '/'.$v->ano_modelo : ''; ?>
                </span>
                <?php endif; ?>
            </div>

            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px;">
                <?php if (!empty($v->cor)): ?>
                <span class="badge badge-gray"><i class="fas fa-circle" style="font-size:8px;"></i> <?php echo htmlspecialchars($v->cor); ?></span>
                <?php endif; ?>
                <?php if (!empty($v->combustivel)): ?>
                <span class="badge badge-gray"><i class="fas fa-gas-pump"></i> <?php echo ucfirst($v->combustivel); ?></span>
                <?php endif; ?>
                <?php if (!empty($v->categoria)): ?>
                <span class="badge badge-gray"><?php echo ucfirst(str_replace('_',' ',$v->categoria)); ?></span>
                <?php endif; ?>
            </div>

            <!-- Ações -->
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <?php if (!$isAtivo): ?>
                <form method="POST" action="/portal/selecionar-veiculo" style="flex:1;">
                    <?php echo \App\Core\View::csrfField(); ?>
                    <input type="hidden" name="veiculo_id" value="<?php echo $v->id; ?>">
                    <button type="submit" class="btn btn-outline btn-sm" style="width:100%;">
                        <i class="fas fa-check"></i> Selecionar
                    </button>
                </form>
                <?php else: ?>
                <a href="/portal/dashboard" class="btn btn-primary btn-sm" style="flex:1; justify-content:center;">
                    <i class="fas fa-tachometer-alt"></i> Ver Dashboard
                </a>
                <?php endif; ?>
                <a href="/portal/veiculos/<?php echo $v->id; ?>/editar" class="btn btn-outline btn-sm">
                    <i class="fas fa-pencil-alt"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
