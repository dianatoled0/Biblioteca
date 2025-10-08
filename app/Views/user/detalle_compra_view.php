<?= $this->extend('layoutuser/layout_user') // Usamos tu layout principal ?>

<?= $this->section('content') ?>

<div class="page-grid">
    <div class="card" style="background-color: #201D2E; border: 1px solid #2C2A3B; padding: 0;">
        <div class="card-title" style="margin-bottom: 0; padding: 24px;">
            📋 Detalle de Compra #<?= esc($pedido['id']) ?>
        </div>
        
        <div style="padding: 24px; border-bottom: 1px solid #2C2A3B;">
            <h4 style="font-size: 16px; color: #A0AEC0; margin-bottom: 15px;">Resumen del Pedido</h4>
            <p style="margin-bottom: 8px;"><strong>Fecha de Pedido:</strong> <?= date('d/m/Y', strtotime(esc($pedido['fecha_pedido']))) ?></p>
            <p style="margin-bottom: 8px;"><strong>Estado:</strong> 
                <?php 
                $estado = esc($pedido['estado_pedido']);
                $clase_status = '';
                if ($estado == 'Entregado') {
                    $clase_status = 'status-completed';
                } elseif ($estado == 'Pendiente') {
                    $clase_status = 'status-pending';
                } else {
                    $clase_status = 'status-shipped';
                }
                ?>
                <span class="status <?= $clase_status ?>">
                    <?= $estado ?>
                </span>
            </p>
            <p style="margin-bottom: 0;"><strong>Fecha de Entrega:</strong> <?= $pedido['fecha_entrega'] ? date('d/m/Y', strtotime(esc($pedido['fecha_entrega']))) : '---' ?></p>
        </div>

        <div style="padding: 24px;">
            <h4 style="font-size: 16px; color: #A0AEC0; margin-bottom: 15px;">Discos Comprados</h4>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40%;">Título (Artista)</th>
                            <th style="width: 15%; text-align: right;">Precio Unitario</th>
                            <th style="width: 15%; text-align: center;">Cantidad</th>
                            <th style="width: 20%; text-align: right;">Sub-Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalle as $item): ?>
                            <tr>
                                <td><?= esc($item['titulo']) ?> (<?= esc($item['artista']) ?>)</td>
                                <td style="text-align: right;">Q<?= number_format(esc($item['precio_venta']), 2) ?></td>
                                <td style="text-align: center;"><?= esc($item['cantidad']) ?></td>
                                <td style="text-align: right;">Q<?= number_format(esc($item['sub_total']), 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 700; background-color: #2C2A3B; color: #FFFFFF;">
                                MONTO TOTAL DEL PEDIDO:
                            </td>
                            <td style="text-align: right; font-weight: 700; background-color: #2C2A3B; color: #6D28D9;">
                                Q<?= number_format(esc($pedido['monto_total']), 2) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="form-actions" style="margin-top: 32px;">
                <a href="<?= base_url('usuario/compras') ?>" 
                   style="background-color: #3e3b52; color: #FFFFFF; border: none; padding: 12px 24px; font-size: 16px; font-weight: 500; border-radius: 8px; text-decoration: none; display: inline-block;">
                    ← Volver a Mis Compras
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>