<?= $this->extend('layoutuser/layout_user') // Usamos tu layout principal ?>

<?= $this->section('content') ?>

<div class="card" style="background-color: #201D2E; border: 1px solid #2C2A3B; padding: 0;">
    <div class="card-title" style="margin-bottom: 0; padding: 24px;">
        🛒 Compras Realizadas
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 20%;">Fecha de Pedido</th>
                    <th style="width: 20%;">Monto Total</th>
                    <th style="width: 20%;">Estado</th>
                    <th style="width: 20%;">Fecha de Entrega</th>
                    <th style="width: 10%;">Más Información</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="text-align: center; color: #A0AEC0;">
                            Aún no has realizado ninguna compra.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= esc($pedido['id']) ?></td>
                            <td><?= date('d/m/Y', strtotime(esc($pedido['fecha_pedido']))) ?></td>
                            <td>Q<?= number_format(esc($pedido['monto_total']), 2) ?></td>
                            <td>
                                <?php 
                                $estado = esc($pedido['estado_pedido']);
                                $clase_status = '';
                                if ($estado == 'Entregado') {
                                    $clase_status = 'status-completed';
                                } elseif ($estado == 'Pendiente') {
                                    $clase_status = 'status-pending';
                                } else {
                                    $clase_status = 'status-shipped'; // Para 'Enviado' u otro
                                }
                                ?>
                                <span class="status <?= $clase_status ?>">
                                    <?= $estado ?>
                                </span>
                            </td>
                            <td>
                                <?= $pedido['fecha_entrega'] ? date('d/m/Y', strtotime(esc($pedido['fecha_entrega']))) : '---' ?>
                            </td>
                            <td class="table-actions">
                                <a href="<?= base_url('usuario/compras/detalle/' . esc($pedido['id'])) ?>" 
                                   title="Más Información"
                                   style="color: #6D28D9; text-decoration: none; font-weight: 500;">
                                    Más información
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>