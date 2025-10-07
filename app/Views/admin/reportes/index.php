<?= $this->extend('admin/layout') ?> 

<?= $this->section('contenido') ?>

<style>
    /* === SECCIÓN DE REPORTES === */
    .report-container {
        background-color: #1B1B2F; /* igual al fondo de las tarjetas del dashboard */
        border-radius: 16px;
        padding: 35px;
        margin-top: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        font-family: 'Poppins', sans-serif; /* igual que el dashboard */
    }

    .report-container h3 {
        color: #EDE9FE; /* tono lavanda usado en títulos principales */
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 25px;
        border-bottom: 1px solid #2E2E48;
        padding-bottom: 12px;
        letter-spacing: 0.5px;
    }

    .report-container p {
        color: #A1A1B5; /* texto gris suave */
        font-size: 0.95rem;
        margin-bottom: 30px;
    }

    /* === GRID DE BOTONES === */
    .report-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .report-buttons a {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #292945;
        color: #EDE9FE;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        padding: 22px;
        border-radius: 14px;
        transition: all 0.2s ease;
        border: 1px solid #3C3C5E;
    }

    .report-buttons a svg {
        margin-right: 10px;
        stroke: #C4B5FD;
    }

    .report-buttons a:hover {
        background-color: #4C1D95;
        color: #FFFFFF;
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(76, 29, 149, 0.3);
        border-color: #6D28D9;
    }

    /* Colores distintivos */
    .btn-usuarios:hover svg { stroke: #93C5FD; }
    .btn-discos:hover svg { stroke: #6EE7B7; }
    .btn-pedidos:hover svg { stroke: #C084FC; }
    .btn-categorias:hover svg { stroke: #FCA5A5; }

    /* Responsivo */
    @media (max-width: 768px) {
        .report-buttons {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="report-container">
    <h3>Reportes Disponibles</h3>
    <p>Selecciona el tipo de informe que deseas generar. El reporte se abrirá en una nueva pestaña para visualizar, imprimir o descargar.</p>

    <div class="report-buttons">
        <a href="<?= base_url('admin/reporte/usuarios') ?>" target="_blank" class="btn-usuarios">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="8.5" cy="7" r="4" />
                <line x1="20" y1="8" x2="20" y2="14" />
                <line x1="23" y1="11" x2="17" y2="11" />
            </svg>
            Reporte de Usuarios
        </a>

        <a href="<?= base_url('admin/reporte/discos') ?>" target="_blank" class="btn-discos">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            Reporte de Discos
        </a>

        <a href="<?= base_url('admin/reporte/pedidos') ?>" target="_blank" class="btn-pedidos">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
            </svg>
            Reporte de Pedidos
        </a>

        <a href="<?= base_url('admin/reporte/categorias') ?>" target="_blank" class="btn-categorias">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
            Reporte de Categorías
        </a>
    </div>
</div>

<?= $this->endSection() ?>


