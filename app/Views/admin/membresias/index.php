<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<header class="main-header">
    <h2>Gestión de Membresías</h2>
</header>

<div class="page-grid">
    <div class="card">
        <h3 class="card-title">Tipos de Membresía Disponibles</h3>
        
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

        <?php if (!empty($membresias) && is_array($membresias)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th> 
                        <th>Duración</th>
                        <th>Características Principales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($membresias as $membresia): ?>
                        <tr>
                            <td><?= $membresia['id'] ?></td>
                            <td><?= esc($membresia['nombre']) ?></td> 
                            <td>Q<?= number_format($membresia['precio'] ?? 0, 2) ?></td> 
                            <td><?= $membresia['duracion'] ?> meses</td>
                            <td><?= esc($membresia['caracteristicas']) ?></td>
                            <td class="table-actions">
                                <a href="<?= base_url('admin/membresias/usuarios/' . $membresia['id']) ?>" title="Ver Usuarios">
                                    <svg class="icon" viewBox="0 0 24 24" stroke="white" fill="white"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p style="color: #A0AEC0;">No hay tipos de membresía definidos.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>