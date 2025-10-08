<?= $this->extend('layoutuser/layout_user') ?>

<?= $this->section('content') ?>

<div class="page-grid">
    <div class="main-header">
        <h2 style="display: flex; align-items: center; gap: 12px;">
            Compras Realizadas
        </h2>
    </div>

    <div class="card">
        <div class="table-container">
            <?php if (empty($pedidos)): ?>
                <p style="padding: 15px; color: #A0AEC0;">Aún no has realizado ninguna compra.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 20%;">FECHA DE PEDIDO</th>
                            <th style="width: 20%;">MONTO TOTAL</th>
                            <th style="width: 20%;">ESTADO</th>
                            <th style="width: 20%;">FECHA DE ENTREGA</th>
                            <th style="width: 15%; text-align: center;"></th> 
                        </tr>
                    </thead>
                    <tbody>
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
                                        $clase_status = 'status-shipped';
                                    }
                                    ?>
                                    <span class="status <?= $clase_status ?>">
                                        <?= $estado ?>
                                    </span>
                                </td>
                                <td><?= $pedido['fecha_entrega'] ? date('d/m/Y', strtotime(esc($pedido['fecha_entrega']))) : '---' ?></td>
                                <td style="text-align: center;">
                                    <a href="<?= base_url('usuario/compras/detalle/' . esc($pedido['id'])) ?>" 
                                       style="color: #6D28D9; text-decoration: none; font-weight: 500;">
                                        Más información
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>