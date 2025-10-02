<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Editar Disco</h2>

    <form action="<?= base_url('admin/discos/editar/'.$disco['id']) ?>" method="post">
        <div class="mb-3">
            <label>Categoría</label>
            <select name="id_categoria" class="form-control" required>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $disco['id_categoria'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= $cat['nom_categoria'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= $disco['titulo'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Artista</label>
            <input type="text" name="artista" class="form-control" value="<?= $disco['artista'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Precio de Venta</label>
            <input type="number" step="0.01" name="precio_venta" class="form-control" value="<?= $disco['precio_venta'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= $disco['stock'] ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="<?= base_url('admin/discos') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>
