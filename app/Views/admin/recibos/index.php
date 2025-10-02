<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Listado de Recibos</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recibos)): ?>
                <?php foreach ($recibos as $recibo): ?>
                    <tr>
                        <td><?= esc($recibo['id']) ?></td>
                        <td><?= esc($recibo['username']) ?></td>
                        <td><?= esc($recibo['email']) ?></td>
                        <td><?= esc($recibo['fecha']) ?></td>
                        <td>$<?= number_format($recibo['total'], 2) ?></td>
                        <td>
                            <a href="<?= base_url('admin/recibos/detalle/' . $recibo['id']) ?>" 
                               class="btn btn-sm btn-info">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay recibos registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>


