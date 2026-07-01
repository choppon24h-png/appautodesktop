<?php require_once dirname(__DIR__) . '/layout/app_header.php'; ?>

<div class="flex justify-between items-center mb-4">
    <div>
        <p class="text-muted text-sm">Total: <strong><?php echo number_format($total ?? 0); ?></strong> pessoas cadastradas</p>
    </div>
    <div class="flex gap-2">
        <form method="GET" action="/admin/clientes/pessoas" style="display:flex; gap:8px;">
            <input type="text" name="busca" class="form-control" style="width:220px;"
                   placeholder="Buscar por nome ou e-mail..."
                   value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title-sm"><i class="fa fa-users"></i> Pessoas Físicas (PF)</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome Completo</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Status</th>
                    <th>E-mail Verificado</th>
                    <th>Cadastrado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td class="text-muted text-sm">#<?php echo $u->id; ?></td>
                    <td class="fw-600"><?php echo htmlspecialchars($u->nome_completo); ?></td>
                    <td><?php echo htmlspecialchars($u->email); ?></td>
                    <td class="text-sm"><?php echo $u->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $u->cpf) : '—'; ?></td>
                    <td class="text-sm"><?php echo $u->telefone ?: '—'; ?></td>
                    <td>
                        <?php
                        $statusMap = ['ativo'=>'badge-success','pendente'=>'badge-warning','inativo'=>'badge-gray','bloqueado'=>'badge-danger'];
                        $cls = $statusMap[$u->status] ?? 'badge-gray';
                        ?>
                        <span class="badge <?php echo $cls; ?>"><?php echo ucfirst($u->status); ?></span>
                    </td>
                    <td>
                        <?php if ($u->email_verificado): ?>
                        <span class="badge badge-success"><i class="fa fa-check"></i> Sim</span>
                        <?php else: ?>
                        <span class="badge badge-warning"><i class="fa fa-clock-o"></i> Pendente</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-sm text-muted"><?php echo date('d/m/Y', strtotime($u->criado_em)); ?></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="/admin/acessar-como/<?php echo $u->id; ?>" class="btn btn-sm btn-outline" title="Acessar como este usuário">
                                <i class="fa fa-eye"></i> Acessar
                            </a>
                            <a href="/admin/usuario/<?php echo $u->id; ?>" class="btn btn-sm btn-primary" title="Ver detalhes">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr><td colspan="9" style="text-align:center; color:var(--text-light); padding:40px;">
                    <i class="fa fa-users" style="font-size:32px; margin-bottom:8px; display:block;"></i>
                    Nenhuma pessoa encontrada.
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <?php if (!empty($totalPaginas) && $totalPaginas > 1): ?>
    <div style="padding:16px 24px; display:flex; gap:8px; justify-content:flex-end; border-top:1px solid var(--border);">
        <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
        <a href="?pagina=<?php echo $p; ?><?php echo !empty($_GET['busca']) ? '&busca='.urlencode($_GET['busca']) : ''; ?>"
           class="btn btn-sm <?php echo ($pagina ?? 1) == $p ? 'btn-primary' : 'btn-outline'; ?>">
            <?php echo $p; ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once dirname(__DIR__) . '/layout/app_footer.php'; ?>
