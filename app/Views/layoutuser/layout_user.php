<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Melofy</title>
    
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
            height: 100vh; 
        }
        /* --- ESTILOS DE LA BARRA LATERAL --- */
        .sidebar {
            width: 260px;
            background-color: #201D2E;
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            height: 100vh; 
            overflow-y: auto; 
            position: sticky; 
            top: 0;
            border-right: 1px solid #2C2A3B;
        }
        /* AÑADIDO: Estilo de la barra de desplazamiento para el sidebar si es necesario hacer scroll */
        .sidebar::-webkit-scrollbar {
            width: 8px;
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #2C2A3B; 
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
        
        /* >>>>>>>>>>> ESTILOS DE LA BARRA DE DESPLAZAMIENTO (SCROLLBAR) <<<<<<<<<<< */
        .main-content-wrapper::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .main-content-wrapper::-webkit-scrollbar-track {
            background: #201D2E; 
        }
        .main-content-wrapper::-webkit-scrollbar-thumb {
            background-color: #6D28D9; 
            border-radius: 4px; 
        }
        .main-content-wrapper::-webkit-scrollbar-thumb:hover {
            background-color: #5b21b6; 
        }
        /* >>>>>>>>>>> FIN ESTILOS DE LA BARRA DE DESPLAZAMIENTO <<<<<<<<<<< */

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

        /* --- ESTILOS ESPECÍFICOS DE LA TIENDA (DISCOS) --- */
        .disk-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 24px;
            margin-top: 24px;
            margin-bottom: 40px;
        }
        .disk-card {
            background-color: #2C2A3B;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .disk-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(109, 40, 217, 0.2);
        }
        .disk-card h4 {
            font-size: 18px;
            font-weight: 600;
            color: #FFFFFF;
            margin-bottom: 8px;
        }
        .disk-card p {
            font-size: 14px;
            color: #A0AEC0;
            margin-bottom: 4px;
        }
        .disk-card .price {
            font-size: 20px;
            font-weight: 700;
            color: #6D28D9;
            margin-top: 12px;
        }
        /* Estilos para el filtro de categorías (botones) */
        .category-filter {
            margin-bottom: 32px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .category-filter button {
            background-color: #2C2A3B;
            color: #A0AEC0;
            border: 1px solid #3e3b52;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s;
        }
        .category-filter button:hover {
            background-color: #3e3b52;
        }
        .category-filter button.active {
            background-color: #6D28D9;
            color: #FFFFFF;
            border-color: #6D28D9;
        }

        /* Estilos para Membresía (Cards) */
        .membership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
        }
        .membership-card {
            background-color: #2C2A3B;
            border-radius: 12px;
            padding: 30px;
            transition: box-shadow 0.3s;
        }
        .membership-card.current {
            border: 2px solid #6D28D9;
            box-shadow: 0 0 15px rgba(109, 40, 217, 0.5);
        }
        .membership-card h3 {
            font-size: 24px;
            color: #6D28D9;
            margin-bottom: 15px;
        }
        .membership-card .price-tag {
            font-size: 36px;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 10px;
        }
        .membership-card .duration {
            font-size: 16px;
            color: #A0AEC0;
            margin-bottom: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    
    <?php
    // LÓGICA DE ACTIVACIÓN DEL MENÚ (Ajustada para Usuario)
    // ----------------------------------------------------
    $uri = service('uri');
    // El segmento 2 es el controlador, ejemplo: /usuario/membresias -> 'membresias'
    $segmento_actual = $uri->getSegment(2);
    
    // --- FUNCIÓN is_active() ELIMINADA DE AQUÍ y movida al Helper ---
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
                    <li class="<?= is_active('home', $segmento_actual) ?>">
                        <a href="<?= base_url('usuario') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="<?= is_active('membresias', $segmento_actual) ?>">
                        <a href="<?= base_url('usuario/membresias') ?>">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Membresías</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('logout') ?>"> 
                    <svg class="icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </aside>
        <div class="main-content-wrapper">
            <?= $this->renderSection('content') ?> </div>
    </div>
</body>
</html>