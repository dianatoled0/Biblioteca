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
        
        /* Estilos para el encabezado fijo */
        .header { 
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            height: 40px; 
            padding: 10px 40px 0; 
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        .company-name {
            float: left;
            font-size: 14px;
            font-weight: bold;
            color: #6D28D9; 
            line-height: 20px;
        }
        .info {
            float: right;
            text-align: right;
        }
        
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            text-align: right; 
            padding: 10px 40px; 
            font-size: 8px; 
            color: #999; 
        }
    </style>
</head>
<body>
    
    <div class="header">
        <div class="company-name"><?= esc($nombre_empresa) ?></div>
        <div class="info">
            <strong>Generado por:</strong> <?= esc($usuario_generador) ?><br>
            <strong>Fecha de Emisión:</strong> <?= esc($fecha_emision) ?>
        </div>
    </div>

    <h1 style="margin-top: 80px;"><?= esc($titulo) ?></h1>
    
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Fecha Pedido</th>
                <th>Monto Total (Q)</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $p): ?>
            <tr>
                <td>#<?= esc($p['id']) ?></td>
                <td><?= esc($p['nombre'] . ' ' . $p['apellido']) ?> (ID: <?= esc($p['id_user']) ?>)</td>
                <td><?= esc($p['fecha_pedido']) ?></td>
                <td>Q <?= number_format($p['monto_total'] ?? 0, 2) ?></td>
                <td><?= esc($p['estado_pedido']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Reporte generado el: <?= esc($fecha_emision) ?>
    </div>
</body>
</html>