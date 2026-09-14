<h3 class="fw-bold mb-4"><i class="bi bi-bar-chart"></i> Reportes</h3>

<form method="get" class="row g-2 mb-4">
    <div class="col-auto">
        <label class="form-label small mb-0">Desde</label>
        <input type="date" name="desde" class="form-control" value="<?= html_escape($desde) ?>">
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">Hasta</label>
        <input type="date" name="hasta" class="form-control" value="<?= html_escape($hasta) ?>">
    </div>
    <div class="col-auto align-self-end">
        <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filtrar</button>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background: var(--ff-primary);">
            <div class="card-body">
                <p class="mb-1">Ingresos en el periodo</p>
                <h2 class="fw-bold mb-0">$<?= number_format($ingresos_totales, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white fw-bold">Ventas por día</div>
            <table class="table mb-0">
                <thead><tr><th>Fecha</th><th>Total vendido</th></tr></thead>
                <tbody>
                <?php foreach ($ventas_por_dia as $v): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($v->dia)) ?></td>
                        <td>$<?= number_format($v->total_dia, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($ventas_por_dia)): ?>
                    <tr><td colspan="2" class="text-muted text-center py-3">Sin ventas en este periodo.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-white fw-bold">Productos más vendidos</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($mas_vendidos as $m): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?= html_escape($m->nombre) ?>
                        <span class="badge bg-primary rounded-pill"><?= $m->total_vendido ?></span>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($mas_vendidos)): ?>
                    <li class="list-group-item text-muted">Aún no hay ventas registradas.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
