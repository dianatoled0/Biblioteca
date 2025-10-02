<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Editar Usuario</h1>

<form action="<?= base_url('admin/usuarios/editar/'.$usuario['id']) ?>" method="post">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= esc($usuario['nombre']) ?>" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= esc($usuario['email']) ?>" required>
    </div>
    <div class="form-group">
        <label>Contraseña (dejar en blanco si no se cambia)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="form-group">
        <label>Rol</label>
        <select name="rol" class="form-control">
            <option value="usuario" <?= $usuario['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
            <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
