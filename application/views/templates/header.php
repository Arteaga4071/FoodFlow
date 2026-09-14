<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($titulo) ? $titulo . ' - FoodFlow' : 'FoodFlow' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3">
        <div class="brand-badge mb-3">FF</div>
        <h5 class="text-white mb-4">FoodFlow</h5>

        <ul class="nav nav-pills flex-column mb-auto">
            <?php $rol = $this->session->userdata('rol'); ?>

            <?php if ($rol === 'cliente' || $rol === 'mesero' || $rol === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('mesas') ?>" class="nav-link <?= $this->uri->segment(1) === 'mesas' ? 'active' : '' ?>">
                        <i class="bi bi-grid-3x3-gap"></i> Mesas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('pedidos') ?>" class="nav-link <?= $this->uri->segment(1) === 'pedidos' && $this->uri->segment(2) !== 'historial' ? 'active' : '' ?>">
                        <i class="bi bi-receipt"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('pedidos/historial') ?>" class="nav-link <?= $this->uri->segment(2) === 'historial' ? 'active' : '' ?>">
                        <i class="bi bi-clock-history"></i> Historial
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($rol === 'cocina' || $rol === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('cocina') ?>" class="nav-link <?= $this->uri->segment(1) === 'cocina' ? 'active' : '' ?>">
                        <i class="bi bi-egg-fried"></i> Cocina
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($rol === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('menu') ?>" class="nav-link <?= $this->uri->segment(1) === 'menu' ? 'active' : '' ?>">
                        <i class="bi bi-journal-text"></i> Menú
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('reportes') ?>" class="nav-link <?= $this->uri->segment(1) === 'reportes' ? 'active' : '' ?>">
                        <i class="bi bi-bar-chart"></i> Reportes
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <hr class="text-white-50">
        <div class="text-white-50 small mb-2">
            <?= $this->session->userdata('nombre') ?><br>
            <span class="badge bg-secondary text-capitalize"><?= $rol ?></span>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right"></i> Salir
        </a>
    </nav>

    <!-- Contenido -->
    <main class="content-area flex-grow-1 p-4">
