<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Lista de Usuarios</h1>
<a href="<?= base_url('admin/usuarios/crear') ?>" class="btn btn-primary">Nuevo Usuario</a>

<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= esc($u['id']) ?></td>
                <td><?= esc($u['nombre']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><?= esc($u['rol']) ?></td>
                <td>
                    <a href="<?= base_url('admin/usuarios/editar/'.$u['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('admin/usuarios/eliminar/'.$u['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>

