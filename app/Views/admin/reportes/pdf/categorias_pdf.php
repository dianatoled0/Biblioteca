<!DOCTYPE html>
<html>
<head>
    <title><?= esc($titulo) ?></title>
    <style>
        body { font-family: sans-serif; font-size: 10px; margin: 40px; }
        h1 { color: #333; text-align: center; margin-bottom: 20px; font-size: 18px; }
        table { width: 80%; margin: 15px auto; border-collapse: collapse; } 
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; color: #555; font-size: 9px; text-transform: uppercase; }
        .count-cell { text-align: center; font-weight: bold; }

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
            line-height: 20px; /* Alineación vertical con la info */
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

    <!-- Encabezado Fijo con Nombre de Empresa y Trazabilidad -->
    <div class="header">
        <div class="company-name"><?= esc($nombre_empresa) ?></div>
        <div class="info">
            <strong>Generado por:</strong> <?= esc($usuario_generador) ?><br>
            <strong>Fecha de Emisión:</strong> <?= esc($fecha_emision) ?>
        </div>
    </div>

    <!-- El margen superior de H1 compensa el encabezado fijo -->
    <h1 style="margin-top: 80px;"><?= esc($titulo) ?></h1> 
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Categoría</th>
                <th style="text-align: center;">Total de Discos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $c): ?>
            <tr>
                <td><?= esc($c['id']) ?></td>
                <td><?= esc($c['nom_categoria']) ?></td>
                <td class="count-cell"><?= esc($c['total_discos']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pie de página con fecha de generación -->
    <div class="footer">
        Reporte generado el: <?= esc($fecha_emision) ?>
    </div>
</body>
</html>