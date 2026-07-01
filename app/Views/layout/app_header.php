<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' — AppAuto' : 'AppAuto'; ?></title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w:   240px;
            --topbar-h:    60px;
            --primary:     #1a56db;
            --primary-dk:  #1e429f;
            --accent:      #f97316;
            --sidebar-bg:  #0f172a;
            --sidebar-txt: #94a3b8;
            --sidebar-act: #1a56db;
            --bg:          #f1f5f9;
            --card:        #ffffff;
            --text:        #1e293b;
            --text-light:  #64748b;
            --border:      #e2e8f0;
            --success:     #16a34a;
            --danger:      #dc2626;
            --warning:     #d97706;
            --radius:      10px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }

        /* ---- SIDEBAR ---- */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 200; transition: transform .3s;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand img { height: 36px; width: auto; object-fit: contain; }
        .sidebar-brand .brand-text { color: #fff; font-weight: 700; font-size: 16px; }
        .sidebar-brand .brand-sub  { color: var(--sidebar-txt); font-size: 10px; }

        .sidebar-nav { flex: 1; padding: 12px 0; }
        .nav-section { padding: 16px 20px 6px; font-size: 10px; font-weight: 700;
                        color: rgba(148,163,184,.5); letter-spacing: 1px; text-transform: uppercase; }
        .nav-item { display: block; padding: 10px 20px; color: var(--sidebar-txt);
                    text-decoration: none; font-size: 13.5px; font-weight: 500;
                    display: flex; align-items: center; gap: 10px; transition: all .2s;
                    border-left: 3px solid transparent; }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,.06); }
        .nav-item.active { color: #fff; background: rgba(26,86,219,.25);
                           border-left-color: var(--primary); }
        .nav-item .nav-icon { width: 18px; text-align: center; font-size: 14px; }
        .nav-badge { margin-left: auto; background: var(--accent); color: #fff;
                     font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 10px; }

        /* Submenu */
        .nav-submenu { overflow: hidden; max-height: 0; transition: max-height .3s; }
        .nav-submenu.open { max-height: 200px; }
        .nav-subitem { display: block; padding: 8px 20px 8px 48px; color: var(--sidebar-txt);
                       text-decoration: none; font-size: 13px; transition: color .2s; }
        .nav-subitem:hover { color: #fff; }
        .nav-subitem.active { color: var(--primary); }
        .nav-parent { cursor: pointer; }
        .nav-parent .nav-arrow { margin-left: auto; font-size: 11px; transition: transform .3s; }
        .nav-parent.open .nav-arrow { transform: rotate(90deg); }

        .sidebar-footer { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-user { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--primary);
                       display: flex; align-items: center; justify-content: center;
                       color: #fff; font-weight: 700; font-size: 14px; flex-shrink: 0; }
        .user-name  { color: #fff; font-size: 13px; font-weight: 600; }
        .user-role  { color: var(--sidebar-txt); font-size: 11px; }

        /* ---- TOPBAR ---- */
        .topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: var(--topbar-h); background: var(--card);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; z-index: 100;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .btn-menu { background: none; border: none; cursor: pointer; font-size: 18px;
                    color: var(--text-light); display: none; }
        .page-title { font-size: 17px; font-weight: 700; color: var(--text); }
        .breadcrumb { font-size: 12px; color: var(--text-light); }
        .breadcrumb a { color: var(--primary); text-decoration: none; }

        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-btn { background: none; border: none; cursor: pointer; width: 36px; height: 36px;
                      border-radius: 8px; display: flex; align-items: center; justify-content: center;
                      color: var(--text-light); font-size: 16px; transition: background .2s; position: relative; }
        .topbar-btn:hover { background: var(--bg); }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px;
                       border-radius: 50%; background: var(--danger); border: 2px solid #fff; }

        /* Impersonation bar */
        .impersonation-bar {
            background: linear-gradient(90deg, #f97316, #ea580c);
            color: #fff; padding: 8px 24px;
            display: flex; align-items: center; justify-content: space-between;
            font-size: 13px; font-weight: 500;
        }
        .impersonation-bar a { color: #fff; font-weight: 700; text-decoration: underline; }

        /* ---- MAIN ---- */
        .main-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }
        .page-content { padding: 28px 28px; }

        /* ---- CARDS ---- */
        .card { background: var(--card); border-radius: var(--radius);
                border: 1px solid var(--border); overflow: hidden; }
        .card-header { padding: 18px 24px; border-bottom: 1px solid var(--border);
                       display: flex; align-items: center; justify-content: space-between; }
        .card-title-sm { font-size: 15px; font-weight: 700; color: var(--text); }
        .card-body { padding: 24px; }

        /* Stat cards */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 28px; }
        .stat-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border);
                     padding: 20px 24px; display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex;
                     align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .stat-icon.blue   { background: #eff6ff; color: var(--primary); }
        .stat-icon.green  { background: #f0fdf4; color: var(--success); }
        .stat-icon.orange { background: #fff7ed; color: var(--accent); }
        .stat-icon.red    { background: #fef2f2; color: var(--danger); }
        .stat-value { font-size: 26px; font-weight: 700; color: var(--text); }
        .stat-label { font-size: 12px; color: var(--text-light); margin-top: 2px; }

        /* Tabela */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700;
             color: var(--text-light); text-transform: uppercase; letter-spacing: .5px;
             border-bottom: 1px solid var(--border); background: #f8fafc; }
        td { padding: 14px 16px; border-bottom: 1px solid var(--border); color: var(--text); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px;
                 border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: var(--success); }
        .badge-warning { background: #fef3c7; color: var(--warning); }
        .badge-danger  { background: #fee2e2; color: var(--danger); }
        .badge-info    { background: #dbeafe; color: var(--primary); }
        .badge-gray    { background: #f1f5f9; color: var(--text-light); }

        /* Botões */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px;
               border-radius: 8px; font-size: 13px; font-weight: 600; font-family: inherit;
               cursor: pointer; border: none; text-decoration: none; transition: all .2s; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dk); }
        .btn-outline { background: transparent; color: var(--primary); border: 1.5px solid var(--primary); }
        .btn-outline:hover { background: rgba(26,86,219,.06); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-icon { width: 32px; height: 32px; padding: 0; justify-content: center; border-radius: 6px; }

        /* Formulários */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
                        border-radius: 8px; font-size: 14px; font-family: inherit; color: var(--text);
                        background: #fff; outline: none; transition: border-color .2s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Alertas */
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;
                 display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #f0fdf4; color: var(--success); border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: var(--danger);  border: 1px solid #fecaca; }
        .alert-info    { background: #eff6ff; color: var(--primary); border: 1px solid #bfdbfe; }

        /* Utilitários */
        .flex { display: flex; } .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; } .gap-3 { gap: 12px; }
        .mt-4 { margin-top: 16px; } .mb-4 { margin-bottom: 16px; }
        .text-sm { font-size: 12px; } .text-muted { color: var(--text-light); }
        .fw-600 { font-weight: 600; }

        /* Responsivo */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
            .btn-menu { display: flex; }
            .form-row { grid-template-columns: 1fr; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<?php
$userId    = $_SESSION['user_id']    ?? 0;
$userName  = $_SESSION['user_name']  ?? 'Usuário';
$userPerfil= $_SESSION['user_perfil']?? 'usuario';
$tipoConta = $_SESSION['tipo_conta'] ?? 'pessoal';
$isAdmin   = ($userPerfil === 'admin');
$isImpersonating = !empty($_SESSION['admin_original_id']);
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';

function navActive(string $path): string {
    global $currentUri;
    return (strpos($currentUri, $path) === 0) ? 'active' : '';
}
?>

<!-- Impersonation Bar -->
<?php if ($isImpersonating): ?>
<div class="impersonation-bar">
    <span><i class="fa fa-eye"></i> Você está visualizando como:
        <strong><?php echo htmlspecialchars($userName); ?></strong>
    </span>
    <a href="/admin/sair-impersonacao"><i class="fa fa-times"></i> Voltar ao Admin</a>
</div>
<?php endif; ?>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="/assets/logo.png" alt="AppAuto" onerror="this.style.display='none'">
        <div>
            <div class="brand-text">AppAuto</div>
            <div class="brand-sub">Gestão Automotiva</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <?php if ($isAdmin): ?>
        <!-- MENU ADMIN -->
        <div class="nav-section">Administração</div>
        <a href="/admin/dashboard" class="nav-item <?php echo navActive('/admin/dashboard'); ?>">
            <i class="fa fa-tachometer nav-icon"></i> Dashboard Admin
        </a>

        <div class="nav-section">Clientes</div>
        <div class="nav-item nav-parent <?php echo navActive('/admin/clientes') ? 'open active' : ''; ?>" onclick="toggleSubmenu(this)">
            <i class="fa fa-users nav-icon"></i> Clientes
            <i class="fa fa-chevron-right nav-arrow"></i>
        </div>
        <div class="nav-submenu <?php echo navActive('/admin/clientes') ? 'open' : ''; ?>">
            <a href="/admin/clientes/pessoas" class="nav-subitem <?php echo navActive('/admin/clientes/pessoas') ? 'active' : ''; ?>">
                <i class="fa fa-user"></i> Pessoas (PF)
            </a>
            <a href="/admin/clientes/negocios" class="nav-subitem <?php echo navActive('/admin/clientes/negocios') ? 'active' : ''; ?>">
                <i class="fa fa-building"></i> Negócios (PJ)
            </a>
        </div>

        <div class="nav-section">Sistema</div>
        <a href="/admin/logs" class="nav-item <?php echo navActive('/admin/logs'); ?>">
            <i class="fa fa-list-alt nav-icon"></i> Logs de Auditoria
        </a>
        <a href="/admin/configuracoes" class="nav-item <?php echo navActive('/admin/configuracoes'); ?>">
            <i class="fa fa-cog nav-icon"></i> Configurações
        </a>

        <div class="nav-section">Acesso Rápido</div>
        <a href="/admin/clientes/pessoas" class="nav-item" style="color:#f97316; border-left-color:#f97316;">
            <i class="fa fa-user-secret nav-icon"></i> Admin como Pessoa
        </a>
        <a href="/admin/clientes/negocios" class="nav-item" style="color:#f97316; border-left-color:#f97316;">
            <i class="fa fa-building nav-icon"></i> Admin como Negócio
        </a>

        <?php else: ?>
        <!-- MENU USUÁRIO -->
        <div class="nav-section">Principal</div>
        <a href="/dashboard" class="nav-item <?php echo navActive('/dashboard'); ?>">
            <i class="fa fa-home nav-icon"></i> Início
        </a>

        <div class="nav-section">Meus Veículos</div>
        <a href="/veiculos" class="nav-item <?php echo navActive('/veiculos'); ?>">
            <i class="fa fa-car nav-icon"></i> Meus Veículos
        </a>
        <a href="/veiculos/adicionar" class="nav-item <?php echo navActive('/veiculos/adicionar'); ?>">
            <i class="fa fa-plus-circle nav-icon"></i> Adicionar Veículo
        </a>
        <a href="/veiculos/consultar-placa" class="nav-item <?php echo navActive('/veiculos/consultar-placa'); ?>">
            <i class="fa fa-search nav-icon"></i> Consultar Placa
        </a>

        <?php if ($tipoConta === 'negocio'): ?>
        <div class="nav-section">Meu Negócio</div>
        <a href="/negocio/dashboard" class="nav-item <?php echo navActive('/negocio/dashboard'); ?>">
            <i class="fa fa-building nav-icon"></i> Painel do Negócio
        </a>
        <a href="/negocio/clientes" class="nav-item <?php echo navActive('/negocio/clientes'); ?>">
            <i class="fa fa-users nav-icon"></i> Clientes
        </a>
        <a href="/negocio/servicos" class="nav-item <?php echo navActive('/negocio/servicos'); ?>">
            <i class="fa fa-wrench nav-icon"></i> Serviços
        </a>
        <?php endif; ?>

        <div class="nav-section">Conta</div>
        <a href="/perfil" class="nav-item <?php echo navActive('/perfil'); ?>">
            <i class="fa fa-user-circle nav-icon"></i> Meu Perfil
        </a>
        <a href="/configuracoes" class="nav-item <?php echo navActive('/configuracoes'); ?>">
            <i class="fa fa-cog nav-icon"></i> Configurações
        </a>
        <?php endif; ?>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
            <div>
                <div class="user-name"><?php echo htmlspecialchars(explode(' ', $userName)[0]); ?></div>
                <div class="user-role"><?php echo $isAdmin ? 'Administrador' : ucfirst($tipoConta); ?></div>
            </div>
        </div>
    </div>
</nav>

<!-- Topbar -->
<header class="topbar">
    <div class="topbar-left">
        <button class="btn-menu" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </button>
        <div>
            <div class="page-title"><?php echo isset($title) ? htmlspecialchars($title) : 'Dashboard'; ?></div>
            <?php if (!empty($breadcrumb)): ?>
            <div class="breadcrumb"><?php echo $breadcrumb; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="topbar-right">
        <button class="topbar-btn" title="Notificações">
            <i class="fa fa-bell"></i>
            <span class="notif-badge"></span>
        </button>
        <form action="/logout" method="POST" style="display:inline;">
            <?php echo \App\Core\View::csrfField(); ?>
            <button type="submit" class="topbar-btn" title="Sair">
                <i class="fa fa-sign-out"></i>
            </button>
        </form>
    </div>
</header>

<!-- Overlay mobile -->
<div id="overlay" onclick="toggleSidebar()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:150;"></div>

<!-- Main -->
<main class="main-content">
    <div class="page-content">

<script>
function toggleSidebar() {
    var s = document.getElementById('sidebar');
    var o = document.getElementById('overlay');
    s.classList.toggle('open');
    o.style.display = s.classList.contains('open') ? 'block' : 'none';
}
function toggleSubmenu(el) {
    el.classList.toggle('open');
    var sub = el.nextElementSibling;
    if (sub) sub.classList.toggle('open');
}
</script>
