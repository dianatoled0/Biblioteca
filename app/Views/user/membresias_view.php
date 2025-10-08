<?= $this->extend('layoutuser/layout_user') ?>

<?= $this->section('content') ?>

<header class="main-header">
    <h2>Tipos de Membresía</h2>
    <p style="color: #A0AEC0; margin-bottom: 30px;">
        Tu membresía actual es: 
        <strong id="current-membership-name" style="color: <?= ($usuario['id_membresia'] ? '#6D28D9' : '#F59E0B') ?>;"><?= esc($usuario['nombre_membresia'] ?? 'Básica (Sin Membresía)') ?></strong>
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
                Acceso a funciones exclusivas y descuentos.
            </p>

            <?php if (!$is_current): ?>
                <button class="btn-primary btn-comprar" data-membresia-id="<?= esc($membresia['id']) ?>">
                    Comprar Membresía
                </button>
            <?php else: ?>
                <button class="btn-primary btn-detalle-actual" data-membresia-id="<?= esc($membresia['id']) ?>" style="background-color: #10B981;">
                    Membresía Activa
                </button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<div id="membershipModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.8); backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #1A202C; margin: 15% auto; padding: 30px; border: 1px solid #4A5568; width: 80%; max-width: 500px; border-radius: 8px; color: #E2E8F0; overflow-y: hidden;">
            <span class="close-btn" style="color: #A0AEC0; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 id="modalTitle" style="color: #6D28D9; border-bottom: 2px solid #2D3748; padding-bottom: 10px;">Detalle de Membresía</h2>
            
            <div id="modalContent" style="margin-top: 20px;">
                <p><strong>Precio:</strong> <span id="modalPrice"></span></p>
                <p><strong>Duración:</strong> <span id="modalDuration"></span> mes(es)</p>
                <h4 style="margin-top: 15px; color: #E2E8F0;">Beneficios:</h4>
                <div id="modalBenefits"></div>
            </div>

            <button id="confirmPurchaseBtn" class="btn-primary" style="margin-top: 30px; display: none;">
                Confirmar Compra por <span id="confirmPrice"></span>
            </button>
            <p id="currentMembershipMessage" style="margin-top: 30px; color: #10B981; display: none;">
                ¡Esta es tu membresía actual! Disfruta de todos tus beneficios.
            </p>
            <p id="purchaseMessage" style="margin-top: 15px; color: #F59E0B; font-size: 14px;"></p>
        </div>
    </div>
</div>

