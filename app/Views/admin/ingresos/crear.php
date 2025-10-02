<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-4">Registrar Ingreso</h1>

<form action="<?= base_url('admin/ingresos/guardar') ?>" method="post">
    <div class="mb-3">
        <label for="id_disco" class="form-label">Disco</label>
        <select name="id_disco" id="id_disco" class="form-control" required>
            <option value="">-- Selecciona un disco --</option>
            <?php foreach ($discos as $disco): ?>
                <option value="<?= $disco['id'] ?>">
                    <?= esc($disco['titulo']) ?> - <?= esc($disco['artista']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad</label>
        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="<?= base_url('admin/ingresos') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
