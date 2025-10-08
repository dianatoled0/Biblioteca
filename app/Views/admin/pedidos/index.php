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

        <div class="form-actions" style="margin-bottom: 20px; display: flex; justify-content: flex-start; align-items: center; gap: 10px;">
            <form method="get" action="<?= base_url('admin/pedidos') ?>" style="display: flex; gap: 10px; align-items: center;">
                
                <label for="membresia_id" style="color: #bbb; margin-right: 10px; font-size: 14px;">Filtrar por Membresía:</label>
                
                <select 
                    name="membresia_id" 
                    id="membresia_id" 
                    class="form-control" 
                    onchange="this.form.submit()" 
                    style="
                        padding: 8px 12px;
                        border-radius: 6px; 
                        border: 1px solid #444; 
                        background-color: #333; 
                        color: #FFFFFF; 
                        font-size: 14px;
                        cursor: pointer;
                        
                        -webkit-appearance: none; 
                        -moz-appearance: none; 
                        appearance: none; 
                        
                        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNGRkZGRkYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0iaWNvbiI+PHBvbHlsaW5lIHBvaW50cz0iNiA5IDEyIDE1IDE4IDkiPjwvcG9seWxpbmU+PC9zdmc+');
                        
                        background-repeat: no-repeat;
                        background-position: right 8px center;
                        padding-right: 30px; 
                    "
                >
                    <option value="" style="background-color: #333; color: #FFFFFF;">Todas las Membresías</option>
                    <?php foreach ($membresias as $membresia): ?>
                        <option value="<?= $membresia['id'] ?>"
                            style="background-color: #333; color: #FFFFFF;"
                            <?= $selected_membresia == $membresia['id'] ? 'selected' : '' ?>>
                            <?= esc($membresia['nombre']) ?> (<?= number_format(($membresia['descuento_porcentaje'] ?? 0) * 100, 0) ?>%)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if (!empty($pedidos) && is_array($pedidos)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Membresía</th>
                        <th>Fecha de Pedido</th>
                        <th>Monto Total</th> 
                        <th>Estado</th>
                        <th>Fecha de Entrega</th>
                        <th>Ver Detalle</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr style="<?= $pedido['id_membresia'] == 3 ? 'background-color: #4A5568;' : '' ?>">
                            <td><?= $pedido['id'] ?></td>
                            <td><?= esc($pedido['nombre'] . ' ' . $pedido['apellido']) ?></td>
                            <td style="<?= $pedido['id_membresia'] == 3 ? 'font-weight: bold; color: #63B3ED;' : '' ?>">
                                <?= esc($pedido['nom_membresia']) ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></td>
                            <td>Q<?= number_format($pedido['monto_total'] ?? 0, 2) ?></td> 
                            
                            <td>
                                <form method="post" action="<?= base_url('admin/pedidos/estado/' . $pedido['id']) ?>" style="display:inline;">
                                    <select name="estado" style="padding: 5px; border-radius: 4px; background-color: #1A202C; color: #fff; border: 1px solid #4A5568;" onchange="this.form.submit()">
                                        <?php foreach ($estados_validos as $estado): ?>
                                            <option value="<?= $estado ?>"
                                                <?= $pedido['estado_pedido'] === $estado ? 'selected' : '' ?>>
                                                <?= $estado ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= csrf_field() ?>
                                </form>
                            </td>

                            <td><?= !empty($pedido['fecha_entrega']) ? date('d/m/Y', strtotime($pedido['fecha_entrega'])) : '---' ?></td>

                            <td class="table-actions">
                                <a href="<?= base_url('admin/pedidos/detalle/' . $pedido['id']) ?>" title="Ver Detalle" style="color: #63B3ED; text-decoration: underline; font-weight: 500;"> Ver detalles
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