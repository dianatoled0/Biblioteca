<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS (puedes ajustar a tu versión) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: #fff;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Menú lateral -->
        <nav class="col-md-2 sidebar">
            <h4 class="text-center">Admin</h4>
            <a href="<?= base_url('admin'); ?>">Dashboard</a>
            <a href="<?= base_url('admin/categorias'); ?>">Categorías</a>
            <a href="<?= base_url('admin/discos'); ?>">Discos</a>
            <a href="<?= base_url('admin/usuarios'); ?>">Usuarios</a>
            <a href="<?= base_url('admin/membresias'); ?>">Membresías</a>
            <a href="<?= base_url('admin/pagos'); ?>">Pagos</a>
            <a href="<?= base_url('admin/ingresos'); ?>">Ingresos</a>
            <a href="<?= base_url('admin/recibos'); ?>">Recibos</a>
            <a href="<?= base_url('login/salir'); ?>" class="text-danger">Cerrar sesión</a>
        </nav>

        <!-- Contenido -->
        <main class="col-md-10 ms-sm-auto content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
