<?= $this->extend('admin/layout'); ?>

<?= $this->section('contenido'); ?>

<header class="main-header">
    <h2 style="font-size: 32px; font-weight: 600;">Usuarios de Membresía</h2>
</header>

<div class="page-grid">
    <div class="card">
        <div class="card-title" style="display: flex; justify-content: space-between; align-items: center; border-bottom: none; padding-bottom: 0;">
            <h3 style="font-size: 20px; font-weight: 600; margin: 0; color: #FFFFFF;">
                Lista de Usuarios: <?= esc($membresia['nombre']); ?>
            </h3>
            
            <a href="<?= base_url('admin/membresias'); ?>" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; padding: 8px 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Volver a Tipos de Membresía
            </a>
        </div>
        <div style="border-bottom: 1px solid #2C2A3B; margin-top: 20px; margin-bottom: 20px;"></div>

        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>USUARIO</th>
                            <th>NOMBRE COMPLETO</th>
                            <th>CORREO</th>
                            <th>INICIO</th>
                            <th>FIN MEMBRESÍA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" class="text-center" style="color: #A0AEC0; text-align: center; padding-top: 20px; padding-bottom: 20px;">No hay usuarios activos con esta membresía.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= esc($usuario['id_usuario']); ?></td>
                                <td><?= esc($usuario['usuario']); ?></td>
                                <td><?= esc($usuario['nombre']) . ' ' . esc($usuario['apellido']); ?></td>
                                <td><?= esc($usuario['correo']); ?></td>
                                <td><?= esc($usuario['fecha_inicio_membresia'] ? date('d-m-Y', strtotime($usuario['fecha_inicio_membresia'])) : 'N/A'); ?></td>
                                <td><?= esc($usuario['fecha_fin_membresia'] ? date('d-m-Y', strtotime($usuario['fecha_fin_membresia'])) : 'N/A'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>