<script>
    // Pasar los beneficios de PHP a JavaScript
    const beneficiosData = <?= json_encode($beneficios) ?>;
    const currentMembresiaId = <?= esc($usuario['id_membresia']) ?>;
    const modal = document.getElementById('membershipModal');
    const closeBtn = document.querySelector('.close-btn');
    const confirmPurchaseBtn = document.getElementById('confirmPurchaseBtn');
    const currentMembershipMessage = document.getElementById('currentMembershipMessage');
    const purchaseMessage = document.getElementById('purchaseMessage');

    // Función mejorada para bloquear el scroll de la página (soluciona la barra principal)
    function blockPageScroll() {
        document.documentElement.style.overflow = 'hidden'; // Bloquea en el elemento raíz <html>
        document.body.style.overflow = 'hidden'; // Bloquea en el <body>
    }

    // Función para restaurar el scroll de la página
    function unblockPageScroll() {
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
    }

    // Manejar el cierre del modal
    closeBtn.onclick = function() {
        modal.style.display = 'none';
        purchaseMessage.textContent = ''; // Limpiar mensaje
        unblockPageScroll(); // Restaurar el scroll
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            purchaseMessage.textContent = ''; // Limpiar mensaje
            unblockPageScroll(); // Restaurar el scroll
        }
    }
    
    let selectedMembresiaId = null;

    // 1. Mostrar detalles de la membresía (al hacer clic en cualquier tarjeta)
    document.querySelectorAll('.membership-card button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); 
            
            selectedMembresiaId = this.getAttribute('data-membresia-id');
            const data = beneficiosData[selectedMembresiaId];
            const isCurrent = (selectedMembresiaId == currentMembresiaId);

            // Rellenar el modal
            document.getElementById('modalTitle').textContent = `Detalle: ${data.nombre}`;
            document.getElementById('modalPrice').textContent = `Q ${data.precio}`;
            document.getElementById('modalDuration').textContent = data.duracion;
            document.getElementById('modalBenefits').innerHTML = data.html;
            
            // Mostrar u ocultar el botón de compra
            confirmPurchaseBtn.style.display = isCurrent ? 'none' : 'block';
            currentMembershipMessage.style.display = isCurrent ? 'block' : 'none';

            // Actualizar el precio en el botón de confirmación
            document.getElementById('confirmPrice').textContent = `Q ${data.precio}`;

            modal.style.display = 'block';
            blockPageScroll(); // Bloquear el scroll de la página principal
        });
    });

    // 2. Lógica de Compra (al hacer clic en el botón de confirmación)
    confirmPurchaseBtn.addEventListener('click', function() {
        if (!selectedMembresiaId) return;

        purchaseMessage.style.color = '#F59E0B';
        purchaseMessage.textContent = 'Procesando la compra... por favor, espera.';
        confirmPurchaseBtn.disabled = true;

        // Simular el envío del formulario de compra con AJAX
        fetch('<?= url_to('user_comprar_membresia') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                // Clave para CodeIgniter 4 con CSRF activo
                'X-Requested-With': 'XMLHttpRequest'
            },
            // Asegúrate de que los nombres de los campos coincidan con los de tu controlador
            body: 'id_membresia=' + selectedMembresiaId + '&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>'
        })
        .then(response => response.json())
        .then(data => {
            confirmPurchaseBtn.disabled = false;
            if (data.success) {
                // Éxito: Mostrar mensaje y recargar para actualizar la vista
                purchaseMessage.style.color = '#10B981';
                purchaseMessage.textContent = data.message;
                
                // Antes de recargar, desbloquear el scroll
                unblockPageScroll();

                setTimeout(() => {
                    window.location.reload(); 
                }, 2000); 

            } else {
                // Error en el pago/proceso
                purchaseMessage.style.color = '#EF4444';
                purchaseMessage.textContent = data.message;
            }
        })
        .catch(error => {
            confirmPurchaseBtn.disabled = false;
            purchaseMessage.style.color = '#EF4444';
            purchaseMessage.textContent = 'Error de conexión: No se pudo completar la compra.';
            console.error('Error:', error);
        });
    });

</script>

<style>
    .membership-grid { display: flex; gap: 20px; margin-top: 20px; }
    .membership-card {
        flex: 1; background-color: #1A202C; border: 1px solid #4A5568; padding: 30px;
        border-radius: 12px; color: #E2E8F0; text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .membership-card:hover:not(.current) {
        transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0, 0, 0, 0.5);
    }
    .membership-card.current {
        border-color: #6D28D9; box-shadow: 0 0 15px rgba(109, 40, 217, 0.5);
    }
    .membership-card h3 { color: #fff; font-size: 20px; margin-bottom: 10px; }
    .price-tag { font-size: 28px; font-weight: bold; color: #6D28D9; margin-bottom: 5px; }
    .duration { font-size: 14px; color: #A0AEC0; margin-bottom: 20px; }
    .btn-primary {
        background-color: #6D28D9; color: #fff; padding: 10px 20px; border: none;
        border-radius: 6px; cursor: pointer; font-weight: bold; transition: background-color 0.2s;
        width: 100%; margin-top: 10px;
    }
    .btn-primary:hover:not([style*="cursor: default"]) { background-color: #5521a8; }
    #modalBenefits ul { list-style-type: disc; margin-left: 20px; padding-left: 0; }
    #modalBenefits li { margin-bottom: 5px; color: #A0AEC0; }
    #modalBenefits strong { color: #E2E8F0; }
</style>

<?= $this->endSection() ?>