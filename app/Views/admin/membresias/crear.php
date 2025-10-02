<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Nueva Membresía</h1>

<form action="<?= base_url('admin/membresias/crear') ?>" method="post">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Precio</label>
        <input type="number" step="0.01" name="precio" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Duración (meses)</label>
        <input type="number" name="duracion_meses" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="<?= base_url('admin/membresias') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
