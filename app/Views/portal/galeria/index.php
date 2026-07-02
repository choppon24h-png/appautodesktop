<?php $pageTitle = 'Galeria de Fotos'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-images me-2 text-pink" style="color:#ec4899"></i>Galeria de Fotos</h4>
    <button class="btn btn-pink text-white" style="background:#ec4899;border-color:#ec4899" data-bs-toggle="modal" data-bs-target="#modalFoto">
        <i class="fas fa-camera me-2"></i>Adicionar Foto
    </button>
</div>

<?php
$categorias = ['exterior'=>'Exterior','interior'=>'Interior','motor'=>'Motor','dano'=>'Danos','manutencao'=>'Manutenção','outro'=>'Outros'];
$fotosPorCategoria = [];
foreach ($fotos as $f) { $fotosPorCategoria[$f['categoria']][] = $f; }
?>

<?php if (empty($fotos)): ?>
<div class="stat-card text-center py-5">
    <i class="fas fa-images fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Nenhuma foto cadastrada</h5>
    <button class="btn mt-2" style="background:#ec4899;color:#fff" data-bs-toggle="modal" data-bs-target="#modalFoto">Adicionar Foto</button>
</div>
<?php else: ?>
<?php foreach ($fotosPorCategoria as $cat => $fotos_cat): ?>
<div class="mb-4">
    <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:.75rem;letter-spacing:.1em">
        <?= $categorias[$cat] ?? ucfirst($cat) ?> (<?= count($fotos_cat) ?>)
    </h6>
    <div class="row g-2">
        <?php foreach ($fotos_cat as $f): ?>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="position-relative" style="cursor:pointer" onclick="verFoto('<?= htmlspecialchars($f['arquivo']) ?>', '<?= htmlspecialchars($f['titulo'] ?? '') ?>')">
                <img src="<?= htmlspecialchars($f['arquivo']) ?>" class="img-fluid rounded" style="width:100%;height:120px;object-fit:cover"
                     onerror="this.src='/assets/img/sem-foto.png'" alt="<?= htmlspecialchars($f['titulo'] ?? '') ?>">
                <?php if (!empty($f['titulo'])): ?>
                <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.6);color:#fff;font-size:.65rem;padding:3px 6px;border-radius:0 0 4px 4px">
                    <?= htmlspecialchars($f['titulo']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- Modal upload -->
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-camera me-2"></i>Adicionar Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/portal/galeria/salvar" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Foto *</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Categoria</label>
                            <select name="categoria" class="form-select">
                                <?php foreach ($categorias as $v => $l): ?>
                                <option value="<?= $v ?>"><?= $l ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data</label>
                            <input type="date" name="data_foto" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Título / Descrição</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex: Arranhão na porta dianteira">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white px-4" style="background:#ec4899"><i class="fas fa-upload me-2"></i>Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal visualização -->
<div class="modal fade" id="modalVisualizacao" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h6 class="modal-title text-white" id="tituloFoto"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="fotoGrande" src="" class="img-fluid" style="max-height:70vh">
            </div>
        </div>
    </div>
</div>

<script>
function verFoto(src, titulo) {
    document.getElementById('fotoGrande').src = src;
    document.getElementById('tituloFoto').textContent = titulo;
    new bootstrap.Modal(document.getElementById('modalVisualizacao')).show();
}
</script>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
