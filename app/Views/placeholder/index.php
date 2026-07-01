<?php require_once dirname(__DIR__) . '/layout/erp_header.php'; ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo isset($title) ? htmlspecialchars($title) : 'Módulo'; ?></h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo isset($title) ? htmlspecialchars($title) : 'Módulo'; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-cogs fa-4x text-muted mb-4"></i>
                    <h2 class="mb-3">Funcionalidade em Desenvolvimento</h2>
                    <p class="lead text-muted">Estamos trabalhando para trazer esta funcionalidade em breve.</p>
                    <a href="/dashboard" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once dirname(__DIR__) . '/layout/erp_footer.php'; ?>
