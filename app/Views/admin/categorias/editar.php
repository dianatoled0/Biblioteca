<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<h2>Editar Categoría</h2>

<form action="<?= base_url('admin/categorias/actualizar/'.$categoria['id']); ?>" method="post">
    <div class="mb-3">
        <label for="nom_categoria" class="form-label">Nombre de Categoría</label>
        <input type="text" name="nom_categoria" id="nom_categoria" value="<?= $categoria['nom_categoria'] ?>" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?= base_url('admin/categorias'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
