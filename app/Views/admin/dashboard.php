<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<h1>Bienvenido al Panel de Administración</h1>
<p class="lead">Desde aquí puedes gestionar todas las secciones del sistema de venta de discos.</p>

<div class="row">
    <div class="col-md-3">
        <div class="card text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Categorías</h5>
                <a href="<?= base_url('admin/categorias'); ?>" class="btn btn-light">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Discos</h5>
                <a href="<?= base_url('admin/discos'); ?>" class="btn btn-light">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <a href="<?= base_url('admin/usuarios'); ?>" class="btn btn-light">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Recibos</h5>
                <a href="<?= base_url('admin/recibos'); ?>" class="btn btn-light">Ver</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
