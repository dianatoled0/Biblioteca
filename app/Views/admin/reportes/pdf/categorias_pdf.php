<!DOCTYPE html>
<html>
<head>
    <title><?= esc($titulo) ?></title>
    <style>
        body { font-family: sans-serif; font-size: 10px; margin: 40px; }
        h1 { color: #333; text-align: center; margin-bottom: 20px; font-size: 18px; }
        table { width: 60%; margin: 15px auto; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; color: #555; font-size: 9px; text-transform: uppercase; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: right; padding: 10px; font-size: 8px; color: #999; }
    </style>
</head>
<body>
    <h1><?= esc($titulo) ?></h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Categoría</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $c): ?>
            <tr>
                <td><?= esc($c['id']) ?></td>
                <td><?= esc($c['nom_categoria']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Reporte generado el: <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>