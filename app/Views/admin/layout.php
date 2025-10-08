<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard - Melofy') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- ESTILOS GENERALES Y RESET --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1A1625;
            color: #FFFFFF;
            display: flex;
            min-height: 100vh;
        }
        .dashboard-container {
            display: flex;
            width: 100%;
            /* AJUSTE PARA SCROLL DE CONTENIDO */
            height: 100vh; 
        }
        /* --- ESTILOS DE LA BARRA LATERAL --- */
        .sidebar {
            width: 260px;
            background-color: #201D2E;
            padding: 24px;
            display: flex;
            flex-direction: column;
            /* AJUSTE PARA SCROLL DE CONTENIDO */
            flex-shrink: 0;
            height: 100vh; 
            overflow-y: auto; 
            position: sticky; 
            top: 0;
            /* FIN AJUSTE PARA SCROLL DE CONTENIDO */
            border-right: 1px solid #2C2A3B;
        }
        /* AÑADIDO: Estilo de la barra de desplazamiento para el sidebar si es necesario hacer scroll */
        .sidebar::-webkit-scrollbar {
            width: 8px;
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #2C2A3B; /* Color del thumb (pulgar) */
            border-radius: 4px;
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }
        .sidebar-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #FFFFFF;
        }
        .sidebar-nav {
            flex-grow: 1;
        }
        .sidebar-nav ul {
            list-style: none;
        }
        .sidebar-nav ul li a, .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 16px;
            text-decoration: none;
            color: #A0AEC0;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
        }
        .sidebar-nav ul li a:hover {
            background-color: #2C2A3B;
            color: #FFFFFF;
        }
        /* ESTILO CLAVE: Esta es la clase que aplica el morado de selección */
        .sidebar-nav ul li.active a {
            background-color: #6D28D9;
            color: #FFFFFF;
        }
        /* NOTA: Estos estilos SÍ APLICAN A TODOS LOS ÍCONOS DE LA BARRA LATERAL (NO AL LOGO DE MELOFY) */
        .sidebar-nav .icon, .sidebar-footer .icon {
            width: 24px;
            height: 24px;
            stroke-width: 2;
            fill: none;
            stroke: currentColor; /* Esto hace que el color sea gris/blanco según el estado */
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .sidebar-footer {
            margin-top: auto;
        }
        /* --- ESTILOS DEL CONTENEDOR PRINCIPAL --- */
        .main-content-wrapper {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto; /* ESTO PERMITE EL SCROLL SÓLO EN ESTA SECCIÓN */
        }
        
        /* >>>>>>>>>>> ESTILOS DE LA BARRA DE DESPLAZAMIENTO (SCROLLBAR) <<<<<<<<<<< */
        /* 1. Definir el ancho de la barra */
        .main-content-wrapper::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        /* 2. Estilo del Rastrillo (Track) */
        .main-content-wrapper::-webkit-scrollbar-track {
            background: #201D2E; /* Fondo oscuro, similar al sidebar */
        }
        /* 3. Estilo del Pulgar (Thumb) */
        .main-content-wrapper::-webkit-scrollbar-thumb {
            background-color: #6D28D9; /* Color morado de tu tema */
            border-radius: 4px; /* Bordes redondeados */
        }
        /* 4. Estilo del Pulgar al pasar el ratón */
        .main-content-wrapper::-webkit-scrollbar-thumb:hover {
            background-color: #5b21b6; /* Un morado más oscuro al pasar el ratón */
        }
        /* >>>>>>>>>>> FIN ESTILOS DE LA BARRA DE DESPLAZAMIENTO <<<<<<<<<<< */

        .main-content {
            display: block;
        }
        .main-header {
            margin-bottom: 32px; /* Espacio para el header principal de la vista */
        }
        .main-header h2 {
            font-size: 32px;
            font-weight: 600;
        }
        /* --- ESTILOS DE COMPONENTES (TARJETAS, FORMULARIOS, TABLAS) --- */
        
        /* Contenedores y Grids */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px;
        }
        
        .page-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px;
        }
        .card {
            background-color: #201D2E;
            border-radius: 12px;
            padding: 24px;
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #2C2A3B;
        }
        /* Formularios */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.form-group-full {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-size: 14px;
            color: #A0AEC0;
            margin-bottom: 8px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 16px;
            background-color: #2C2A3B;
            border: 1px solid #3e3b52;
            border-radius: 8px;
            color: #FFFFFF;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #6D28D9;
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.4);
        }
        .form-group input::placeholder { color: #718096; }
        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23A0AEC0' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        .form-actions { margin-top: 32px; }
        .btn-primary, a.btn-primary { /* Asegurar que el estilo aplica a enlaces */
            background-color: #6D28D9;
            color: #FFFFFF;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none; /* Añadido para enlaces */
            display: inline-flex; /* Añadido para mejor control */
            align-items: center; /* Añadido para mejor control */
            justify-content: center; /* Añadido para mejor control */
        }
        .btn-primary:hover, a.btn-primary:hover { background-color: #5b21b6; }
        
        /* Tablas */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 14px 18px;
        }
        thead {
            border-bottom: 2px solid #3e3b52;
        }
        th {
            font-size: 12px;
            text-transform: uppercase;
            color: #A0AEC0;
            font-weight: 600;
        }
        tbody tr {
            border-bottom: 1px solid #2C2A3B;
        }
        tbody tr:last-child {
            border-bottom: none;
        }
        td {
            font-size: 14px;
            color: #E2E8F0;
        }
        .status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-completed { background-color: rgba(16, 185, 129, 0.1); color: #10B981; }
        .status-pending { background-color: rgba(245, 158, 11, 0.1); color: #F59E0B; }
        .status-shipped { background-color: rgba(59, 130, 246, 0.1); color: #3B82F6; }
        .table-actions a {
            color: #A0AEC0;
            text-decoration: none;
            margin-right: 12px;
            transition: color 0.2s;
        }
        .table-actions a:hover { color: #FFFFFF; }
        .table-actions .icon { width: 20px; height: 20px; }
        /* Notificaciones */
        .notifications-list ul {
            list-style: none;
        }
        .notifications-list li {
            padding: 12px 0;
            border-bottom: 1px solid #2C2A3B;
            font-size: 14px;
            color: #E2E8F0;
        }
        .notifications-list li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>
    
    <?= $this->renderSection('scripts_head') ?>
</head>
<body>
    
    <?php
    // LÓGICA DE ACTIVACIÓN DEL MENÚ (IMPLEMENTACIÓN LIMPIA)
    // Se asume que la función is_active() existe en App/Helpers/view_helper.php
    $uri = service('uri');
    // Para el dashboard, verificamos que el segmento sea 'admin' y no tenga un segundo segmento (ruta raíz /admin)
    $is_dashboard_active = ($uri->getTotalSegments() === 1 && $uri->getSegment(1) === 'admin') || ($uri->getTotalSegments() === 2 && $uri->getSegment(2) === '');
    
    // Función placeholder, si no la tienes en un helper
    if (!function_exists('is_active')) {
        function is_active(string $segment): string
        {
            $uri = service('uri');
            return $uri->getSegment(2) === $segment ? 'active' : '';
        }
    }
    // ----------------------------------------------------
    ?>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 3c-3.866 0-7 3.134-7 7v2h2v-2a5 5 0 0 1 10 0v2h2v-2c0-3.866-3.134-7-7-7z"
                                fill="#8B5CF6"/>
                    <rect x="3" y="10" width="4" height="7" rx="1" ry="1" fill="#8B5CF6"/>
                    <rect x="17" y="10" width="4" height="7" rx="1" ry="1" fill="#8B5CF6"/>
                    <rect x="11" y="19" width="2" height="2" rx="0.5" ry="0.5" fill="#8B5CF6"/>
                </svg>
                <h1>Melofy</h1>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?= $is_dashboard_active ? 'active' : '' ?>">
                        <a href="<?= base_url('admin') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?= is_active('usuarios') ?>">
                        <a href="<?= base_url('admin/usuarios') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li class="<?= is_active('categorias') ?>">
                        <a href="<?= base_url('admin/categorias') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                            <span>Categorías</span>
                        </a>
                    </li>
                    <li class="<?= is_active('discos') ?>">
                        <a href="<?= base_url('admin/discos') ?>">
                            <svg class="icon" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 2a10 10 0 0 1 7.07 2.93"></path>
                                <path d="M3.05 12a10 10 0 0 1 7.07-7.07"></path>
                                <path d="M12 22a10 10 0 0 0 7.07-2.93"></path>
                                <path d="M20.95 12a10 10 0 0 0-7.07 7.07"></path>
                            </svg>
                            <span>Discos</span>
                        </a>
                    </li>
                    <li class="<?= is_active('pedidos') ?>">
                        <a href="<?= base_url('admin/pedidos') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                            <span>Pedidos</span>
                        </a>
                    </li>
                    <li class="<?= is_active('membresias') ?>">
                        <a href="<?= base_url('admin/membresias') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Membresías</span>
                        </a>
                    </li>
                    
                    <li class="<?= is_active('reportes') ?>">
                        <a href="<?= base_url('admin/reportes') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            <span>Reportes</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('logout') ?>"> <svg class="icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </aside>
        <div class="main-content-wrapper">
            
            <?= $this->renderSection('contenido') ?>
        </div>
    </div>
    
    <?= $this->renderSection('scripts_footer') ?>
</body>
</html>