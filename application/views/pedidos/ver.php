<h3 class="fw-bold mb-4"><i class="bi bi-receipt"></i> Pedido #<?= $pedido->id ?> - Mesa <?= $pedido->mesa_numero ?></h3>

<div class="card mb-3" style="max-width: 600px;">
<div class="card-body">
    <p><strong>Mesero:</strong> <?= html_escape($pedido->mesero_nombre) ?></p>
    <p><strong>Estado:</strong> <span class="badge badge-estado-<?= $pedido->estado ?>"><?= str_replace('_', ' ', $pedido->estado) ?></span></p>
    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido->creado_en)) ?></p>

    <table class="table">
        <thead><tr><th>Producto</th><th>Cant.</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php foreach ($detalles as $d): ?>
            <tr>
                <td><?= html_escape($d->producto_nombre) ?></td>
                <td><?= $d->cantidad ?></td>
                <td>$<?= number_format($d->subtotal, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p class="text-end fw-bold fs-5">Total: $<?= number_format($pedido->total, 0, ',', '.') ?></p>
</div>
</div>

<a href="<?= site_url('pedidos/historial') ?>" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Volver al historial
</a>
