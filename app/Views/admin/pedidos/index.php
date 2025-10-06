<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<header class="main-header">
    <h2>Gestión de Pedidos</h2>
</header>

<div class="page-grid">
    <div class="card">
        <h3 class="card-title">Listado de Pedidos</h3>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background-color: #1a4f38; color: #10B981; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div style="background-color: #5c1a1a; color: #F87171; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div style="background-color: #1A1625; color: #3B82F6; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #3B82F6;">
                <?= session()->getFlashdata('info') ?>
            </div>
        <?php endif; ?>

        <div class="form-actions" style="margin-bottom: 20px;">
            </div>

        <?php if (!empty($pedidos) && is_array($pedidos)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha de Pedido</th>
                        <th>Monto Total</th> 
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= $pedido['id'] ?></td>
                            <td><?= esc($pedido['nombre'] . ' ' . $pedido['apellido']) ?></td>
                            <td><?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></td>
                            <td>Q<?= number_format($pedido['monto_total'] ?? 0, 2) ?></td> 
                            <td>
                                <?php 
                                    // Lógica para aplicar la clase de color basada en el estado
                                    $estado = esc($pedido['estado_pedido']);
                                    $clase = 'status-pending'; 
                                    if ($estado === 'Completado') {
                                        $clase = 'status-completed';
                                    } elseif ($estado === 'Enviado') {
                                        $clase = 'status-shipped';
                                    }
                                ?>
                                <span class="status <?= $clase ?>">
                                    <?= $estado ?>
                                </span>
                            </td>
                            <td class="table-actions">
                                <a href="<?= base_url('admin/pedidos/detalle/' . $pedido['id']) ?>" title="Ver Detalle">
                                    <svg class="icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p style="color: #A0AEC0;">No hay pedidos para mostrar.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>


