<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Lista de Membresías</h1>
<a href="<?= base_url('admin/membresias/crear') ?>" class="btn btn-primary">Nueva Membresía</a>

<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Duración (meses)</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($membresias as $m): ?>
            <tr>
                <td><?= esc($m['id']) ?></td>
                <td><?= esc($m['nombre']) ?></td>
                <td><?= esc($m['precio']) ?></td>
                <td><?= esc($m['duracion_meses']) ?></td>
                <td>
                    <a href="<?= base_url('admin/membresias/editar/'.$m['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('admin/membresias/eliminar/'.$m['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta membresía?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
