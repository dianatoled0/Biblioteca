<?= $this->extend('admin/layout') ?> 

<?= $this->section('contenido') ?>

<header class="main-header">
    <h2>Detalle del Pedido #<?= esc($pedido['id']) ?></h2>
</header>

<div class="page-grid">
    <div class="card">
        <div class="form-actions" style="margin-bottom: 20px;">
            <a href="<?= base_url('admin/pedidos') ?>" class="btn-secondary" style="padding: 8px 15px; background-color: #718096; color: white; border-radius: 4px; text-decoration: none;">
                ← Volver al Listado
            </a>
        </div>

        <h3 class="card-title" style="border-bottom: 1px solid #4A5568; padding-bottom: 10px;">Información del Cliente y Pedido</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; color: #A0AEC0;">
            <div>
                <p><strong>Cliente:</strong> <?= esc($pedido['nom_cliente'] . ' ' . $pedido['ape_cliente']) ?></p>
                <p>
                    <strong>Membresía:</strong> 
                    <span style="font-weight: bold; color: <?= $pedido['nom_membresia'] === 'Premium' ? '#63B3ED' : '#F6AD55' ?>;">
                        <?= esc($pedido['nom_membresia']) ?> 
                    </span>
                    (<?= number_format($pedido['descuento_porcentaje'] * 100, 0) ?>% Desc.)
                </p>
            </div>
            <div>
                <p><strong>Fecha de Pedido:</strong> <?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></p>
                <p>
                    <strong>Estado del Pedido:</strong> 
                    <?php 
                        $estado_clase = 'status-pending'; 
                        if ($pedido['estado_pedido'] === 'Entregado') $estado_clase = 'status-completed'; 
                        elseif ($pedido['estado_pedido'] === 'Enviado') $estado_clase = 'status-shipped';
                    ?>
                    <span class="status <?= $estado_clase ?>">
                        <?= esc($pedido['estado_pedido']) ?>
                    </span>
                </p>
                <p><strong>Fecha de Entrega:</strong> <?= !empty($pedido['fecha_entrega']) ? date('d/m/Y', strtotime($pedido['fecha_entrega'])) : '---' ?></p>
            </div>
        </div>

        <h3 class="card-title" style="border-bottom: 1px solid #4A5568; padding-bottom: 10px; margin-top: 20px;">Detalle de Discos Comprados</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Artista</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal Item</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal_items = 0;
                    if (!empty($pedido['detalle'])): ?>
                        <?php foreach ($pedido['detalle'] as $item): 
                            $subtotal_items += $item['sub_total']; // Acumula el subtotal ya con el descuento aplicado
                        ?>
                            <tr>
                                <td><?= esc($item['titulo']) ?></td>
                                <td><?= esc($item['artista']) ?></td>
                                <td>Q<?= number_format($item['precio_venta'], 2) ?></td> <td><?= esc($item['cantidad']) ?></td>
                                <td>Q<?= number_format($item['sub_total'], 2) ?></td> </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #A0AEC0;">Este pedido no tiene discos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <div style="width: 300px; padding: 15px; border: 1px solid #4A5568; border-radius: 8px; background-color: #2D3748;">
                <?php 
                    // Cálculo del envío basado en el monto total - subtotal de items
                    // Se asume que el monto total ya incluye el costo de envío
                    $costo_envio = max(0, $pedido['monto_total'] - $subtotal_items);
                    $envio_display = $costo_envio == 0.00 ? 'GRATIS' : 'Q' . number_format($costo_envio, 2);
                ?>
                <p style="display: flex; justify-content: space-between;"><span>Subtotal de Discos:</span> <span>Q<?= number_format($subtotal_items, 2) ?></span></p>
                <p style="display: flex; justify-content: space-between; color: #48BB78;">
                    <span>Costo de Envío:</span> 
                    <span><?= $envio_display ?></span>
                </p>
                <p style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2em; border-top: 1px solid #4A5568; padding-top: 10px; margin-top: 10px;">
                    <span>TOTAL DEL PEDIDO:</span> 
                    <span>Q<?= number_format($pedido['monto_total'], 2) ?></span>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>