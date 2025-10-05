<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<header class="main-header">
    <h2>Gestión de Categorías</h2>
</header>

<div class="page-grid">
    <div class="card">
        <h3 class="card-title">Listado de Categorías</h3>
        
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

        <div class="form-actions" style="margin-bottom: 20px;">
            <a href="<?= base_url('admin/categorias/crear') ?>" class="btn-primary">
                + Crear Nueva Categoría
            </a>
        </div>

        <?php if (!empty($categorias) && is_array($categorias)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= $categoria['id'] ?></td>
                            <td><?= esc($categoria['nom_categoria']) ?></td>
                            <td class="table-actions">
                                <a href="<?= base_url('admin/categorias/editar/' . $categoria['id']) ?>" title="Editar">
                                    <svg class="icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <a href="<?= base_url('admin/categorias/eliminar/' . $categoria['id']) ?>" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                    <svg class="icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p style="color: #A0AEC0;">No hay categorías para mostrar.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
