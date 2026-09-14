<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($titulo) ? $titulo : 'FoodFlow' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="login-body d-flex align-items-center justify-content-center">
    <div class="card login-card shadow">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="brand-badge mx-auto mb-2">FF</div>
                <h3 class="fw-bold mb-0">FoodFlow</h3>
                <p class="text-muted small">Sistema de Pedidos para Restaurante</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger py-2"><?= $error ?></div>
            <?php endif; ?>
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger py-2"><?= validation_errors() ?></div>
            <?php endif; ?>

            <?= form_open('login') ?>
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" value="<?= set_value('usuario') ?>" autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            <?= form_close() ?>

            <hr>
            <p class="small text-muted mb-0">Usuarios demo (clave: <code>123456</code>):</p>
            <p class="small text-muted mb-0">admin · mesero · cocina</p>
        </div>
    </div>
</body>
</html>
