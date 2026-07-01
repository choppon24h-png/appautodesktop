<?php require_once dirname(__DIR__) . '/layout/app_header.php'; ?>

<div class="flex justify-between items-center mb-4">
    <div>
        <p class="text-muted text-sm">Total: <strong><?php echo number_format($total ?? 0); ?></strong> negócios cadastrados</p>
    </div>
    <div class="flex gap-2">
        <form method="GET" action="/admin/clientes/negocios" style="display:flex; gap:8px;">
            <input type="text" name="busca" class="form-control" style="width:220px;"
                   placeholder="Buscar por nome ou CNPJ..."
                   value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title-sm"><i class="fa fa-building"></i> Negócios (PJ)</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Razão Social / Nome</th>
                    <th>CNPJ</th>
                    <th>Ramo de Atividade</th>
                    <th>Responsável</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Plano</th>
                    <th>Cadastrado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($negocios)): ?>
                <?php foreach ($negocios as $n): ?>
                <tr>
                    <td class="text-muted text-sm">#<?php echo $n->id; ?></td>
                    <td>
                        <div class="fw-600"><?php echo htmlspecialchars($n->razao_social); ?></div>
                        <?php if ($n->nome_fantasia && $n->nome_fantasia !== $n->razao_social): ?>
                        <div class="text-sm text-muted"><?php echo htmlspecialchars($n->nome_fantasia); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-sm"><?php echo $n->cnpj ? preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $n->cnpj) : '—'; ?></td>
                    <td>
                        <span class="badge badge-info">
                            <i class="fa <?php echo $n->ramo_icone ?? 'fa-wrench'; ?>"></i>
                            <?php echo htmlspecialchars($n->ramo_nome ?? '—'); ?>
                        </span>
                    </td>
                    <td class="text-sm"><?php echo htmlspecialchars($n->dono_nome ?? '—'); ?></td>
                    <td class="text-sm"><?php echo htmlspecialchars($n->email ?? '—'); ?></td>
                    <td>
                        <?php
                        $statusMap = ['ativo'=>'badge-success','pendente'=>'badge-warning','inativo'=>'badge-gray','suspenso'=>'badge-danger'];
                        $cls = $statusMap[$n->status] ?? 'badge-gray';
                        ?>
                        <span class="badge <?php echo $cls; ?>"><?php echo ucfirst($n->status); ?></span>
                    </td>
                    <td>
                        <?php
                        $planoMap = ['gratuito'=>'badge-gray','basico'=>'badge-info','profissional'=>'badge-success','enterprise'=>'badge-warning'];
                        $pcls = $planoMap[$n->plano] ?? 'badge-gray';
                        ?>
                        <span class="badge <?php echo $pcls; ?>"><?php echo ucfirst($n->plano); ?></span>
                    </td>
                    <td class="text-sm text-muted"><?php echo date('d/m/Y', strtotime($n->criado_em)); ?></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="/admin/acessar-negocio/<?php echo $n->id; ?>" class="btn btn-sm btn-outline" title="Acessar como este negócio">
                                <i class="fa fa-eye"></i> Acessar
                            </a>
                            <a href="/admin/negocio/<?php echo $n->id; ?>" class="btn btn-sm btn-primary" title="Ver detalhes">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr><td colspan="10" style="text-align:center; color:var(--text-light); padding:40px;">
                    <i class="fa fa-building" style="font-size:32px; margin-bottom:8px; display:block;"></i>
                    Nenhum negócio encontrado.
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layout/app_footer.php'; ?>
