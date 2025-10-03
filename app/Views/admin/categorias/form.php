// CÓDIGO COMPLETO PARA PEGAR EN app/Views/admin/categorias/form.php

<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<header class="main-header">
    <h2><?= isset($categoria) ? 'Editar Categoría: ' . esc($categoria['nom_categoria']) : 'Crear Nueva Categoría' ?></h2>
</header>

<div class="page-grid">
    <div class="card">
        <h3 class="card-title"><?= isset($categoria) ? 'Actualizar Información' : 'Llenar los datos' ?></h3>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background-color: #1a4f38; color: #10B981; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php $validation = \Config\Services::validation(); ?>
        <?php if ($validation->getErrors()): ?>
            <div style="background-color: #5c1a1a; color: #F87171; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <ul>
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= form_open(isset($categoria) ? 'admin/categorias/actualizar/' . $categoria['id'] : 'admin/categorias/guardar') ?>
        
            <div class="form-grid">
                <div class="form-group form-group-full">
                    <label for="nom_categoria">Nombre de la Categoría</label>
                    <input type="text" id="nom_categoria" name="nom_categoria" 
                           placeholder="Ej. Rock Clásico, Electrónica, Pop" 
                           value="<?= set_value('nom_categoria', $categoria['nom_categoria'] ?? '') ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <?= isset($categoria) ? 'Guardar Cambios' : 'Crear Categoría' ?>
                </button>
                <a href="<?= base_url('admin/categorias') ?>" style="margin-left: 15px; color: #A0AEC0; text-decoration: none;">
                    Cancelar y Volver
                </a>
            </div>

        <?= form_close() ?>

    </div>
</div>

<?= $this->endSection() ?>