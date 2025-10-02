<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Editar Tipo de Pago</h1>

<form action="<?= base_url('admin/tipos-pago/editar/'.$tipoPago['id']) ?>" method="post">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= esc($tipoPago['nombre']) ?>" required>
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control"><?= esc($tipoPago['descripcion']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="<?= base_url('admin/tipos-pago') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
