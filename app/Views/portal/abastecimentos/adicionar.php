<?php $pageTitle = 'Novo Abastecimento'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/portal/abastecimentos" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><i class="fas fa-gas-pump me-2 text-warning"></i>Registrar Abastecimento</h4>
</div>

<div class="stat-card" style="max-width:600px">
    <form method="POST" action="/portal/abastecimentos/salvar">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Data *</label>
                <input type="date" name="data_abast" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Combustível *</label>
                <select name="combustivel" class="form-select" required>
                    <option value="gasolina">Gasolina</option>
                    <option value="etanol">Etanol</option>
                    <option value="diesel">Diesel</option>
                    <option value="gnv">GNV</option>
                    <option value="eletrico">Elétrico</option>
                    <option value="flex">Flex</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Litros *</label>
                <input type="text" name="litros" id="litros" class="form-control" placeholder="0,000" required oninput="calcTotal()">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Valor por Litro (R$) *</label>
                <input type="text" name="valor_litro" id="valor_litro" class="form-control" placeholder="0,000" required oninput="calcTotal()">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Total (R$)</label>
                <input type="text" id="valor_total_display" class="form-control bg-light" placeholder="Calculado automaticamente" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">KM Atual</label>
                <input type="number" name="km_abastecido" class="form-control" placeholder="Ex: 82000">
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Posto</label>
                <input type="text" name="posto_nome" class="form-control" placeholder="Nome do posto">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Cidade</label>
                <input type="text" name="cidade" class="form-control" placeholder="Cidade">
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tanque_cheio" id="tanque_cheio" checked>
                    <label class="form-check-label" for="tanque_cheio">Tanque cheio</label>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-warning text-dark px-4"><i class="fas fa-save me-2"></i>Salvar</button>
            <a href="/portal/abastecimentos" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
function calcTotal() {
    const l = parseFloat(document.getElementById('litros').value.replace(',','.')) || 0;
    const v = parseFloat(document.getElementById('valor_litro').value.replace(',','.')) || 0;
    document.getElementById('valor_total_display').value = 'R$ ' + (l * v).toFixed(2).replace('.',',');
}
</script>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
