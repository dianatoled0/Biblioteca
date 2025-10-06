<?= $this->extend('layoutuser/layout_user') ?>

<?= $this->section('content') ?>

<header class="main-header">
    <h2>Tipos de Membresía</h2>
    <p style="color: #A0AEC0; margin-bottom: 30px;">
        Tu membresía actual es: 
        <strong style="color: <?= ($usuario['id_membresia'] ? '#6D28D9' : '#F59E0B') ?>;"><?= esc($usuario['nombre_membresia'] ?? 'Básica (Sin Membresía)') ?></strong>
    </p>
</header>

<div class="membership-grid">
    <?php foreach ($tipos_membresia as $membresia): ?>
        <?php $is_current = ($membresia['id'] == $usuario['id_membresia']); ?>
        
        <div class="membership-card <?= $is_current ? 'current' : '' ?>">
            <h3><?= esc($membresia['nombre']) ?> <?= $is_current ? '(Actual)' : '' ?></h3>
            <p class="price-tag">Q <?= number_format(esc($membresia['precio']), 2) ?></p>
            <p class="duration">Duración: <?= esc($membresia['duracion_meses']) ?> mes(es)</p>
            
            <p style="font-size: 14px; color: #E2E8F0; margin-bottom: 20px;">
                <?= esc($membresia['descripcion'] ?? 'Acceso a funciones exclusivas y descuentos.') ?>
            </p>

            <?php if (!$is_current): ?>
                <button class="btn-primary" data-membresia-id="<?= esc($membresia['id']) ?>">
                    Comprar Membresía
                </button>
            <?php else: ?>
                <button class="btn-primary" style="background-color: #10B981; cursor: default;">
                    Membresía Activa
                </button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>