<h3 class="fw-bold mb-4"><i class="bi bi-clock-history"></i> <?= $this->session->userdata('rol') === 'cliente' ? 'Mi historial de pedidos' : 'Historial de pedidos' ?></h3>

<form method="get" class="row g-2 mb-4">
    <div class="col-auto">
        <input type="date" name="fecha" class="form-control" value="<?= html_escape($fecha) ?>">
    </div>
    <div class="col-auto">
        <input type="number" name="mesa" class="form-control" placeholder="N° mesa" value="<?= html_escape($mesa) ?>">
    </div>
    <div class="col-auto">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
        <a href="<?= site_url('pedidos/historial') ?>" class="btn btn-outline-secondary">Limpiar</a>
    </div>
</form>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Mesa</th>
            <th>Mesero</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Fecha</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pedidos as $p): ?>
        <tr>
            <td>#<?= $p->id ?></td>
            <td>Mesa <?= $p->mesa_numero ?></td>
            <td><?= html_escape($p->mesero_nombre) ?></td>
            <td><span class="badge badge-estado-<?= $p->estado ?>"><?= str_replace('_', ' ', $p->estado) ?></span></td>
            <td>$<?= number_format($p->total, 0, ',', '.') ?></td>
            <td><?= date('d/m/Y H:i', strtotime($p->creado_en)) ?></td>
            <td><a href="<?= site_url('pedidos/ver/' . $p->id) ?>" class="btn btn-sm btn-outline-secondary">Ver</a></td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($pedidos)): ?>
        <tr><td colspan="7" class="text-muted text-center py-4">Sin resultados.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
