<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><i class="bi bi-receipt"></i> Pedido - Mesa <?= $mesa->numero ?></h3>
    <a href="<?= site_url('mesas') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver a mesas
    </a>
</div>

<div class="row g-4">
    <!-- Menú disponible -->
    <div class="col-lg-7">
        <?php if (empty($menu_agrupado)): ?>
            <p class="text-muted">No hay productos disponibles en el menú.</p>
        <?php endif; ?>

        <?php foreach ($menu_agrupado as $categoria => $productos): ?>
            <h6 class="text-uppercase text-muted mt-3"><?= html_escape($categoria) ?></h6>
            <div class="row g-2 mb-3">
                <?php foreach ($productos as $p): ?>
                    <div class="col-md-6">
                        <div class="card menu-item-card p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= html_escape($p->nombre) ?></strong><br>
                                    <span class="text-muted small">$<?= number_format($p->precio, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <input type="number" min="1" value="1" class="form-control form-control-sm cantidad-input" style="width:60px">
                                    <button class="btn btn-sm btn-primary btn-agregar-item"
                                            data-id="<?= $p->id ?>">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Resumen del pedido -->
    <div class="col-lg-5">
        <div class="card sticky-top" style="top: 1rem;">
            <div class="card-header bg-white fw-bold">Resumen del pedido</div>
            <ul class="list-group list-group-flush" id="lista-detalles">
                <?php foreach ($detalles as $d): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-detalle-id="<?= $d->id ?>">
                        <span><?= $d->cantidad ?> x <?= html_escape($d->producto_nombre) ?></span>
                        <span>
                            $<?= number_format($d->subtotal, 0, ',', '.') ?>
                            <button class="btn btn-sm btn-link text-danger btn-quitar-item" data-detalle-id="<?= $d->id ?>">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <strong>Total</strong>
                <strong id="total-pedido">$<?= number_format($pedido->total, 0, ',', '.') ?></strong>
            </div>
            <div class="card-body pt-0">
                <?php if ($pedido->estado === 'pendiente' && $pedido->total == 0): ?>
                    <p class="small text-muted mb-2">Agrega productos y confirma para enviarlo a cocina.</p>
                <?php endif; ?>
                <?= form_open('pedidos/confirmar/' . $pedido->id) ?>
                    <button type="submit" class="btn btn-primary w-100" <?= (float) $pedido->total <= 0 ? 'disabled' : '' ?>>
                        <i class="bi bi-check2-circle"></i> Confirmar y enviar a cocina
                    </button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="pedido_id" value="<?= $pedido->id ?>">
<input type="hidden" id="csrf_name" value="<?= $this->security->get_csrf_token_name() ?>">
<input type="hidden" id="csrf_hash" value="<?= $this->security->get_csrf_hash() ?>">
<input type="hidden" id="url_agregar_item" value="<?= site_url('pedidos/agregar_item') ?>">
<input type="hidden" id="url_quitar_item" value="<?= site_url('pedidos/quitar_item') ?>">
