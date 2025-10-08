<?php
// Extracción de datos para simplificar
$datos_pedido = $pedido; 
$detalle_discos = $detalle;

// 1. CALCULAR SUBTOTAL DE PRODUCTOS (PRECIO BASE SIN DESCUENTO)
$subtotal_productos_base = array_sum(array_column($detalle_discos, 'sub_total'));

// 2. OBTENER DESCUENTO Y ENVÍO DE LA MEMBRESÍA
$descuento_porcentaje = $datos_pedido['descuento_porcentaje'] ?? 0.00;
$costo_envio_fijo = $datos_pedido['costo_envio_fijo'] ?? 0.00;
$envio_gratis_monto_minimo = $datos_pedido['envio_gratis_monto_minimo'] ?? 999999.00;

// 3. CÁLCULO DEL DESCUENTO APLICADO
$monto_descuento = $subtotal_productos_base * $descuento_porcentaje;
$subtotal_descontado = $subtotal_productos_base - $monto_descuento;

// 4. CÁLCULO DEL COSTO DE ENVÍO
$costo_envio = 0.00;
if ($subtotal_productos_base < $envio_gratis_monto_minimo) {
    $costo_envio = $costo_envio_fijo;
}
// 5. CÁLCULO DE VERIFICACIÓN (Debería coincidir con $datos_pedido['monto_total'])
$monto_total_verificado = $subtotal_descontado + $costo_envio; 
?>

<?= $this->extend('layoutuser/layout_user') ?>

<?= $this->section('content') ?>

<div class="page-grid">
    <div class="card" style="background-color: #201D2E; border: 1px solid #2C2A3B; padding: 0;">
        <div class="card-title" style="margin-bottom: 0; padding: 24px;">
             Detalle de Compra #<?= esc($datos_pedido['id']) ?>
        </div>
        
        <div style="padding: 24px; border-bottom: 1px solid #2C2A3B;">
            <h4 style="font-size: 16px; color: #A0AEC0; margin-bottom: 15px;">Resumen del Pedido</h4>
            <p style="margin-bottom: 8px;"><strong>Fecha de Pedido:</strong> <?= date('d/m/Y', strtotime(esc($datos_pedido['fecha_pedido']))) ?></p>
            <p style="margin-bottom: 8px;">
                <strong>Membresía Aplicada:</strong> 
                <?= esc($datos_pedido['nombre']) ?> 
                <?php if ($descuento_porcentaje > 0): ?>
                    (<span style="color:#10B981; font-weight:600;"><?= number_format(esc($descuento_porcentaje) * 100, 0) ?>% de Descuento en Productos</span>)
                <?php endif; ?>
            </p>
            <p style="margin-bottom: 8px;"><strong>Estado:</strong> 
                <?php 
                $estado = esc($datos_pedido['estado_pedido']);
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
            <p style="margin-bottom: 0;"><strong>Fecha de Entrega:</strong> <?= $datos_pedido['fecha_entrega'] ? date('d/m/Y', strtotime(esc($datos_pedido['fecha_entrega']))) : '---' ?></p>
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
                        <?php foreach ($detalle_discos as $item): ?>
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
                            <td colspan="3" style="text-align: right; font-weight: 500; background-color: transparent; color: #A0AEC0; padding-top: 15px;">
                                Subtotal (Precio Base):
                            </td>
                            <td style="text-align: right; font-weight: 500; background-color: transparent; color: #E2E8F0; padding-top: 15px;">
                                Q<?= number_format($subtotal_productos_base, 2) ?>
                            </td>
                        </tr>
                        <?php if ($monto_descuento > 0): ?>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 500; background-color: transparent; color: #A0AEC0;">
                                Descuento aplicado (<?= number_format(esc($descuento_porcentaje) * 100, 0) ?>%):
                            </td>
                            <td style="text-align: right; font-weight: 500; background-color: transparent; color: #10B981;">
                                - Q<?= number_format($monto_descuento, 2) ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                         <tr>
                            <td colspan="3" style="text-align: right; font-weight: 500; background-color: transparent; color: #A0AEC0;">
                                Costo de Envío:
                                <?php if($costo_envio == 0.00): ?>
                                    <span style="font-size: 12px; color: #10B981;">(Envío Gratis)</span>
                                <?php elseif($subtotal_productos_base < $envio_gratis_monto_minimo): ?>
                                    <span style="font-size: 12px; color: #F59E0B;">(Compra mínima Q<?= number_format(esc($envio_gratis_monto_minimo), 2) ?> para envío gratis)</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right; font-weight: 500; background-color: transparent; color: #E2E8F0;">
                                Q<?= number_format($costo_envio, 2) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 700; background-color: #2C2A3B; color: #FFFFFF; padding-top: 15px;">
                                MONTO TOTAL DEL PEDIDO:
                            </td>
                            <td style="text-align: right; font-weight: 700; background-color: #2C2A3B; color: #6D28D9; padding-top: 15px;">
                                Q<?= number_format(esc($datos_pedido['monto_total']), 2) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="form-actions" style="margin-top: 32px;">
                <a href="<?= base_url('usuario/compras') ?>" 
                   style="background-color: #6D28D9; color: #FFFFFF; border: none; padding: 12px 24px; font-size: 16px; font-weight: 500; border-radius: 8px; text-decoration: none; display: inline-block;">
                    ← Volver a Mis Compras
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>