<?= $this->extend('layoutuser/layout_user') ?>

<?= $this->section('content') ?>

<header class="main-header">
    <h2>Bienvenido, <?= esc($nombreUsuario) ?> a Melofy</h2>
    <h3>Explora Nuestros Discos</h3>
</header>

<div class="category-filter" id="categoryFilter">
    <button data-id="0" class="active">Todos</button>
    <?php foreach ($categorias as $cat): ?>
        <button data-id="<?= esc($cat['id']) ?>"><?= esc($cat['nom_categoria']) ?></button>
    <?php endforeach; ?>
</div>

<div class="disk-grid" id="allDiscosList">
    <?php if (!empty($allDiscos)): ?>
        <?php foreach ($allDiscos as $disco): ?>
            <div class="disk-card" data-id="<?= esc($disco['id']) ?>">
                <h4><?= esc($disco['titulo']) ?></h4>
                <p>Artista: <?= esc($disco['artista']) ?></p>
                <p>Categoría: <?= esc($disco['nom_categoria'] ?? 'N/A') ?></p> 
                <p class="price">Precio: Q <?= number_format(esc($disco['precio_venta']), 2) ?></p>
                
                <button class="btn-primary add-to-cart-btn" data-id="<?= esc($disco['id']) ?>" data-stock="<?= esc($disco['stock']) ?>">
                    Comprar
                </button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay discos disponibles.</p>
    <?php endif; ?>
</div>

