<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #181818;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        header {
            background: #03dac6;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        a {
            color: #03dac6;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>Panel de Usuario</header>
    <div class="content">
        <h2>Bienvenido, <?= session('nombre') ?> <?= session('apellido') ?></h2>
        <p>Has iniciado sesión como <strong>Usuario</strong>.</p>
        <p><a href="<?= base_url('login/salir') ?>">Cerrar Sesión</a></p>
    </div>
</body>
</html>
