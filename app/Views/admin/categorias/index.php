<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<h2>Categorías</h2>
<a href="<?= base_url('admin/categorias/crear'); ?>" class="btn btn-primary mb-3">Agregar Categoría</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre de Categoría</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= $cat['nom_categoria'] ?></td>
                    <td>
                        <a href="<?= base_url('admin/categorias/editar/'.$cat['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="<?= base_url('admin/categorias/eliminar/'.$cat['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">No hay categorías registradas</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
