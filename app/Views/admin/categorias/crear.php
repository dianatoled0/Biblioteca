<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<h2>Agregar Categoría</h2>

<form action="<?= base_url('admin/categorias/guardar'); ?>" method="post">
    <div class="mb-3">
        <label for="nom_categoria" class="form-label">Nombre de Categoría</label>
        <input type="text" name="nom_categoria" id="nom_categoria" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="<?= base_url('admin/categorias'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
