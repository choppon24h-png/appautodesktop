<?php require_once dirname(__DIR__) . '/layout/app_header.php'; ?>

<!-- Stats -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa fa-users"></i></div>
        <div>
            <div class="stat-value"><?php echo number_format($stats->total_usuarios ?? 0); ?></div>
            <div class="stat-label">Usuários Cadastrados</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-building"></i></div>
        <div>
            <div class="stat-value"><?php echo number_format($stats->total_negocios ?? 0); ?></div>
            <div class="stat-label">Negócios Ativos</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa fa-car"></i></div>
        <div>
            <div class="stat-value"><?php echo number_format($stats->total_veiculos ?? 0); ?></div>
            <div class="stat-label">Veículos Cadastrados</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa fa-clock-o"></i></div>
        <div>
            <div class="stat-value"><?php echo number_format($stats->pendentes ?? 0); ?></div>
            <div class="stat-label">Pendentes de Validação</div>
        </div>
    </div>
</div>

<!-- Últimos cadastros -->
<div class="card">
    <div class="card-header">
        <span class="card-title-sm"><i class="fa fa-user-plus"></i> Últimos Cadastros</span>
        <a href="/admin/clientes/pessoas" class="btn btn-outline btn-sm">Ver todos</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Cadastrado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ultimos_cadastros)): ?>
                <?php foreach ($ultimos_cadastros as $u): ?>
                <tr>
                    <td class="text-muted text-sm">#<?php echo $u->id; ?></td>
                    <td class="fw-600"><?php echo htmlspecialchars($u->nome_completo); ?></td>
                    <td><?php echo htmlspecialchars($u->email); ?></td>
                    <td>
                        <span class="badge <?php echo $u->tipo_conta === 'negocio' ? 'badge-info' : 'badge-gray'; ?>">
                            <i class="fa <?php echo $u->tipo_conta === 'negocio' ? 'fa-building' : 'fa-user'; ?>"></i>
                            <?php echo $u->tipo_conta === 'negocio' ? 'Negócio' : 'Pessoal'; ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $statusMap = ['ativo'=>'badge-success','pendente'=>'badge-warning','inativo'=>'badge-gray','bloqueado'=>'badge-danger'];
                        $cls = $statusMap[$u->status] ?? 'badge-gray';
                        ?>
                        <span class="badge <?php echo $cls; ?>"><?php echo ucfirst($u->status); ?></span>
                    </td>
                    <td class="text-sm text-muted"><?php echo date('d/m/Y H:i', strtotime($u->criado_em)); ?></td>
                    <td>
                        <a href="/admin/acessar-como/<?php echo $u->id; ?>" class="btn btn-sm btn-outline" title="Acessar como este usuário">
                            <i class="fa fa-eye"></i> Acessar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr><td colspan="7" style="text-align:center; color:var(--text-light); padding:32px;">Nenhum cadastro encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layout/app_footer.php'; ?>
