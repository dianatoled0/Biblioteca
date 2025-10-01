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
        
        /* Lógica para mostrar/ocultar vistas */
        .main-content {
            display: none;
        }
        .main-content.active {
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
            grid-template-columns: 2fr 1fr;
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
                    <li class="active"><a data-view="home">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Home</span>
                    </a></li>
                    <li><a data-view="nuevo-usuario">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        <span>Nuevo Usuario</span>
                    </a></li>
                    <li><a data-view="nueva-categoria">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                        <span>Nueva Categoría</span>
                    </a></li>
                    <li><a data-view="nuevo-producto">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        <span>Nuevo Producto</span>
                    </a></li>
                    <li><a data-view="stock">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Stock</span>
                    </a></li>
                    <li><a data-view="ordenes">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Órdenes</span>
                    </a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('login/salir') ?>">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </aside>

        <div class="main-content-wrapper">

            <main class="main-content active" id="view-home">
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
                    <div class="card">
                        <h3 class="card-title">Notificaciones</h3>
                        <div class="notifications-list">
                            <ul>
                                <li>Nuevo usuario registrado.</li>
                                <li>Producto "Melofy Pro" con bajo stock.</li>
                                <li>Orden #102 ha sido actualizada.</li>
                                <li>Ganancia del mes: Q 500.00.</li>
                                <li>Nuevo producto añadido.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>

            <main class="main-content" id="view-nuevo-usuario">
                 <header class="main-header">
                    <h2>Nuevo Usuario</h2>
                </header>
                <div class="card">
                     <form action="#" method="POST">
                        <h3 class="card-title">Datos generales</h3>
                        <div class="form-grid">
                            <div class="form-group"><label for="nombre">Nombre:</label><input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre"></div>
                            <div class="form-group"><label for="apellido">Apellido:</label><input type="text" id="apellido" name="apellido" placeholder="Ingresa el apellido"></div>
                            <div class="form-group"><label for="fecha_nacimiento">Fecha de Nacimiento:</label><input type="text" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd / mm / aaaa"></div>
                            <div class="form-group"><label for="dpi">DPI:</label><input type="text" id="dpi" name="dpi" placeholder="0000 00000 0000"></div>
                            <div class="form-group"><label for="telefono">Teléfono:</label><input type="tel" id="telefono" name="telefono" placeholder="Ingresa el teléfono"></div>
                            <div class="form-group"><label for="correo">Correo:</label><input type="email" id="correo" name="correo" placeholder="Ingresa el correo"></div>
                            <div class="form-group form-group-full"><label for="tipo_usuario">Tipo de usuario</label><select id="tipo_usuario" name="tipo_usuario"><option value="administrador">Administrador</option><option value="vendedor">Vendedor</option><option value="cliente">Cliente</option></select></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Guardar</button></div>
                    </form>
                </div>
            </main>
            
            <main class="main-content" id="view-nueva-categoria">
                <header class="main-header">
                    <h2>Categorías</h2>
                </header>
                <div class="page-grid">
                    <div class="card">
                        <h3 class="card-title">Agregar nueva categoría</h3>
                        <form action="#">
                            <div class="form-group">
                                <label for="cat-nombre">Nombre:</label>
                                <input type="text" id="cat-nombre" placeholder="Nombre de la categoría">
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <h3 class="card-title">Lista de Categorías</h3>
                        <div class="table-container">
                             <table>
                                <thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead>
                                <tbody>
                                    <tr><td>1</td><td>Rock</td><td class="table-actions">
                                        <a href="#"><svg class="icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                        <a href="#"><svg class="icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                    </td></tr>
                                    <tr><td>2</td><td>Pop</td><td class="table-actions">
                                        <a href="#"><svg class="icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                        <a href="#"><svg class="icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                    </td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            
             <main class="main-content" id="view-nuevo-producto">
                 <header class="main-header">
                    <h2>Nuevo Producto</h2>
                </header>
                <div class="card">
                     <form action="#" method="POST">
                        <h3 class="card-title">Datos del producto</h3>
                        <div class="form-grid">
                            <div class="form-group"><label for="prod-nombre">Nombre:</label><input type="text" id="prod-nombre" placeholder="Ingresa el nombre del producto"></div>
                            <div class="form-group"><label for="prod-precio-venta">Precio de Venta:</label><input type="number" id="prod-precio-venta" placeholder="0.00"></div>
                            <div class="form-group"><label for="prod-ganancia">Ganancia estimada en %:</label><input type="number" id="prod-ganancia" placeholder="0"></div>
                            <div class="form-group"><label for="prod-precio-compra">Precio de Compra:</label><input type="number" id="prod-precio-compra" placeholder="0.00"></div>
                            <div class="form-group"><label for="prod-categoria">Categoría</label><select id="prod-categoria"><option>Selecciona una categoría</option><option>Rock</option><option>Pop</option></select></div>
                            <div class="form-group"><label for="prod-codigo-barras">Código de barras:</label><input type="text" id="prod-codigo-barras" placeholder="Ingresa el código"></div>
                            <div class="form-group form-group-full"><label for="prod-stock">Stock Inicial:</label><input type="number" id="prod-stock" placeholder="0"></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Guardar</button></div>
                    </form>
                </div>
            </main>
            
            <main class="main-content" id="view-stock">
                <header class="main-header">
                    <h2>Stock de Productos</h2>
                </header>
                <div class="card">
                    <div class="table-container">
                         <table>
                            <thead><tr><th>ID Disco</th><th>Título</th><th>Artista</th><th>Categoría</th><th>Stock</th><th>Precio Venta</th></tr></thead>
                            <tbody>
                                <tr><td>1</td><td>Thriller</td><td>Michael Jackson</td><td>Pop</td><td>150</td><td>Q 150.00</td></tr>
                                <tr><td>2</td><td>Back in Black</td><td>AC/DC</td><td>Rock</td><td>75</td><td>Q 125.00</td></tr>
                                <tr><td>3</td><td>Hybrid Theory</td><td>Linkin Park</td><td>Rock</td><td>200</td><td>Q 175.00</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
            
            <main class="main-content" id="view-ordenes">
                <header class="main-header">
                    <h2>Órdenes</h2>
                </header>
                 <div class="card">
                    <div class="table-container">
                         <table>
                            <thead><tr><th>ID Recibo</th><th>Usuario</th><th>Fecha</th><th>Total</th><th>Tipo de Pago</th><th>Acciones</th></tr></thead>
                            <tbody>
                                <tr><td>#101</td><td>Elder Gonzalez</td><td>12-08-2024</td><td>Q 150.00</td><td>Tarjeta de Crédito</td><td class="table-actions">
                                    <a href="#"><svg class="icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                    <a href="#"><svg class="icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                </td></tr>
                                <tr><td>#102</td><td>Maria Perez</td><td>11-08-2024</td><td>Q 75.00</td><td>Efectivo</td><td class="table-actions">
                                    <a href="#"><svg class="icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                    <a href="#"><svg class="icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                </td></tr>
                                <tr><td>#103</td><td>Juan Lopez</td><td>11-08-2024</td><td>Q 300.00</td><td>Transferencia</td><td class="table-actions">
                                    <a href="#"><svg class="icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                    <a href="#"><svg class="icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a[data-view]');
            const views = document.querySelectorAll('.main-content');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    
                    const targetViewId = 'view-' + this.getAttribute('data-view');
                    
                    // Actualizar el estado activo en la navegación
                    document.querySelector('.sidebar-nav li.active').classList.remove('active');
                    this.parentElement.classList.add('active');
                    
                    // Mostrar la vista seleccionada y ocultar las demás
                    views.forEach(view => {
                        if (view.id === targetViewId) {
                            view.classList.add('active');
                        } else {
                            view.classList.remove('active');
                        }
                    });
                });
            });
        });
    </script>
    </body>
</html>
