<h3 class="fw-bold mb-4"><i class="bi bi-receipt"></i> <?= $this->session->userdata('rol') === 'cliente' ? 'Mis pedidos' : 'Pedidos activos' ?></h3>

<?php if ($this->session->flashdata('exito')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('exito') ?></div>
<?php endif; ?>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
    <thead>
        <tr>
            <th>Mesa</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Creado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pedidos as $p): ?>
        <tr>
            <td>Mesa <?= $p->mesa_numero ?></td>
            <td><span class="badge badge-estado-<?= $p->estado ?>"><?= str_replace('_', ' ', $p->estado) ?></span></td>
            <td>$<?= number_format($p->total, 0, ',', '.') ?></td>
            <td><?= date('d/m/Y H:i', strtotime($p->creado_en)) ?></td>
            <td class="text-end">
                <?php if ($this->session->userdata('rol') !== 'cliente'): ?>
                    <a href="<?= site_url('pedidos/nuevo/' . $p->mesa_id) ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                <?php endif; ?>
                <?php if ($this->session->userdata('rol') !== 'cliente' && ($p->estado === 'listo' || $p->estado === 'entregado')): ?>
                    <button class="btn btn-sm btn-success btn-confirmar-cierre" data-url="<?= site_url('pedidos/cerrar/' . $p->id) ?>">
                        <i class="bi bi-cash-coin"></i> Cerrar / Pagar
                    </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($pedidos)): ?>
        <tr><td colspan="5" class="text-muted text-center py-4">No hay pedidos activos.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
