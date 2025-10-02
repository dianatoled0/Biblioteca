<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-4">Ingresos</h1>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<a href="<?= base_url('admin/ingresos/crear') ?>" class="btn btn-primary mb-3">+ Nuevo Ingreso</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Disco</th>
            <th>Cantidad</th>
            <th>Fecha de Ingreso</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($ingresos)): ?>
            <?php foreach ($ingresos as $ingreso): ?>
                <tr>
                    <td><?= esc($ingreso['id']) ?></td>
                    <td><?= esc($ingreso['titulo']) ?> - <?= esc($ingreso['artista']) ?></td>
                    <td><?= esc($ingreso['cantidad']) ?></td>
                    <td><?= esc($ingreso['fecha_ingreso']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No hay ingresos registrados</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>

