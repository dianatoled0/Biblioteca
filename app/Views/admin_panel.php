<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        header {
            background: #6200ea;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        nav {
            background: #3700b3;
            padding: 10px;
        }
        nav a {
            color: #fff;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>Panel de Administración</header>
    <nav>
        <a href="<?= base_url('categorias') ?>">Categorías</a>
        <a href="<?= base_url('discos') ?>">Discos</a>
        <a href="<?= base_url('usuarios') ?>">Usuarios</a>
        <a href="<?= base_url('recibos') ?>">Recibos</a>
        <a href="<?= base_url('login/salir') ?>">Cerrar Sesión</a>
    </nav>
    <div class="content">
        <h2>Bienvenido, <?= session('nombre') ?> <?= session('apellido') ?></h2>
        <p>Has iniciado sesión como <strong>Administrador</strong>.</p>
    </div>
</body>
</html>
