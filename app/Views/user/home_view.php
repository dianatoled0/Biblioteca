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
    // **********************************************
    // CORRECCIÓN CLAVE: Usar la URL base para el controlador de Carrito
    // Asumo que tu controlador de Carrito está en la raíz, no dentro de 'usuario'.
    // Si tu ruta es 'carrito/agregar', esto apunta a http://localhost/biblioteca/public/carrito/agregar
    const BASE_URL = '<?= base_url() ?>';
    const USER_BASE_URL = '<?= base_url('usuario') ?>'; // Para rutas del usuario (filtros)

    // Si tu ruta de carrito *realmente* está dentro del grupo 'usuario', usa:
    // const BASE_URL = '<?= base_url('usuario') ?>'; 
    // Y la línea 354 quedaría: url: BASE_URL + '/carrito/agregar', (como estaba antes)
    // **********************************************

    $(document).ready(function() {
        // --- 1. Inicialización de filtros ---
        $('#categoryFilter button').on('click', function() {
            $('#categoryFilter button').removeClass('active');
            $(this).addClass('active');
            filterDiscos($(this).data('id'));
        });

        // --- 2. Lógica para abrir/cerrar el modal del carrito ---
        // Listener del icono flotante
        $('#floating-cart-icon').on('click', function() {
            updateCarritoDisplay(); 
            $('#carritoModal').fadeIn(300);
        });
        // Listener del botón de cerrar
        $('#closeModalBtn').on('click', function() {
            $('#carritoModal').fadeOut(300);
        });
        // Listener para cerrar al hacer clic en el overlay
        $('#carritoModal').on('click', function(e) {
            if (e.target.id === 'carritoModal') {
                $(this).fadeOut(300);
            }
        });


        // --- 3. Lógica para añadir 1 disco al carrito (CORRECCIÓN CLAVE) ---
        // Este evento maneja los botones de 'Comprar' estáticos y los creados por AJAX
        $(document).on('click', '.add-to-cart-btn', function() {
            const discoId = $(this).data('id');
            // Nota: Aquí se está asumiendo que quieres agregar 1 unidad por cada clic.
            addToCarrito(discoId, 1); 
        });

        // --- 4. Eventos dentro del modal (Usando delegación de eventos) ---
        
        // Evento para VACIAR el carrito
        $('#vaciarCarritoBtn').on('click', function() {
            vaciarCarrito();
        });
        
        // Evento para FINALIZAR la compra (Checkout)
        $('#finalizarCompraBtn').on('click', function() {
            finalizarCompra();
        });
        
        // Evento para ACTUALIZAR la cantidad dentro del modal
        $('#carrito-items-list').on('change', '.cart-quantity-input', function() {
            const discoId = $(this).data('id');
            const newQty = parseInt($(this).val());
            if (newQty >= 0) {
                // LLamada a la función unificada para cambiar cantidad o eliminar (si es 0)
                actualizarCantidadCarrito(discoId, newQty);
            } else {
                 $(this).val(1); // Mantiene la cantidad en 1 si se intenta poner negativo
            }
        });

        // Evento para ELIMINAR un ítem del modal (Botón X)
        $('#carrito-items-list').on('click', '.remove-item-btn', function() {
            const discoId = $(this).data('id');
            eliminarDelCarrito(discoId);
        });

        // Carga el contador inicial
        updateCarritoDisplay(true); 
    });
    
    // =========================================================
    // LÓGICA DE FILTRO (Usa USER_BASE_URL)
    // =========================================================
    function filterDiscos(categoryId) {
        $.ajax({
            url: USER_BASE_URL + '/ajax/discos/' + categoryId, // Usa la URL base de usuario
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
    // LÓGICA DE CARRITO Y CHECKOUT (Usa BASE_URL)
    // =========================================================
    
    // Función para formatear a Q 0.00
    const formatQuetzal = (number) => {
        return parseFloat(number).toFixed(2);
    };

    /**
     * Muestra una notificación temporal al usuario.
     */
    function showNotification(message, isError = false) {
        // ... (Tu código de showNotification aquí) ...
        const notification = $('#cartNotification');
        // NOTA: No tienes el div #cartNotification en tu vista, así que usa SweetAlert
        
        if (isError) {
             swal("Error", message, "error");
        } else {
             swal("Agregado", message, "success");
        }
        
    }

    /**
     * Agrega un disco al carrito.
     */
    function addToCarrito(discoId, cantidad) {
        $.ajax({
            // CORRECCIÓN APLICADA AQUÍ: Usa BASE_URL
            url: BASE_URL + 'carrito/agregar', 
            method: 'POST',
            data: { id: discoId, cantidad: cantidad },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.count);
                    // Usar response.message del controlador
                    swal("Agregado", response.message, "success");
                } else {
                    swal("Error", response.message, "error");
                }
            },
            error: function(xhr, status, error) {
                // El error de conexión aparece aquí
                console.error("Error al agregar al carrito. Código:", xhr.status, "Mensaje:", xhr.responseText);
                swal("Error", "Ocurrió un error de conexión al agregar el disco. Verifique la consola (F12 > Network).", "error");
            }
        });
    }
    
    /**
     * Actualiza la cantidad de un disco en el carrito.
     */
    function actualizarCantidadCarrito(discoId, cantidad) {
        $.ajax({
            url: BASE_URL + 'carrito/actualizar',
            method: 'POST',
            data: { id: discoId, cantidad: cantidad },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.count);
                    updateCarritoDisplay(); // Recarga el modal
                } else {
                    swal("Error", response.message, "error");
                    updateCarritoDisplay(); // Vuelve a cargar el carrito para corregir la cantidad en el input
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
            url: BASE_URL + 'carrito/eliminar',
            method: 'POST',
            data: { id: discoId }, // Asumo que tu controlador usa el ID del disco
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
    function updateCarritoDisplay(isInitialLoad = false) {
        $.ajax({
            url: BASE_URL + 'carrito/obtener',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                 if (response.status === 'success' && (response.items || response.carrito)) {
                    // Determinar qué array usar
                    let itemsArray = response.items || Object.values(response.carrito);
                    let totales = response.totales || { subtotal: 0, descuento: 0, costo_envio: 0, total_final: 0 };

                    renderCarritoModal(itemsArray, totales); 
                    updateCarritoCount(itemsArray.length);
                } else {
                    // Caso por defecto (carrito vacío)
                    renderCarritoModal([], { subtotal: 0, descuento: 0, costo_envio: 0, total_final: 0 });
                    updateCarritoCount(0);
                }
            },
            error: function(xhr, status, error) {
                // Solo muestra error si no es la carga inicial
                if (!isInitialLoad) {
                    console.error("Error al cargar carrito. Código:", xhr.status, "Mensaje:", xhr.responseText);
                    swal("Error", "No se pudo cargar el carrito. Intente de nuevo.", "error");
                }
            }
        });
    }
    
    /**
     * Renderiza el contenido dentro del modal. (USANDO LA ESTRUCTURA DEL MODAL)
     */
    function renderCarritoModal(items, totales) {
        const $list = $('#carrito-items-list');
        $list.empty();
        
        const isEmpty = items.length === 0;
        
        // Muestra u oculta elementos según si hay ítems
        $('#carrito-vacio-msg').toggle(isEmpty);
        $('#carrito-content-header').toggle(!isEmpty);
        $('#carritoTotalResumen').toggle(!isEmpty); // Muestra/Oculta el resumen de totales
        $('#finalizarCompraBtn').prop('disabled', isEmpty);

        if (isEmpty) return;

        let html = '';
        items.forEach(function(item) {
            // Se asume que item.id es el ID del disco.
            const discoId = item.id; 
            
            html += `
                <div class="carrito-item-grid">
                    <div class="carrito-item-info"><strong>${item.name}</strong></div>
                    <div style="text-align: right;">Q ${formatQuetzal(item.price)}</div>
                    <div style="text-align: center;">
                        <input type="number" 
                                 class="cart-quantity-input" 
                                 value="${item.qty}" 
                                 min="1" 
                                 data-id="${discoId}" 
                                 style="width: 60px; text-align: center;">
                    </div>
                    <div style="text-align: center;">
                        <button class="btn btn-sm btn-danger remove-item-btn" data-id="${discoId}" style="padding: 2px 8px;">X</button>
                    </div>
                </div>
            `;
        });
        $list.html(html);
        
        // Actualizar Totales
        $('#resumen-subtotal').text(formatQuetzal(totales.subtotal));
        $('#resumen-descuento').text(formatQuetzal(totales.descuento));
        $('#resumen-envio').text(formatQuetzal(totales.costo_envio));
        $('#resumen-total-final').text(formatQuetzal(totales.total_final));
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
        swal({
            title: "¿Confirmar Compra?",
            text: "Estás a punto de finalizar la compra de tus discos.",
            icon: "warning",
            buttons: ["Cancelar", "Confirmar"],
            dangerMode: true,
        })
        .then((willCheckout) => {
            if (willCheckout) {
                $('#carritoModal').fadeOut(300);
                $.ajax({
                    url: BASE_URL + 'carrito/checkout',
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            swal("¡Éxito!", "Tu compra se realizó con éxito.", "success");
                            updateCarritoDisplay(); // Vuelve a cargar y vacía el carrito
                        } else {
                            swal("Error al comprar", response.message, "error");
                        }
                    },
                    error: function() {
                        swal("Error", "Ocurrió un error de conexión al finalizar la compra.", "error");
                    }
                });
            }
        });
    }
    
    /**
     * Llama al endpoint de Vaciar Carrito.
     */
    function vaciarCarrito() {
        swal({
            title: "¿Vaciar Carrito?",
            text: "Se eliminarán todos los productos de tu carrito.",
            icon: "info",
            buttons: ["Cancelar", "Sí, Vaciar"],
        })
        .then((willEmpty) => {
            if (willEmpty) {
                $.ajax({
                    url: BASE_URL + 'carrito/vaciar',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            swal("Vaciado", response.message, "info");
                            updateCarritoDisplay();
                        }
                    }
                });
            }
        });
    }
</script>

<?= $this->endSection() ?>