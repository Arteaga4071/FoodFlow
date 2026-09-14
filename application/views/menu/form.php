<h3 class="fw-bold mb-4">
    <i class="bi bi-journal-text"></i> <?= $item ? 'Editar producto' : 'Nuevo producto' ?>
</h3>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger"><?= validation_errors() ?></div>
<?php endif; ?>

<div class="card" style="max-width: 560px;">
<div class="card-body">
<?= form_open($item ? 'menu/editar/' . $item->id : 'menu/nuevo') ?>

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control"
               value="<?= set_value('nombre', $item ? $item->nombre : '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="2"><?= set_value('descripcion', $item ? $item->descripcion : '') ?></textarea>
    </div>

    <div class="row">
        <div class="col-6 mb-3">
            <label class="form-label">Precio (COP)</label>
            <input type="number" step="1" name="precio" class="form-control"
                   value="<?= set_value('precio', $item ? $item->precio : '') ?>">
        </div>
        <div class="col-6 mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select">
                <option value="">-- Selecciona --</option>
                <?php foreach ($categorias as $cat): ?>
                    <?php $sel = $item && $item->categoria_id == $cat->id ? 'selected' : ''; ?>
                    <option value="<?= $cat->id ?>" <?= $sel ?>><?= html_escape($cat->nombre) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-check mb-4">
        <input type="checkbox" name="disponible" class="form-check-input" id="disponible"
               <?= (!$item || $item->disponible) ? 'checked' : '' ?>>
        <label class="form-check-label" for="disponible">Disponible en el menú</label>
    </div>

    <a href="<?= site_url('menu') ?>" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar</button>

<?= form_close() ?>
</div>
</div>
