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

// CORRECCIÓN: Define la URL de acción basada en las nuevas rutas:
$action_url = $is_edit ? base_url('admin/usuarios/actualizar/' . esc($usuario['id'])) : base_url('admin/usuarios/guardar');

// Define los valores predeterminados para los campos
$usuario_val = old('usuario', $is_edit ? $usuario['usuario'] : '');
$rol_val = old('rol', $is_edit ? $usuario['rol'] : 'usuario');
$nombre_val = old('nombre', $is_edit ? $usuario['nombre'] : '');
$apellido_val = old('apellido', $is_edit ? $usuario['apellido'] : '');
$correo_val = old('correo', $is_edit ? $usuario['correo'] : '');
$membresias = $membresias ?? [];
$id_membresia_val = old('id_membresia', $is_edit ? $usuario['id_membresia'] : '');
$fecha_nacimiento_val = old('fecha_nacimiento', $is_edit ? $usuario['fecha_nacimiento'] : '');
$fecha_inicio_val = old('fecha_inicio_membresia', $is_edit ? $usuario['fecha_inicio_membresia'] : date('Y-m-d'));
$fecha_fin_val = old('fecha_fin_membresia', $is_edit ? $usuario['fecha_fin_membresia'] : '');
// NUEVO: Para pass y pass_confirm (usa old() para mantener valores en errores)
$pass_val = old('pass', ''); // Para edición, vacío por seguridad
$pass_confirm_val = old('pass_confirm', ''); // Para mantener en errores
?>

