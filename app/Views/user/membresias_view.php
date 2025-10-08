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
        <div class="modal-content">
            <span class="close-btn" style="color: #A0AEC0; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 id="modalTitle" style="color: #6D28D9; border-bottom: 2px solid #2D3748; padding-bottom: 10px;">Detalle de Membresía</h2>
            
            <div id="modalContent">
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

    // 1. CORRECCIÓN SCROLLBAR: Añade/Quita la clase 'modal-open' al body.
    function blockPageScroll() {
        document.body.classList.add('modal-open'); 
    }

    function unblockPageScroll() {
        document.body.classList.remove('modal-open'); 
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

    // Mostrar detalles de la membresía (al hacer clic en cualquier tarjeta)
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

    // 2. CORRECCIÓN ERROR 405/CSRF: Lógica de Compra (al hacer clic en el botón de confirmación)
    confirmPurchaseBtn.addEventListener('click', function() {
        if (!selectedMembresiaId) return;

        purchaseMessage.style.color = '#F59E0B';
        purchaseMessage.textContent = 'Procesando la compra... por favor, espera.';
        confirmPurchaseBtn.disabled = true;

        const csrfTokenName = '<?= csrf_token() ?>'; 
        const csrfTokenHash = '<?= csrf_hash() ?>'; 

        // Usamos URLSearchParams para construir el cuerpo de la solicitud POST correctamente
        const requestBody = new URLSearchParams();
        requestBody.append('id_membresia', selectedMembresiaId);
        requestBody.append(csrfTokenName, csrfTokenHash); 

        // Enviar la solicitud de compra con AJAX
        fetch('<?= url_to('user_comprar_membresia') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: requestBody.toString() // ¡El cuerpo está bien formado!
        })
        
        .then(response => {
            // Manejo de errores HTTP (incluyendo el 405)
            const clonedResponse = response.clone(); 
            if (!response.ok) {
                clonedResponse.text().then(text => console.error('Error de Servidor (no JSON):', text));
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            confirmPurchaseBtn.disabled = false;
            if (data.success) {
                // Éxito
                purchaseMessage.style.color = '#10B981';
                purchaseMessage.textContent = data.message;
                
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
            purchaseMessage.textContent = 'Error de conexión o de servidor. (Revisa la consola)';
            console.error('Error:', error);
        });
    });

</script>

<style>
    /* Estilos existentes para las tarjetas de membresía */
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


    /* >>>>>>>>>>> CORRECCIÓN SCROLLBAR: ESTILOS CLAVE DEL MODAL <<<<<<<<<<< */
    .modal-content {
        background-color: #1A202C; 
        margin: 15% auto; /* Centrado vertical */
        padding: 30px; 
        border: 1px solid #4A5568; 
        width: 80%; 
        max-width: 500px; 
        border-radius: 8px; 
        color: #E2E8F0; 
        /* Limita la altura del modal completo y previene el scroll externo */
        max-height: 85vh; 
        overflow-y: hidden; 
    }
    
    #modalContent {
        margin-top: 20px;
        /* Limita la altura de la sección de beneficios y permite el scroll interno */
        max-height: 350px; 
        overflow-y: auto; 
        padding-right: 15px; 
    }
    
    /* Estilo del scrollbar interno del modal (opcional, para mejor apariencia) */
    #modalContent::-webkit-scrollbar {
        width: 6px;
    }
    #modalContent::-webkit-scrollbar-thumb {
        background-color: #4A5568; 
        border-radius: 3px;
    }
    /* >>>>>>>>>>> FIN CORRECCIÓN SCROLLBAR <<<<<<<<<<< */
</style>

<?= $this->endSection() ?>