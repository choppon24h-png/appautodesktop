<?php
// Proteção de sessão
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';
$userName   = $_SESSION['user_name'] ?? 'Usuário';
$userEmail  = $_SESSION['user_email'] ?? '';
$veiculoId  = $_GET['veiculo_id'] ?? ($_SESSION['veiculo_ativo_id'] ?? null);

function isActive(string $path, string $current): string {
    return (strpos($current, $path) !== false) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Portal de Veículos' ?> — AppAuto</title>
    <link rel="icon" type="image/png" href="/assets/img/logo.png">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-width: 260px;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --sidebar-text: #94a3b8;
            --sidebar-active-bg: rgba(59,130,246,0.15);
            --sidebar-active-text: #60a5fa;
            --topbar-h: 60px;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f1f5f9; }

        /* Sidebar */
        #sidebar {
            position: fixed; top: 0; left: 0; height: 100vh;
            width: var(--sidebar-width); background: var(--sidebar-bg);
            overflow-y: auto; z-index: 1000; transition: transform .3s;
            display: flex; flex-direction: column;
        }
        #sidebar .sidebar-brand {
            padding: 18px 20px; border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: 10px;
        }
        #sidebar .sidebar-brand img { width: 38px; height: 38px; border-radius: 8px; object-fit: contain; }
        #sidebar .sidebar-brand span { color: #fff; font-size: 1.1rem; font-weight: 700; }
        #sidebar .sidebar-brand small { color: var(--sidebar-text); font-size: .72rem; display: block; }

        /* Seletor de veículo ativo */
        #sidebar .veiculo-ativo {
            margin: 12px 14px; padding: 10px 14px;
            background: rgba(59,130,246,.12); border-radius: 10px;
            border: 1px solid rgba(59,130,246,.25);
        }
        #sidebar .veiculo-ativo small { color: var(--sidebar-text); font-size: .7rem; text-transform: uppercase; letter-spacing: .05em; }
        #sidebar .veiculo-ativo .placa { color: #fff; font-weight: 700; font-size: .95rem; }
        #sidebar .veiculo-ativo a { color: var(--accent); font-size: .75rem; text-decoration: none; }

        /* Seções do menu */
        #sidebar .menu-section { padding: 14px 14px 4px; }
        #sidebar .menu-section-title {
            color: var(--sidebar-text); font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
        }
        #sidebar .nav-item { list-style: none; }
        #sidebar .nav-link {
            display: flex; align-items: center; gap: 10px;
            color: var(--sidebar-text); padding: 9px 16px; border-radius: 8px;
            text-decoration: none; font-size: .875rem; transition: all .2s;
        }
        #sidebar .nav-link:hover { background: rgba(255,255,255,.06); color: #fff; }
        #sidebar .nav-link.active { background: var(--sidebar-active-bg); color: var(--sidebar-active-text); font-weight: 600; }
        #sidebar .nav-link i { width: 18px; text-align: center; font-size: .9rem; }
        #sidebar .nav-link .badge-count {
            margin-left: auto; background: var(--accent); color: #fff;
            font-size: .65rem; padding: 2px 7px; border-radius: 20px;
        }

        /* Topbar */
        #topbar {
            position: fixed; top: 0; left: var(--sidebar-width); right: 0;
            height: var(--topbar-h); background: #fff;
            border-bottom: 1px solid #e2e8f0; z-index: 999;
            display: flex; align-items: center; padding: 0 24px;
            gap: 12px;
        }
        #topbar .topbar-toggle { display: none; background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer; }
        #topbar .topbar-breadcrumb { flex: 1; color: #64748b; font-size: .875rem; }
        #topbar .topbar-breadcrumb strong { color: #1e293b; }
        #topbar .topbar-actions { display: flex; align-items: center; gap: 12px; }
        #topbar .topbar-actions .btn-icon {
            width: 36px; height: 36px; border-radius: 50%; border: none;
            background: #f1f5f9; color: #64748b; display: flex; align-items: center;
            justify-content: center; cursor: pointer; transition: background .2s; position: relative;
        }
        #topbar .topbar-actions .btn-icon:hover { background: #e2e8f0; }
        #topbar .topbar-actions .btn-icon .badge-dot {
            position: absolute; top: 4px; right: 4px; width: 8px; height: 8px;
            background: #ef4444; border-radius: 50%; border: 2px solid #fff;
        }
        #topbar .user-avatar {
            width: 36px; height: 36px; border-radius: 50%; background: var(--accent);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .9rem; cursor: pointer;
        }

        /* Conteúdo principal */
        #main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }
        .page-body { padding: 28px 28px 40px; }

        /* Cards de estatísticas */
        .stat-card {
            background: #fff; border-radius: 14px; padding: 20px 22px;
            border: 1px solid #e2e8f0; transition: box-shadow .2s;
        }
        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 14px;
        }
        .stat-card .stat-value { font-size: 1.6rem; font-weight: 700; color: #1e293b; }
        .stat-card .stat-label { color: #64748b; font-size: .8rem; margin-top: 2px; }

        /* Score badge */
        .score-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 80px; height: 80px; border-radius: 50%;
            font-size: 1.8rem; font-weight: 800; border: 5px solid;
        }
        .score-excelente { color: #16a34a; border-color: #16a34a; }
        .score-bom { color: #2563eb; border-color: #2563eb; }
        .score-regular { color: #d97706; border-color: #d97706; }
        .score-ruim { color: #dc2626; border-color: #dc2626; }

        /* Responsivo */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-left: 0; }
            #topbar { left: 0; }
            #topbar .topbar-toggle { display: flex; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <img src="/assets/img/logo.png" alt="AppAuto">
        <div>
            <span>AppAuto</span>
            <small>Portal de Veículos</small>
        </div>
    </div>

    <!-- Veículo ativo -->
    <?php if (!empty($_SESSION['veiculo_ativo_placa'])): ?>
    <div class="veiculo-ativo">
        <small>Veículo ativo</small>
        <div class="placa"><?= htmlspecialchars($_SESSION['veiculo_ativo_placa']) ?></div>
        <a href="/portal/veiculos">Trocar veículo &rarr;</a>
    </div>
    <?php endif; ?>

    <ul class="nav flex-column px-2 mt-2" style="flex:1">

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="/portal/dashboard" class="nav-link <?= isActive('/portal/dashboard', $currentUri) ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>

        <!-- Seção: Veículos -->
        <div class="menu-section"><span class="menu-section-title">Veículos</span></div>

        <li class="nav-item">
            <a href="/portal/veiculos" class="nav-link <?= isActive('/portal/veiculos', $currentUri) ?>">
                <i class="fas fa-car"></i> Meus Veículos
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/documentos" class="nav-link <?= isActive('/portal/documentos', $currentUri) ?>">
                <i class="fas fa-folder-open"></i> Documentos
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/galeria" class="nav-link <?= isActive('/portal/galeria', $currentUri) ?>">
                <i class="fas fa-images"></i> Galeria de Fotos
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/timeline" class="nav-link <?= isActive('/portal/timeline', $currentUri) ?>">
                <i class="fas fa-timeline"></i> Timeline
            </a>
        </li>

        <!-- Seção: Manutenção -->
        <div class="menu-section"><span class="menu-section-title">Manutenção</span></div>

        <li class="nav-item">
            <a href="/portal/manutencoes" class="nav-link <?= isActive('/portal/manutencoes', $currentUri) ?>">
                <i class="fas fa-wrench"></i> Oficina / Manutenções
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/agenda" class="nav-link <?= isActive('/portal/agenda', $currentUri) ?>">
                <i class="fas fa-calendar-check"></i> Agenda Inteligente
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/pneus" class="nav-link <?= isActive('/portal/pneus', $currentUri) ?>">
                <i class="fas fa-circle-dot"></i> Pneus
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/bateria" class="nav-link <?= isActive('/portal/bateria', $currentUri) ?>">
                <i class="fas fa-battery-half"></i> Bateria
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/checklist" class="nav-link <?= isActive('/portal/checklist', $currentUri) ?>">
                <i class="fas fa-clipboard-check"></i> Checklist
            </a>
        </li>

        <!-- Seção: Financeiro -->
        <div class="menu-section"><span class="menu-section-title">Financeiro</span></div>

        <li class="nav-item">
            <a href="/portal/abastecimentos" class="nav-link <?= isActive('/portal/abastecimentos', $currentUri) ?>">
                <i class="fas fa-gas-pump"></i> Abastecimentos
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/seguro" class="nav-link <?= isActive('/portal/seguro', $currentUri) ?>">
                <i class="fas fa-shield-halved"></i> Seguro
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/ipva" class="nav-link <?= isActive('/portal/ipva', $currentUri) ?>">
                <i class="fas fa-file-invoice-dollar"></i> IPVA / Multas
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/custos" class="nav-link <?= isActive('/portal/custos', $currentUri) ?>">
                <i class="fas fa-chart-pie"></i> Custos
            </a>
        </li>

        <!-- Seção: Inteligência -->
        <div class="menu-section"><span class="menu-section-title">Inteligência</span></div>

        <li class="nav-item">
            <a href="/portal/relatorios" class="nav-link <?= isActive('/portal/relatorios', $currentUri) ?>">
                <i class="fas fa-chart-bar"></i> Relatórios
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/ia" class="nav-link <?= isActive('/portal/ia', $currentUri) ?>">
                <i class="fas fa-robot"></i> Assistente IA
                <span class="badge-count">Beta</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/portal/marketplace" class="nav-link <?= isActive('/portal/marketplace', $currentUri) ?>">
                <i class="fas fa-store"></i> Marketplace
            </a>
        </li>

        <!-- Seção: Conta -->
        <div class="menu-section"><span class="menu-section-title">Conta</span></div>

        <?php if (!empty($_SESSION['is_negocio'])): ?>
        <li class="nav-item">
            <a href="/negocio/dashboard" class="nav-link">
                <i class="fas fa-building"></i> Meu Negócio
            </a>
        </li>
        <?php endif; ?>

        <?php if (!empty($_SESSION['is_admin'])): ?>
        <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link" style="color:#f97316">
                <i class="fas fa-shield-halved"></i> Painel Admin
            </a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="/configuracoes" class="nav-link <?= isActive('/configuracoes', $currentUri) ?>">
                <i class="fas fa-gear"></i> Configurações
            </a>
        </li>
        <li class="nav-item">
            <a href="/logout" class="nav-link" style="color:#f87171">
                <i class="fas fa-right-from-bracket"></i> Sair
            </a>
        </li>
    </ul>
</nav>

<!-- Topbar -->
<header id="topbar">
    <button class="topbar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
        <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-breadcrumb">
        <strong><?= $pageTitle ?? 'Dashboard' ?></strong>
        <?php if (!empty($breadcrumb)): ?>
        <span class="text-muted"> / <?= htmlspecialchars($breadcrumb) ?></span>
        <?php endif; ?>
    </div>
    <div class="topbar-actions">
        <button class="btn-icon" title="Notificações">
            <i class="fas fa-bell"></i>
            <span class="badge-dot"></span>
        </button>
        <div class="dropdown">
            <div class="user-avatar dropdown-toggle" data-bs-toggle="dropdown" title="<?= htmlspecialchars($userEmail) ?>">
                <?= strtoupper(substr($userName, 0, 1)) ?>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text fw-bold"><?= htmlspecialchars($userName) ?></span></li>
                <li><span class="dropdown-item-text text-muted small"><?= htmlspecialchars($userEmail) ?></span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/configuracoes"><i class="fas fa-gear me-2"></i>Configurações</a></li>
                <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-right-from-bracket me-2"></i>Sair</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Conteúdo principal -->
<div id="main-content">
<div class="page-body">
