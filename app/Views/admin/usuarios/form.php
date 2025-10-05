<?php 
/**
 * Llama al archivo 'layout.php' que está en app/Views/admin/layout.php
 */
$this->extend('admin/layout'); 
?>

<?= $this->section('contenido'); ?> 

<?php
// Determina si estamos creando o editando
$is_edit = isset($usuario) && !empty($usuario);
$title = $is_edit ? 'Editar Usuario: ' . esc($usuario['nombre']) : 'Crear Nuevo Usuario';
$action_url = $is_edit ? base_url('admin/usuarios/editar/' . esc($usuario['id'])) : base_url('admin/usuarios/crear');
?>

<div class="card">
    <h2 style="font-size: 20px; font-weight: 600; margin: 0 0 20px 0; color: #FFFFFF;">
        <?= $title; ?>
    </h2>

    <?php 
    // Muestra los errores de validación devueltos por el controlador
    // Usa 'session()->getFlashdata('errors')' que es lo que el controlador retorna
    $errors = session()->getFlashdata('errors');
    if (!empty($errors)): 
    ?>
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <p style="font-weight: 600; margin-bottom: 5px;">Por favor, corrige los siguientes errores:</p>
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $action_url; ?>" method="post">
        <?= csrf_field(); ?>
        <?php if ($is_edit): ?>
            <input type="hidden" name="id" value="<?= esc($usuario['id']); ?>">
        <?php endif; ?>

        <div class="form-grid">
            
            <div>
                <h6 class="text-primary" style="font-size: 16px; font-weight: 600; color: #A0AEC0; margin-bottom: 8px;">Datos de Acceso</h6>
                <hr style="border: 0; border-top: 1px solid #2C2A3B; margin-bottom: 24px;">
                
                <div class="form-group">
                    <label for="usuario">Usuario <span class="text-danger">*</span></label>
                    <input type="text" name="usuario" id="usuario" required 
                            value="<?= old('usuario', $is_edit ? $usuario['usuario'] : ''); ?>">
                </div>

                <div class="form-group">
                    <label for="pass">Contraseña <?= !$is_edit ? '<span class="text-danger">*</span>' : '(Solo si desea cambiarla)'; ?></label>
                    <input type="password" name="pass" id="pass" value=""> 
                    <?php if (session()->has('errors') && isset($errors['pass'])): ?>
                        <p class="text-danger" style="margin-top: 5px; font-size: 12px; color: #dc3545;"><?= esc($errors['pass']); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="rol">Rol <span class="text-danger">*</span></label>
                    <select name="rol" id="rol" required>
                        <option value="usuario" <?= (old('rol', $is_edit ? $usuario['rol'] : '') == 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                        <option value="admin" <?= (old('rol', $is_edit ? $usuario['rol'] : '') == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>

                <h6 class="text-primary" style="font-size: 16px; font-weight: 600; color: #A0AEC0; margin-top: 24px; margin-bottom: 8px;">Información Personal</h6>
                <hr style="border: 0; border-top: 1px solid #2C2A3B; margin-bottom: 24px;">

                <div class="form-group">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" required 
                            value="<?= old('nombre', $is_edit ? $usuario['nombre'] : ''); ?>">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido <span class="text-danger">*</span></label>
                    <input type="text" name="apellido" id="apellido" required 
                            value="<?= old('apellido', $is_edit ? $usuario['apellido'] : ''); ?>">
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="correo" id="correo" required 
                            value="<?= old('correo', $is_edit ? $usuario['correo'] : ''); ?>">
                </div>
            </div>

            <div>
                <h6 class="text-primary" style="font-size: 16px; font-weight: 600; color: #A0AEC0; margin-bottom: 8px;">Datos de Membresía</h6>
                <hr style="border: 0; border-top: 1px solid #2C2A3B; margin-bottom: 24px;">

                <div class="form-group">
                    <label for="id_membresia">Membresía ID <span class="text-danger">*</span></label>
                    <input type="number" name="id_membresia" id="id_membresia" required 
                            value="<?= old('id_membresia', $is_edit ? $usuario['id_membresia'] : '1'); ?>">
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                            value="<?= old('fecha_nacimiento', $is_edit ? $usuario['fecha_nacimiento'] : ''); ?>">
                </div>

                <div class="form-group">
                    <label for="fecha_inicio_membresia">Fecha Inicio Membresía <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_inicio_membresia" id="fecha_inicio_membresia" required 
                            value="<?= old('fecha_inicio_membresia', $is_edit ? $usuario['fecha_inicio_membresia'] : date('Y-m-d')); ?>">
                </div>

                <div class="form-group">
                    <label for="fecha_fin_membresia">Fecha Fin Membresía <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_fin_membresia" id="fecha_fin_membresia" required 
                            value="<?= old('fecha_fin_membresia', $is_edit ? $usuario['fecha_fin_membresia'] : date('Y-m-d', strtotime('+4 months'))); ?>">
                            </div>
            </div>
        </div>

        <div class="form-actions" style="grid-column: 1 / -1; margin-top: 32px;">
            <button type="submit" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Guardar Usuario
            </button>
            <a href="<?= base_url('admin/usuarios'); ?>" class="btn-secondary" style="background-color: #2C2A3B; color: #FFFFFF; border: none; padding: 12px 24px; font-size: 16px; font-weight: 500; border-radius: 8px; cursor: pointer; text-decoration: none; margin-left: 10px;">Cancelar</a>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>