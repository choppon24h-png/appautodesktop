<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>INLAUDO ERP - Dashboard</title>
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- CSS -->
  <style>
    :root {
        --primary-color: #00529B;
        --sidebar-bg: #172B4D;
        --sidebar-text: #ADB5BD;
        --sidebar-active: #F5365C;
        --header-bg: #5E72E4;
    }
    /* Adicione aqui o CSS completo do Argon Dashboard */
    body { background-color: #F8F9FE; }
    .sidenav { height: 100%; width: 250px; position: fixed; z-index: 1; top: 0; left: 0; background-color: var(--sidebar-bg); overflow-x: hidden; padding-top: 20px; }
    .sidenav a { padding: 10px 15px; text-decoration: none; font-size: 16px; color: var(--sidebar-text); display: block; }
    .sidenav a:hover, .sidenav a.active { color: #fff; background-color: rgba(255,255,255,0.1); }
    .sidenav .navbar-brand { padding: 20px; text-align: center; }
    .sidenav .navbar-brand img { max-height: 40px; }
    .main-content { margin-left: 250px; }
    .navbar-top { background-color: var(--header-bg); padding: 1rem; }
    .header-body { padding: 1.5rem; }
    .card { margin-bottom: 30px; border: 0; box-shadow: 0 0 2rem 0 rgba(136,152,170,.15); }
    .card-stats .card-body { padding: 1.5rem; }
    .icon { width: 3rem; height: 3rem; }
    .icon-shape { display: inline-flex; padding: 12px; text-align: center; border-radius: 50%; align-items: center; justify-content: center; }
    .bg-gradient-red { background: linear-gradient(87deg,#f5365c 0,#f56036 100%)!important; }
    .bg-gradient-orange { background: linear-gradient(87deg,#fb6340 0,#fbb140 100%)!important; }
    .bg-gradient-green { background: linear-gradient(87deg,#2dce89 0,#2dcecc 100%)!important; }
    .bg-gradient-info { background: linear-gradient(87deg,#11cdef 0,#1171ef 100%)!important; }
    .text-white { color: #fff!important; }
    .rounded-circle { border-radius: 50%!important; }
    .shadow { box-shadow: 0 1px 3px rgba(50,50,93,.15), 0 1px 0 rgba(0,0,0,.02)!important; }
    .h2, h5 { color: #32325d; }
    .text-muted { color: #8898aa!important; }
  </style>
</head>
<body>
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="/dashboard">
          <img src="/home/ubuntu/upload/1.png" class="navbar-brand-img" alt="...">
        </a>
      </div>
      <div class="navbar-inner">
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" href="/dashboard">
                <i class="fas fa-tv text-primary"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="/clientes"><i class="fas fa-users text-blue"></i><span class="nav-link-text">Clientes</span></a></li>
<li class="nav-item">
              <a class="nav-link" href="#navbar-financeiro" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-financeiro">
                <i class="fas fa-wallet text-info"></i>
                <span class="nav-link-text">Financeiro</span>
              </a>
              <div class="collapse" id="navbar-financeiro">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item"><a href="/financeiro/contas-a-pagar" class="nav-link">Contas a Pagar</a></li>
                  <li class="nav-item"><a href="/financeiro/contas-a-receber" class="nav-link">Contas a Receber</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="/faturamento"><i class="fas fa-file-invoice-dollar text-yellow"></i><span class="nav-link-text">Faturamento</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/integracao"><i class="fas fa-cogs text-primary"></i><span class="nav-link-text">Integração</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/configuracoes"><i class="fas fa-cog text-gray"></i><span class="nav-link-text">Configurações</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/logout"><i class="fas fa-sign-out-alt text-danger"></i><span class="nav-link-text">Logout</span></a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <div class="main-content" id="panel">
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav align-items-center ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="https://via.placeholder.com/40">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm font-weight-bold text-white"><?php echo htmlspecialchars($_SESSION["user_name"] ?? "Admin"); ?></span>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <!-- Conteúdo do header aqui -->
      </div>
    </div>
