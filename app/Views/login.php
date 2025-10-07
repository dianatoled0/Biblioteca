<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/melofy1.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/melofy3.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/melofy2.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>Melofy</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Poppins&display=swap');
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f0f0f, #1e002f);
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(25, 0, 50, 0.8);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 0 20px #a259ff;
            text-align: center;
            width: 320px;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            color: #a259ff;
            margin-bottom: 10px;
        }

        .slogan {
            font-size: 16px;
            color: #ccc;
            margin-bottom: 30px;
        }

        .login-form label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-size: 14px;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #1a1a1a;
            color: white;
            font-size: 14px;
        }

        .login-form input::placeholder {
            color: #999;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: #a259ff;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-form button:hover {
            background-color: #c084fc;
        }

        .register-text {
            margin-top: 15px;
            font-size: 13px;
        }

        .register-text a {
            color: #8a63d2;
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="logo">Melofy</h1>
        <h2 class="slogan">Tu ritmo, tu mundo </h2>
        <?php if (session()->getFlashdata('error')): ?>
            <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>
        
        <form class="login-form" action="<?= base_url('autenticar') ?>" method="post">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>

            <label for="pass">Contraseña</label>
            <input type="password" id="pass" name="pass" placeholder="Ingresa tu contraseña" required>

            <button type="submit">Iniciar Sesión</button>

        </form>

    </div>
</body>
</html>