<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><i class="bi bi-journal-text"></i> Gestión del Menú</h3>
    <a href="<?= site_url('menu/nuevo') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo producto
    </a>
</div>

<?php if ($this->session->flashdata('exito')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('exito') ?></div>
<?php endif; ?>

<div class="row g-3">
<?php foreach ($items as $item): ?>
    <div class="col-md-4 col-lg-3">
        <div class="card menu-item-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="badge bg-secondary mb-2"><?= html_escape($item->categoria_nombre) ?></span>
                    <?php if (!$item->disponible): ?>
                        <span class="badge bg-danger mb-2">No disponible</span>
                    <?php endif; ?>
                </div>
                <h5 class="card-title mb-1"><?= html_escape($item->nombre) ?></h5>
                <p class="card-text text-muted small"><?= html_escape($item->descripcion) ?></p>
                <p class="fw-bold text-primary">$<?= number_format($item->precio, 0, ',', '.') ?></p>
                <div class="d-flex gap-2">
                    <a href="<?= site_url('menu/editar/' . $item->id) ?>" class="btn btn-sm btn-outline-secondary flex-fill">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger flex-fill btn-eliminar"
                            data-url="<?= site_url('menu/eliminar/' . $item->id) ?>">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($items)): ?>
    <p class="text-muted">No hay productos registrados aún.</p>
<?php endif; ?>
</div>
