<?= $this->extend('admin/layout') ?> 

<?= $this->section('contenido') ?>

<header class="main-header">
    <h2>Dashboard</h2>
</header>
<div class="dashboard-grid">
    <div class="card">
        <h3 class="card-title">Órdenes Realizadas</h3>
        <div class="table-container">
            <table>
                <thead><tr><th>ID</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead>
                <tbody>
                    <tr><td>#101</td><td>12-08-2024</td><td>Q 150.00</td><td><span class="status status-completed">Completada</span></td></tr>
                    <tr><td>#102</td><td>12-08-2024</td><td>Q 75.00</td><td><span class="status status-pending">Pendiente</span></td></tr>
                    <tr><td>#103</td><td>11-08-2024</td><td>Q 300.00</td><td><span class="status status-completed">Completada</span></td></tr>
                    <tr><td>#104</td><td>11-08-2024</td><td>Q 50.00</td><td><span class="status status-shipped">Enviada</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    
    </div>

<?= $this->endSection() ?>
