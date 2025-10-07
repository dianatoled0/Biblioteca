<!DOCTYPE html>
<html>
<head>
    <title><?= esc($titulo) ?></title>
    <style>
        body { font-family: sans-serif; font-size: 10px; margin: 40px; }
        h1 { color: #333; text-align: center; margin-bottom: 20px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; color: #555; font-size: 9px; text-transform: uppercase; }
        .stock-bajo { background-color: #fee2e2; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: right; padding: 10px; font-size: 8px; color: #999; }
    </style>
</head>
<body>
    <h1><?= esc($titulo) ?></h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Artista</th>
                <th>Categoría</th>
                <th>Precio Venta (Q)</th>
                <th>Stock Disponible</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $d): ?>
            <tr class="<?= ($d['stock'] ?? 0) < 5 ? 'stock-bajo' : '' ?>">
                <td><?= esc($d['id']) ?></td>
                <td><?= esc($d['titulo']) ?></td>
                <td><?= esc($d['artista']) ?></td>
                <td><?= esc($d['nom_categoria'] ?? 'Sin Categoría') ?></td>
                <td>Q <?= number_format($d['precio_venta'] ?? 0, 2) ?></td>
                <td><?= esc($d['stock'] ?? 0) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Reporte generado el: <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>