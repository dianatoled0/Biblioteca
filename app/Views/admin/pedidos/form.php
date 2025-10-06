<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Detalle del Recibo #<?= esc($recibo['id']) ?></h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Usuario:</strong> <?= esc($recibo['username']) ?> (<?= esc($recibo['email']) ?>)</p>
            <p><strong>Fecha:</strong> <?= esc($recibo['fecha']) ?></p>
            <p><strong>Total:</strong> $<?= number_format($recibo['total'], 2) ?></p>
        </div>
    </div>

    <h4>Discos en este recibo</h4>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Artista</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recibo['detalles'])): ?>
                <?php foreach ($recibo['detalles'] as $detalle): ?>
                    <tr>
                        <td><?= esc($detalle['id']) ?></td>
                        <td><?= esc($detalle['titulo']) ?></td>
                        <td><?= esc($detalle['artista']) ?></td>
                        <td><?= esc($detalle['cantidad']) ?></td>
                        <td>$<?= number_format($detalle['precio'], 2) ?></td>
                        <td>$<?= number_format($detalle['cantidad'] * $detalle['precio'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Este recibo no tiene discos registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= base_url('admin/recibos') ?>" class="btn btn-secondary mt-3">Volver al listado</a>
</div>

<?= $this->endSection() ?>

