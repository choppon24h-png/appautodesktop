<?php $pageTitle = 'Nova Manutenção'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/portal/manutencoes" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><i class="fas fa-wrench me-2 text-success"></i>Registrar Manutenção</h4>
</div>

<div class="stat-card" style="max-width:700px">
    <form method="POST" action="/portal/manutencoes/salvar" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tipo de Serviço *</label>
                <select name="tipo" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option>Troca de Óleo</option>
                    <option>Filtro de Ar</option>
                    <option>Filtro de Combustível</option>
                    <option>Pastilha de Freio</option>
                    <option>Disco de Freio</option>
                    <option>Correia Dentada</option>
                    <option>Velas de Ignição</option>
                    <option>Revisão Geral</option>
                    <option>Suspensão</option>
                    <option>Alinhamento e Balanceamento</option>
                    <option>Ar Condicionado</option>
                    <option>Embreagem</option>
                    <option>Bateria</option>
                    <option>Pneus</option>
                    <option>Outros</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Data do Serviço *</label>
                <input type="date" name="data_servico" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">KM no Momento</label>
                <input type="number" name="km_servico" class="form-control" placeholder="Ex: 82000">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Oficina</label>
                <input type="text" name="oficina_nome" class="form-control" placeholder="Nome da oficina">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Valor (R$)</label>
                <input type="text" name="valor" class="form-control" placeholder="0,00">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Descrição</label>
                <textarea name="descricao" class="form-control" rows="2" placeholder="Descreva o serviço realizado..."></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Peças Utilizadas</label>
                <textarea name="pecas" class="form-control" rows="2" placeholder="Liste as peças trocadas..."></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Observações</label>
                <textarea name="observacoes" class="form-control" rows="2" placeholder="Observações adicionais..."></textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-save me-2"></i>Salvar Manutenção
            </button>
            <a href="/portal/manutencoes" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
