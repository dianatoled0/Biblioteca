<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Lista de Tipos de Pago</h1>
<a href="<?= base_url('admin/tipos-pago/crear') ?>" class="btn btn-primary">Nuevo Tipo de Pago</a>

<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tipos_pago as $tp): ?>
            <tr>
                <td><?= esc($tp['id']) ?></td>
                <td><?= esc($tp['nombre']) ?></td>
                <td><?= esc($tp['descripcion']) ?></td>
                <td>
                    <a href="<?= base_url('admin/tipos-pago/editar/'.$tp['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('admin/tipos-pago/eliminar/'.$tp['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este tipo de pago?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
