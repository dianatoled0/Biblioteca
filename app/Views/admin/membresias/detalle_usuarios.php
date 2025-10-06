<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<header class="main-header">
    <h2>Usuarios de la Membresía: <?= esc($membresia['nombre']) ?></h2>
</header>

<div class="page-grid">
    <div class="card">
        <h3 class="card-title">
            Listado de Usuarios (<?= count($usuarios) ?> en total)
        </h3>
        
        <div class="form-actions" style="margin-bottom: 20px;">
            <a href="<?= base_url('admin/membresias') ?>" style="color: #A0AEC0; text-decoration: none;">
                &larr; Volver a Membresías
            </a>
        </div>

        <?php if (!empty($usuarios) && is_array($usuarios)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Usuario</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>ID Membresía</th>
                        <th>Inicio Membresía</th>
                        <th>Finalización</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id_usuario'] ?></td>
                            <td><?= esc($usuario['usuario']) ?></td>
                            <td><?= esc($usuario['correo']) ?></td>
                            <td><?= $usuario['membresia_id'] ?> (<?= esc($usuario['membresia_nombre']) ?>)</td>
                            <td><?= date('d-m-Y', strtotime($usuario['fecha_inicio_membresia'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($usuario['fecha_fin_membresia'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p style="color: #A0AEC0;">No hay usuarios asignados a la membresía **<?= esc($membresia['nombre']) ?>**.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>