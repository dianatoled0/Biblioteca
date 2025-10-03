<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Melofy</title>
    
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
        }

        /* --- ESTILOS DE LA BARRA LATERAL --- */
        .sidebar {
            width: 260px;
            background-color: #201D2E;
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid #2C2A3B;
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

        .sidebar-nav .icon, .sidebar-footer .icon {
            width: 24px;
            height: 24px;
            stroke-width: 2;
            fill: none;
            stroke: currentColor;
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
            overflow-y: auto;
        }
        
        .main-content {
            display: block;
        }

        .main-header h2 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 32px;
        }

        /* --- ESTILOS DE COMPONENTES (TARJETAS, FORMULARIOS, TABLAS) --- */
        
        /* Contenedores y Grids */
        .dashboard-grid {
            display: grid;
            /* ¡CAMBIO APLICADO AQUÍ! Ahora es 1 columna que ocupa todo el ancho */
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

        .btn-primary {
            background-color: #6D28D9;
            color: #FFFFFF;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-primary:hover { background-color: #5b21b6; }
        
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
    </head>
<body>
    
    <?php 
    // LÓGICA DE ACTIVACIÓN DEL MENÚ
    // ----------------------------------------------------
    $uri = service('uri');
    // El segmento 2 es el controlador, ejemplo: /admin/categorias -> 'categorias'
    $segmento_actual = $uri->getSegment(2); 

    /**
     * Devuelve la clase 'active' si el segmento actual coincide con el nombre del enlace.
     * @param string $segment_name Nombre del enlace (ej: 'categorias').
     * @return string
     */
    function is_active(string $segment_name, ?string $current_segment): string 
    {
        // Caso especial para el Dashboard: si el segmento es vacío o 'admin' (ruta base)
        if ($segment_name === 'admin') {
             // getSegment(2) en /admin/ puede ser vacío o 'admin' dependiendo de la config.
             if (empty($current_segment)) { 
                 return 'active';
             }
        }
        // Caso general para los demás controladores
        return ($segment_name === $current_segment) ? 'active' : '';
    }
    // ----------------------------------------------------
    ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.477 2 2 6.477 2 12V18C2 19.105 2.895 20 4 20H6C7.105 20 8 19.105 8 18V12C8 9.791 9.791 8 12 8C14.209 8 16 9.791 16 12V18C16 19.105 16.895 20 18 20H20C21.105 20 22 19.105 22 18V12C22 6.477 17.523 2 12 2Z" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h1>Melofy</h1>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?= is_active('admin', $segmento_actual) ?>">
                        <a href="<?= base_url('admin') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?= is_active('usuarios', $segmento_actual) ?>">
                        <a href="<?= base_url('admin/usuarios') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li class="<?= is_active('categorias', $segmento_actual) ?>">
                        <a href="<?= base_url('admin/categorias') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                            <span>Categorías</span>
                        </a>
                    </li>
                    <li class="<?= is_active('discos', $segmento_actual) ?>">
                        <a href="<?= base_url('admin/discos') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            <span>Discos</span>
                        </a>
                    </li>
                    <li class="<?= is_active('ingresos', $segmento_actual) ?>">
                        <a href="<?= base_url('admin/ingresos') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            <span>Ingresos / Stock</span>
                        </a>
                    </li>
                    <li class="<?= is_active('recibos', $segmento_actual) ?>">
                        <a href="<?= base_url('admin/recibos') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                            <span>Recibos</span>
                        </a>
                    </li>
                    <li class="<?= is_active('membresias', $segmento_actual) ?>">
                        <a href="<?= base_url('admin/membresias') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Membresías</span>
                        </a>
                    </li>
                    <li class="<?= is_active('pagos', $segmento_actual) ?>">
                        <a href="<?= base_url('admin/pagos') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>Tipos de Pago</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('login/logout') ?>"> <svg class="icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </aside>

        <div class="main-content-wrapper">
            <?= $this->renderSection('contenido') ?> 
        </div>
    </div>

    </body>
</html> 
