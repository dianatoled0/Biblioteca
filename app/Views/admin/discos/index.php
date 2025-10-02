<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Listado de Discos</h2>
    <a href="<?= base_url('admin/discos/crear') ?>" class="btn btn-primary mb-3">+ Nuevo Disco</a>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Categoría</th>
                <th>Título</th>
                <th>Artista</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($discos)): ?>
                <?php foreach($discos as $disco): ?>
                    <tr>
                        <td><?= $disco['id'] ?></td>
                        <td><?= $disco['nom_categoria'] ?></td>
                        <td><?= $disco['titulo'] ?></td>
                        <td><?= $disco['artista'] ?></td>
                        <td>Q <?= number_format($disco['precio_venta'], 2) ?></td>
                        <td><?= $disco['stock'] ?></td>
                        <td>
                            <a href="<?= base_url('admin/discos/editar/'.$disco['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url('admin/discos/eliminar/'.$disco['id']) ?>" 
                               onclick="return confirm('¿Estás seguro de eliminar este disco?')" 
                               class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No hay discos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
