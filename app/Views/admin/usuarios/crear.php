<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Nuevo Usuario</h1>

<form action="<?= base_url('admin/usuarios/crear') ?>" method="post">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Rol</label>
        <select name="rol" class="form-control">
            <option value="usuario">Usuario</option>
            <option value="admin">Administrador</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