<div class="card">
    <h2 style="font-size: 20px; font-weight: 600; margin: 0 0 20px 0; color: #FFFFFF;">
        <?= $title; ?>
    </h2>

    <?php
    // Muestra los errores de validación
    $errors = session()->getFlashdata('errors');
    $system_error = session()->getFlashdata('error');
    if (!empty($errors)):
    ?>
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <p style="font-weight: 600; margin-bottom: 5px;">⚠️ Por favor, corrige los siguientes errores:</p>
            <ul>
                <?php foreach ($errors as $field => $error): ?>
                    <li>**<?= esc(ucfirst(str_replace('_', ' ', $field))); ?>**: <?= esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (!empty($system_error)): ?>
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <p style="font-weight: 600; margin-bottom: 5px;">❌ Error del Sistema:</p>
            <p><?= esc($system_error); ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= $action_url; ?>" method="post">
        <?= csrf_field(); ?>
        <?php if ($is_edit): ?>
            <input type="hidden" name="_method" value="post">
        <?php endif; ?>

        <div class="form-grid">
            <div>
                <h6 class="text-primary" style="font-size: 16px; font-weight: 600; color: #A0AEC0; margin-bottom: 8px;">Datos de Acceso</h6>
                <hr style="border: 0; border-top: 1px solid #2C2A3B; margin-bottom: 24px;">

                <div class="form-group">
                    <label for="usuario">Usuario <span class="text-danger">*</span></label>
                    <input type="text" name="usuario" id="usuario" required value="<?= esc($usuario_val); ?>">
                </div>

                <div class="form-group">
                    <label for="pass">Contraseña <?= !$is_edit ? '<span class="text-danger">*</span>' : '(Solo si desea cambiarla)'; ?></label>
                    <input type="password" name="pass" id="pass" value="<?= esc($pass_val); ?>" <?= !$is_edit ? 'required' : ''; ?>>
                    <?php if (!$is_edit && session()->has('errors') && isset($errors['pass'])): ?>
                        <p class="text-danger" style="margin-top: 5px; font-size: 12px; color: #dc3545;">La contraseña es requerida.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="pass_confirm">Confirmar Contraseña <?= !$is_edit ? '<span class="text-danger">*</span>' : ''; ?></label>
                    <input type="password" name="pass_confirm" id="pass_confirm" value="<?= esc($pass_confirm_val); ?>" <?= !$is_edit ? 'required' : ''; ?>>
                </div>

                <div class="form-group">
                    <label for="rol">Rol <span class="text-danger">*</span></label>
                    <select name="rol" id="rol" required>
                        <option value="usuario" <?= ($rol_val == 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                        <option value="admin" <?= ($rol_val == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>

                <h6 class="text-primary" style="font-size: 16px; font-weight: 600; color: #A0AEC0; margin-top: 24px; margin-bottom: 8px;">Información Personal</h6>
                <hr style="border: 0; border-top: 1px solid #2C2A3B; margin-bottom: 24px;">

                <div class="form-group">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" required value="<?= esc($nombre_val); ?>">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido <span class="text-danger">*</span></label>
                    <input type="text" name="apellido" id="apellido" required value="<?= esc($apellido_val); ?>">
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="correo" id="correo" required value="<?= esc($correo_val); ?>">
                </div>
            </div>

            <div>
                <h6 class="text-primary" style="font-size: 16px; font-weight: 600; color: #A0AEC0; margin-bottom: 8px;">Datos de Membresía</h6>
                <hr style="border: 0; border-top: 1px solid #2C2A3B; margin-bottom: 24px;">

                <div class="form-group">
                    <label for="id_membresia">Membresía <span class="text-danger">*</span></label>
                    <select name="id_membresia" id="id_membresia" required>
                        <option value="" <?= (empty($id_membresia_val) || $id_membresia_val == 0) ? 'selected' : ''; ?>>-- Seleccione una --</option>
                        <?php foreach ($membresias as $membresia): ?>
                            <option value="<?= esc($membresia['id']); ?>" data-duracion="<?= esc($membresia['duracion_meses']); ?>" <?= ($membresia['id'] == $id_membresia_val) ? 'selected' : ''; ?> >
                                <?= esc($membresia['nombre']); ?> (<?= esc($membresia['duracion_meses']); ?> meses)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?= esc($fecha_nacimiento_val); ?>">
                </div>

                <div class="form-group">
                    <label for="fecha_inicio_membresia">Fecha Inicio Membresía <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_inicio_membresia" id="fecha_inicio_membresia" required value="<?= esc($fecha_inicio_val); ?>">
                </div>

                <div class="form-group">
                    <label for="fecha_fin_membresia">Fecha Fin Membresía <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_fin_membresia" id="fecha_fin_membresia" readonly required value="<?= esc($fecha_fin_val); ?>">
                </div>
            </div>
        </div>

        <div class="form-actions" style="grid-column: 1 / -1; margin-top: 32px;">
            <button type="submit" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                <?= $is_edit ? 'Guardar Cambios' : 'Crear Usuario'; ?>
            </button>
            <a href="<?= base_url('admin/usuarios'); ?>" class="btn-secondary" style="background-color: #2C2A3B; color: #FFFFFF; border: none; padding: 12px 24px; font-size: 16px; font-weight: 500; border-radius: 8px; cursor: pointer; text-decoration: none; margin-left: 10px;">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectMembresia = document.getElementById('id_membresia');
    const inputFechaInicio = document.getElementById('fecha_inicio_membresia');
    const inputFechaFin = document.getElementById('fecha_fin_membresia');

    /**
     * Calcula y establece la Fecha Fin Membresía.
     */
    function calcularFechaFin() {
        const selectedOption = selectMembresia.options[selectMembresia.selectedIndex];
        if (selectedOption.value === "") {
            inputFechaFin.value = '';
            return;
        }

        const duracionMeses = parseInt(selectedOption.getAttribute('data-duracion'));
        const fechaInicioStr = inputFechaInicio.value;

        if (fechaInicioStr && duracionMeses > 0) {
            // Crea un objeto Date con corrección de zona horaria
            let fechaFin = new Date(fechaInicioStr + 'T00:00:00');
            fechaFin.setMonth(fechaFin.getMonth() + duracionMeses);
            // Resta un día para que termine el día anterior
            fechaFin.setDate(fechaFin.getDate() - 1);

            // Formatea la fecha a YYYY-MM-DD
            const yyyy = fechaFin.getFullYear();
            const mm = String(fechaFin.getMonth() + 1).padStart(2, '0');
            const dd = String(fechaFin.getDate()).padStart(2, '0');

            inputFechaFin.value = `${yyyy}-${mm}-${dd}`;
        } else {
            inputFechaFin.value = '';
        }
    }

    // Ejecuta el cálculo cada vez que cambian la membresía o la fecha de inicio
    selectMembresia.addEventListener('change', calcularFechaFin);
    inputFechaInicio.addEventListener('change', calcularFechaFin);

    // Ejecuta el cálculo al cargar la página (importante para edición)
    setTimeout(calcularFechaFin, 100);
});
</script>

<?= $this->endSection(); ?>