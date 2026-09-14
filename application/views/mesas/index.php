<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap"></i> Mesas</h3>
    <?php if ($this->session->userdata('rol') === 'admin'): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaMesa">
        <i class="bi bi-plus-lg"></i> Nueva mesa
    </button>
    <?php endif; ?>
</div>

<?php if ($this->session->flashdata('exito')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('exito') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<div class="row g-3">
<?php foreach ($mesas as $mesa): ?>
  <?php $es_cliente = $this->session->userdata('rol') === 'cliente'; ?>
  <?php $puede_elegir = !$es_cliente || $mesa->estado === 'libre'; ?>
    <div class="col-md-3">
    <div class="card mesa-card <?= $mesa->estado === 'libre' ? 'mesa-libre' : 'mesa-ocupada' ?> p-3 <?= !$puede_elegir ? 'mesa-no-disponible' : '' ?>"
       <?= $puede_elegir ? "onclick=\"window.location='" . site_url('pedidos/nuevo/' . $mesa->id) . "'\"" : '' ?>>
            <h2 class="fw-bold mb-0">Mesa <?= $mesa->numero ?></h2>
            <p class="mb-1"><i class="bi bi-people"></i> Capacidad: <?= $mesa->capacidad ?></p>
            <span class="badge bg-light text-dark text-uppercase"><?= $mesa->estado ?></span>
            <?php if (!$es_cliente): ?>
            <?= form_open('mesas/cambiar_estado/' . $mesa->id, array('class' => 'mt-3', 'onclick' => 'event.stopPropagation()')) ?>
              <input type="hidden" name="estado" value="<?= $mesa->estado === 'libre' ? 'ocupada' : 'libre' ?>">
              <button type="submit" class="btn btn-light btn-sm w-100">
                <i class="bi <?= $mesa->estado === 'libre' ? 'bi-person-fill' : 'bi-check-circle' ?>"></i>
                <?= $mesa->estado === 'libre' ? 'Ocupar mesa' : 'Desocupar mesa' ?>
              </button>
            <?= form_close() ?>
            <?php elseif (!$puede_elegir): ?>
              <span class="small mt-3">No disponible para reservar</span>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($mesas)): ?>
    <p class="text-muted">No hay mesas registradas.</p>
<?php endif; ?>
</div>

<!-- Modal nueva mesa -->
<div class="modal fade" id="modalNuevaMesa" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <?= form_open('mesas/crear') ?>
      <div class="modal-header">
        <h5 class="modal-title">Nueva mesa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Número de mesa</label>
          <input type="number" name="numero" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Capacidad</label>
          <input type="number" name="capacidad" class="form-control" value="4" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
      <?= form_close() ?>
    </div>
  </div>
</div>
