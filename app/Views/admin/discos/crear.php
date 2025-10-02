<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Nuevo Disco</h2>

    <form action="<?= base_url('admin/discos/crear') ?>" method="post">
        <div class="mb-3">
            <label>Categoría</label>
            <select name="id_categoria" class="form-control" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['nom_categoria'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Artista</label>
            <input type="text" name="artista" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Precio de Venta</label>
            <input type="number" step="0.01" name="precio_venta" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('admin/discos') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>
