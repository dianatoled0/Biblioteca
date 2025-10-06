<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<header class="main-header">
    <!-- $disco->titulo ahora siempre funcionará para editar -->
    <h2><?= isset($disco) && isset($disco->id) ? 'Editar Disco: ' . esc($disco->titulo ?? '') : 'Crear Nuevo Disco' ?></h2>
</header>

<div class="page-grid">
    <div class="card">
        <h3 class="card-title"><?= isset($disco) && isset($disco->id) ? 'Actualizar Información' : 'Llenar los datos' ?></h3>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background-color: #1a4f38; color: #10B981; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php $validation = \Config\Services::validation(); ?>
        <?php if (isset($validation) && $validation->getErrors()): ?>
            <div style="background-color: #5c1a1a; color: #F87171; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <ul>
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- La URL ahora se construye correctamente si $disco tiene un ID válido -->
        <?= form_open(isset($disco->id) ? 'admin/discos/actualizar/' . $disco->id : 'admin/discos/guardar') ?>
        
            <!-- **CAMPO OCULTO DE ID:** Aunque el ID va en la URL, se añade este campo por seguridad. -->
            <?php if (isset($disco->id)): ?>
                <input type="hidden" name="id" value="<?= esc($disco->id) ?>">
            <?php endif; ?>
        
            <div class="form-grid">
                <div class="form-group">
                    <label for="titulo">Título del Disco</label>
                    <input type="text" id="titulo" name="titulo" 
                            placeholder="Ej. Thriller" 
                            value="<?= set_value('titulo', $disco->titulo ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="artista">Artista</label>
                    <input type="text" id="artista" name="artista" 
                            placeholder="Ej. Michael Jackson" 
                            value="<?= set_value('artista', $disco->artista ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="precio">Precio (Q)</label>
                    <input type="number" step="0.01" id="precio" name="precio" 
                            placeholder="Ej. 150.00" 
                            value="<?= set_value('precio', $disco->precio_venta ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" 
                            placeholder="Ej. 50" 
                            value="<?= set_value('stock', $disco->stock ?? '') ?>">
                </div>
                
                <div class="form-group form-group-full">
                    <label for="categoria_id">Categoría</label>
                    <select id="categoria_id" name="categoria_id">
                        <option value="">Seleccione una categoría</option>
                        <?php 
                        // Utilizamos set_value para mantener el valor en caso de error de validación,
                        // o el valor original ($disco->id_categoria) si es la carga inicial de edición.
                        $selected_cat = set_value('categoria_id', $disco->id_categoria ?? '');
                        foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ((string)$cat['id'] === (string)$selected_cat) ? 'selected' : '' ?>>
                                <?= esc($cat['nom_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <?= isset($disco->id) ? 'Guardar Cambios' : 'Crear Disco' ?>
                </button>
                <a href="<?= base_url('admin/discos') ?>" style="margin-left: 15px; color: #A0AEC0; text-decoration: none;">
                    Cancelar y Volver
                </a>
            </div>

        <?= form_close() ?>

    </div>
</div>

<?= $this->endSection() ?>