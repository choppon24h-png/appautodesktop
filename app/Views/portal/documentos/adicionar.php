<?php $pageTitle = 'Enviar Documento'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/portal/documentos" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><i class="fas fa-upload me-2" style="color:#8b5cf6"></i>Enviar Documento</h4>
</div>

<div class="stat-card" style="max-width:600px">
    <form method="POST" action="/portal/documentos/salvar" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tipo de Documento *</label>
                <select name="tipo" class="form-select" required>
                    <option value="crlv">CRLV</option>
                    <option value="cnh">CNH</option>
                    <option value="seguro">Apólice de Seguro</option>
                    <option value="nota_fiscal">Nota Fiscal</option>
                    <option value="laudo">Laudo / Vistoria</option>
                    <option value="ipva">IPVA / Licenciamento</option>
                    <option value="outro">Outros</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Título</label>
                <input type="text" name="titulo" class="form-control" placeholder="Ex: CRLV 2025">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Arquivo *</label>
                <input type="file" name="arquivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                <small class="text-muted">Formatos: PDF, JPG, PNG. Máximo 10MB.</small>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Observações</label>
                <textarea name="observacao" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn px-4 text-white" style="background:#8b5cf6"><i class="fas fa-upload me-2"></i>Enviar</button>
            <a href="/portal/documentos" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
