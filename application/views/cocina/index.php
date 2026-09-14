<h3 class="fw-bold mb-4"><i class="bi bi-egg-fried"></i> Pedidos en cocina</h3>

<div class="row g-3">
<?php foreach ($pedidos as $p): ?>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Mesa <?= $p->mesa_numero ?></strong>
                <span class="badge badge-estado-<?= $p->estado ?>"><?= str_replace('_', ' ', $p->estado) ?></span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-3">
                    <?php foreach ($p->detalles as $d): ?>
                        <li><?= $d->cantidad ?> x <?= html_escape($d->producto_nombre) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="text-muted small mb-2">
                    <i class="bi bi-clock"></i> <?= date('H:i', strtotime($p->creado_en)) ?>
                </p>

                <?php if ($p->estado === 'pendiente'): ?>
                    <a href="<?= site_url('cocina/avanzar_estado/' . $p->id) ?>" class="btn btn-primary w-100">
                        <i class="bi bi-play-fill"></i> Iniciar preparación
                    </a>
                <?php elseif ($p->estado === 'en_preparacion'): ?>
                    <a href="<?= site_url('cocina/avanzar_estado/' . $p->id) ?>" class="btn btn-success w-100">
                        <i class="bi bi-check2"></i> Marcar como listo
                    </a>
                <?php elseif ($p->estado === 'listo'): ?>
                    <a href="<?= site_url('cocina/avanzar_estado/' . $p->id) ?>" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-box-arrow-right"></i> Marcar entregado
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($pedidos)): ?>
    <p class="text-muted">No hay pedidos pendientes en este momento. 🎉</p>
<?php endif; ?>
</div>
