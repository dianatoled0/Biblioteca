<?php
/**
 * Llama al archivo 'layout.php' que está en app/Views/admin/layout.php
 */
$this->extend('admin/layout');
?>

<?= $this->section('contenido'); ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-title" style="display: flex; justify-content: space-between; align-items: center; border-bottom: none; padding-bottom: 0;">
        <h2 style="font-size: 20px; font-weight: 600; margin: 0; color: #FFFFFF;">Gestión de Usuarios</h2>
        <a href="<?= base_url('admin/usuarios/crear'); ?>" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; padding: 8px 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Crear Nuevo Usuario
        </a>
    </div>

    <div style="border-bottom: 1px solid #2C2A3B; margin-top: 20px; margin-bottom: 20px;"></div>

    <div class="row mb-4" style="margin-bottom: 15px; padding: 0 10px;">
        <div class="col-md-3">
            <form action="<?= base_url('admin/usuarios'); ?>" method="get" class="form-inline" style="display: flex; align-items: center;">
                <label for="filtro_rol" style="color: #bbb; margin-right: 10px; font-size: 14px;">Filtrar por Rol:</label>
                <select 
                    name="filtro_rol" 
                    id="filtro_rol" 
                    class="form-control" 
                    onchange="this.form.submit()"
                    style="
                        padding: 8px 12px;
                        border-radius: 6px;
                        border: 1px solid #444; 
                        background-color: #333; 
                        color: #FFFFFF; 
                        font-size: 14px;
                        cursor: pointer;
                        
                        /* Estilos clave para mostrar una flecha personalizada compatible: */
                        -webkit-appearance: none; 
                        -moz-appearance: none; 
                        appearance: none; 
                        
                        /* Se usa una flecha SVG codificada en base64 para garantizar la visibilidad */
                        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNGRkZGRkYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0iaWNvbiI+PHBvbHlsaW5lIHBvaW50cz0iNiA5IDEyIDE1IDE4IDkiPjwvcG9seWxpbmU+PC9zdmc+');
                        
                        background-repeat: no-repeat;
                        background-position: right 8px center;
                        padding-right: 30px; /* Espacio para la flecha */
                    "
                >
                    <option value="" style="background-color: #333; color: #FFFFFF;">Todos los Roles</option>
                    <option value="admin" style="background-color: #333; color: #FFFFFF;" <?= (isset($filtroRol) && $filtroRol === 'admin') ? 'selected' : '' ?>>Administrador</option>
                    <option value="usuario" style="background-color: #333; color: #FFFFFF;" <?= (isset($filtroRol) && $filtroRol === 'usuario') ? 'selected' : '' ?>>Usuario</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>USUARIO</th>
                        <th>NOMBRE COMPLETO</th>
                        <th>CORREO</th>
                        <th>ROL</th>
                        <th>MEMBRESÍA</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): // Determinamos el estado para el estilo de ROL
                            $status_class = (isset($usuario['rol']) && $usuario['rol'] == 'admin') ? 'completed' : 'pending';
                        ?>
                            <tr>
                                <td><?= esc($usuario['id']); ?></td>
                                <td><?= esc($usuario['usuario']); ?></td>
                                <td><?= esc($usuario['nombre']) . ' ' . esc($usuario['apellido']); ?></td>
                                <td><?= esc($usuario['correo']); ?></td>
                                <td><span class="status status-<?= $status_class; ?>"><?= ucfirst(esc($usuario['rol'])); ?></span></td>
                                <td><?= esc($usuario['nombre_membresia']); ?></td>
                                <td class="table-actions">
                                    
                                    <a href="<?= base_url('admin/usuarios/editar/' . esc($usuario['id'])); ?>" title="Editar">
                                        <svg class="icon" viewBox="0 0 24 24" stroke="white" fill="white"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                    </a>
                                    
                                    <form action="<?= base_url('admin/usuarios/eliminar/' . esc($usuario['id'])); ?>" method="post" class="d-inline" onsubmit="return confirm('¿Está seguro de que desea eliminar este usuario?');" style="display: inline;">
                                        <?= csrf_field(); ?>
                                        <button type="submit" style="background: none; border: none; padding: 0; color: inherit; cursor: pointer;" title="Eliminar">
                                            <svg class="icon" viewBox="0 0 24 24" stroke="white" fill="white"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>