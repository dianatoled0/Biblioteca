<?= $this->extend('admin/layout') ?> 

<?= $this->section('contenido') ?>

<header class="main-header">
    <h2>Generación de Reportes PDF</h2>
    <p style="color:#A0AEC0;">Selecciona el tipo de informe que deseas generar. Se abrirá una nueva pestaña con el PDF para visualizar, imprimir o descargar.</p>
</header>

<div class="page-grid">
    <div class="card form-grid">
        <h3 class="card-title form-group-full">Reportes Disponibles</h3>

        <a href="<?= base_url('admin/reporte/usuarios') ?>" target="_blank" class="btn-primary" style="background-color: #3B82F6; text-align: center;">
            <svg style="margin-right: 8px;" class="icon" width="20" height="20" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle><line x1="20" y1="8" x2="20" y2="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></line><line x1="23" y1="11" x2="17" y2="11" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></line></svg>
            Reporte de Usuarios
        </a>

        <a href="<?= base_url('admin/reporte/membresias') ?>" target="_blank" class="btn-primary" style="background-color: #F59E0B; text-align: center;">
            <svg style="margin-right: 8px;" class="icon" width="20" height="20" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Reporte de Membresías
        </a>

        <a href="<?= base_url('admin/reporte/discos') ?>" target="_blank" class="btn-primary" style="background-color: #10B981; text-align: center;">
            <svg style="margin-right: 8px;" class="icon" width="20" height="20" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle><path d="M12 2a10 10 0 0 1 7.07 2.93" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.05 12a10 10 0 0 1 7.07-7.07" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 22a10 10 0 0 0 7.07-2.93" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><path d="M20.95 12a10 10 0 0 0-7.07 7.07" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Reporte de Discos (Stock)
        </a>

        <a href="<?= base_url('admin/reporte/pedidos') ?>" target="_blank" class="btn-primary" style="background-color: #6D28D9; text-align: center;">
            <svg style="margin-right: 8px;" class="icon" width="20" height="20" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle><circle cx="20" cy="21" r="1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Reporte de Pedidos
        </a>
        
        <a href="<?= base_url('admin/reporte/categorias') ?>" target="_blank" class="btn-primary" style="background-color: #EF4444; text-align: center;">
            <svg style="margin-right: 8px;" class="icon" width="20" height="20" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Reporte de Categorías
        </a>
    </div>
</div>

<?= $this->endSection() ?>