<?= $this->include('user/_carrito_modal') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
    const BASE_URL = '<?= base_url('usuario') ?>';

    $(document).ready(function() {
        // Inicialización de filtros
        $('#categoryFilter button').on('click', function() {
            $('#categoryFilter button').removeClass('active');
            $(this).addClass('active');
            filterDiscos($(this).data('id'));
        });

        // Lógica para abrir/cerrar el modal del carrito
        $('#floating-cart-icon').on('click', function() {
            updateCarritoDisplay(); 
            $('#carritoModal').fadeIn(300);
        });
        $('#closeModalBtn').on('click', function() {
            $('#carritoModal').fadeOut(300);
        });

        // Lógica para añadir 1 disco al carrito
        $('#allDiscosList').on('click', '.add-to-cart-btn', function() {
            addToCarrito($(this).data('id'), 1); 
        });

        // Evento para VACIAR el carrito
        $('#vaciarCarritoBtn').on('click', function() {
            vaciarCarrito();
        });
        
        // Evento para FINALIZAR la compra (Checkout)
        $('#finalizarCompraBtn').on('click', function() {
            finalizarCompra();
        });
        
        // Evento para ACTUALIZAR la cantidad dentro del modal (Punto 3)
        $('#carrito-items-list').on('change', '.cart-quantity-input', function() {
            const discoId = $(this).data('id');
            const cantidad = parseInt($(this).val());
            actualizarCantidadCarrito(discoId, cantidad);
        });

        // Evento para ELIMINAR un ítem del modal
        $('#carrito-items-list').on('click', '.remove-item-btn', function() {
            const discoId = $(this).data('id');
            eliminarDelCarrito(discoId);
        });

        updateCarritoDisplay(); // Carga el contador inicial
    });
    
    // =========================================================
    // LÓGICA DE FILTRO
    // =========================================================
    function filterDiscos(categoryId) {
        $.ajax({
            url: BASE_URL + '/ajax/discos/' + categoryId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    renderDiscos(response.discos);
                }
            },
            error: function() {
                alert('Error al cargar los discos.');
            }
        });
    }

    function renderDiscos(discos) {
        let html = '';
        if (discos.length > 0) {
            discos.forEach(function(disco) {
                html += `
                    <div class="disk-card" data-id="${disco.id}">
                        <h4>${disco.titulo}</h4>
                        <p>Artista: ${disco.artista}</p>
                        <p>Categoría: ${disco.nom_categoria}</p>
                        <p class="price">Precio: Q ${parseFloat(disco.precio_venta).toFixed(2)}</p>
                        <button class="btn-primary add-to-cart-btn" data-id="${disco.id}" data-stock="${disco.stock}">
                            Comprar
                        </button>
                    </div>
                `;
            });
        } else {
            html = '<p style="grid-column: 1 / -1;">No se encontraron discos en esta categoría.</p>';
        }
        $('#allDiscosList').html(html);
    }
    
    // =========================================================
    // LÓGICA DE CARRITO Y CHECKOUT
    // =========================================================
    
    /**
     * Muestra una notificación temporal al usuario. (PUNTO 4)
     */
    function showNotification(message) {
        const notification = $('#cartNotification');
        notification.text(message).fadeIn(300);
        setTimeout(() => {
            notification.fadeOut(300);
        }, 2000);
    }

    /**
     * Agrega un disco al carrito.
     */
    function addToCarrito(discoId, cantidad) {
        $.ajax({
            url: BASE_URL + '/carrito/agregar',
            method: 'POST',
            data: { id: discoId, cantidad: cantidad },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.count);
                    showNotification(response.message); // Muestra la notificación
                } else {
                    swal("Error", response.message, "error");
                }
            }
        });
    }
    
    /**
     * Actualiza la cantidad de un disco en el carrito (dentro del modal). (Punto 3)
     */
    function actualizarCantidadCarrito(discoId, cantidad) {
        // La validación de stock y eliminación si es <= 0 se maneja en el Controller
        if (cantidad < 0) return; // Evita llamadas negativas

        $.ajax({
            url: BASE_URL + '/carrito/actualizar',
            method: 'POST',
            data: { id: discoId, cantidad: cantidad },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.count);
                    updateCarritoDisplay(); // Recarga el modal para ver los subtotales actualizados
                } else {
                    swal("Error", response.message, "error");
                    updateCarritoDisplay(); // Vuelve a cargar el carrito si hay error de stock
                }
            },
            error: function() {
                swal("Error", "Ocurrió un error al actualizar la cantidad.", "error");
            }
        });
    }
    
    /**
     * Elimina un disco del carrito.
     */
    function eliminarDelCarrito(discoId) {
        $.ajax({
            url: BASE_URL + '/carrito/eliminar',
            method: 'POST',
            data: { id: discoId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.count);
                    updateCarritoDisplay();
                } else {
                    swal("Error", response.message, "error");
                }
            }
        });
    }

    /**
     * Obtiene el carrito y actualiza la vista del modal.
     */
    function updateCarritoDisplay() {
        $.ajax({
            url: BASE_URL + '/carrito/obtener',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                updateCarritoCount(response.count);
                renderCarritoModal(response.carrito, response.total);
            }
        });
    }
    
    /**
     * Renderiza el contenido dentro del modal. (Punto 3 - Renderizado)
     */
    function renderCarritoModal(carrito, total) {
        const $list = $('#carrito-items-list');
        $list.empty();
        $('#carrito-total-monto').text(total);

        const isEmpty = Object.keys(carrito).length === 0;
        
        // Muestra u oculta elementos según si hay ítems
        $('#carrito-vacio-msg').toggle(isEmpty);
        $('#carrito-content-header').toggle(!isEmpty);
        $('#finalizarCompraBtn').prop('disabled', isEmpty);

        if (isEmpty) return;

        let html = '';
        for (const id in carrito) {
            const item = carrito[id];
            // Importante: toFixed(2) debe usarse en JS, no en el controlador
            const subtotal = (item.precio_venta * item.cantidad).toFixed(2); 
            html += `
                <div class="carrito-item-grid">
                    <div class="carrito-item-info"><strong>${item.titulo}</strong></div>
                    <div style="text-align: right;">Q ${item.precio_venta.toFixed(2)}</div>
                    <div style="text-align: center;">
                        <input type="number" 
                                class="cart-quantity-input" 
                                value="${item.cantidad}" 
                                min="1" 
                                data-id="${item.id}" 
                                style="width: 60px; text-align: center;">
                    </div>
                    <div style="text-align: center;">
                        <button class="btn btn-sm btn-danger remove-item-btn" data-id="${item.id}" style="padding: 2px 8px;">X</button>
                    </div>
                </div>
            `;
        }
        $list.html(html);
    }
    
    /**
     * Actualiza el contador del carrito en el ícono.
     */
    function updateCarritoCount(count) {
        $('.cart-count').text(count);
    }
    
    /**
     * Llama al endpoint de Checkout.
     */
    function finalizarCompra() {
        $.ajax({
            url: BASE_URL + '/carrito/checkout',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    swal("¡Éxito!", "Tu compra se realizó con éxito.", "success");
                    updateCarritoCount(response.count);
                    updateCarritoDisplay();
                } else {
                    swal("Error al comprar", response.message, "error");
                }
            },
            error: function() {
                swal("Error", "Ocurrió un error de conexión al finalizar la compra.", "error");
            }
        });
    }
    
    /**
     * Llama al endpoint de Vaciar Carrito.
     */
    function vaciarCarrito() {
        $.ajax({
            url: BASE_URL + '/carrito/vaciar',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    swal("Vaciado", response.message, "info");
                    updateCarritoCount(response.count);
                    updateCarritoDisplay();
                }
            }
        });
    }
</script>

<?= $this->endSection() ?>