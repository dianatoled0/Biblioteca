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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
    // Definiciones de URLs y CSRF
    const BASE_URL = '<?= base_url() ?>';
    const USER_BASE_URL = '<?= base_url('usuario') ?>';
    const CART_BASE_URL = USER_BASE_URL + '/carrito';

    const csrf_token_name = '<?= csrf_token() ?>';
    const csrf_token_hash = '<?= csrf_hash() ?>';

    // Fuerza header AJAX global para que isAJAX() detecte correctamente
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    $(document).ready(function() {
        // --- 1. Inicialización de filtros ---
        $('#categoryFilter button').on('click', function() {
            $('#categoryFilter button').removeClass('active');
            $(this).addClass('active');
            filterDiscos($(this).data('id'));
        });

        // --- 2. Lógica para abrir/cerrar el modal del carrito ---
        $('#floating-cart-icon').on('click', function() {
            updateCarritoDisplay(); 
            $('#carritoModal').fadeIn(300);
        });
        $('#closeModalBtn').on('click', function() {
            $('#carritoModal').fadeOut(300);
        });
        $('#carritoModal').on('click', function(e) {
            if (e.target.id === 'carritoModal') {
                $(this).fadeOut(300);
            }
        });

        // --- 3. Lógica para añadir 1 disco al carrito ---
        $(document).on('click', '.add-to-cart-btn', function() {
            const discoId = $(this).data('id');
            addToCarrito(discoId, 1); 
        });

        // --- 4. Eventos dentro del modal ---
        $('#vaciarCarritoBtn').on('click', function() {
            vaciarCarrito();
        });
        
        $('#finalizarCompraBtn').on('click', function() {
            finalizarCompra();
        });
        
        $('#carrito-items-list').on('change', '.cart-quantity-input', function() {
            const rowId = $(this).data('rowid');
            const newQty = parseInt($(this).val());
            if (newQty >= 0) {
                actualizarCantidadCarrito(rowId, newQty);
            } else {
                 $(this).val(1);
            }
        });

        $('#carrito-items-list').on('click', '.remove-item-btn', function() {
            const rowId = $(this).data('rowid');
            eliminarDelCarrito(rowId);
        });

        // Carga el contador inicial
        updateCarritoDisplay(true); 
    });
    
    // LÓGICA DE FILTRO
    function filterDiscos(categoryId) {
        $.ajax({
            url: USER_BASE_URL + '/ajax/discos/' + categoryId,
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
    
    // LÓGICA DE CARRITO Y CHECKOUT
    const formatQuetzal = (number) => {
        return parseFloat(number).toFixed(2);
    };

    function addToCarrito(discoId, cantidad) {
        $.ajax({
            url: CART_BASE_URL + '/agregar',
            type: 'POST',  // Fuerza 'type' para compatibilidad
            data: { id_disco: discoId, qty: cantidad, [csrf_token_name]: csrf_token_hash },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.total_items);
                    swal("Agregado", response.message, "success");
                } else {
                    swal("Error", response.message, "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al agregar al carrito. Código:", xhr.status, "Mensaje:", xhr.responseText);
                swal("Error", "Ocurrió un error de conexión al agregar el disco. Verifique la consola (F12 > Network). Código: " + xhr.status, "error");
            }
        });
    }
    
    function actualizarCantidadCarrito(rowId, cantidad) {
        $.ajax({
            url: CART_BASE_URL + '/actualizar',
            type: 'POST',
            data: { rowid: rowId, qty: cantidad, [csrf_token_name]: csrf_token_hash },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.total_items);
                    updateCarritoDisplay();
                } else {
                    swal("Error", response.message, "error");
                    updateCarritoDisplay();
                }
            },
            error: function() {
                swal("Error", "Ocurrió un error al actualizar la cantidad.", "error");
            }
        });
    }
    
    function eliminarDelCarrito(rowId) {
        $.ajax({
            url: CART_BASE_URL + '/eliminar',
            type: 'POST',
            data: { rowid: rowId, [csrf_token_name]: csrf_token_hash },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCarritoCount(response.total_items);
                    updateCarritoDisplay();
                } else {
                    swal("Error", response.message, "error");
                }
            }
        });
    }

    function updateCarritoDisplay(isInitialLoad = false) {
        $.ajax({
            url: CART_BASE_URL + '/obtener',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.items) {
                    let itemsArray = response.items;
                    let totales = response.totales || { subtotal: 0, descuento: 0, costo_envio: 0, total_final: 0 };

                    renderCarritoModal(itemsArray, totales); 
                    updateCarritoCount(itemsArray.reduce((sum, item) => sum + item.qty, 0));
                } else {
                    renderCarritoModal([], { subtotal: 0, descuento: 0, costo_envio: 0, total_final: 0 });
                    updateCarritoCount(0);
                }
            },
            error: function(xhr, status, error) {
                if (!isInitialLoad) {
                    console.error("Error al cargar carrito. Código:", xhr.status, "Mensaje:", xhr.responseText);
                    swal("Error", "No se pudo cargar el carrito. Intente de nuevo.", "error");
                }
            }
        });
    }
    
    function renderCarritoModal(items, totales) {
        const $list = $('#carrito-items-list');
        $list.empty();
        
        const isEmpty = items.length === 0;
        
        $('#carrito-vacio-msg').toggle(isEmpty);
        $('#carrito-content-header').toggle(!isEmpty);
        $('#carritoTotalResumen').toggle(!isEmpty);
        $('#finalizarCompraBtn').prop('disabled', isEmpty);

        if (isEmpty) return;

        let html = '';
        items.forEach(function(item) {
            html += `
                <div class="carrito-item-grid">
                    <div class="carrito-item-info"><strong>${item.name}</strong></div>
                    <div style="text-align: right;">Q ${formatQuetzal(item.price)}</div>
                    <div style="text-align: center;">
                        <input type="number" 
                               class="cart-quantity-input" 
                               value="${item.qty}" 
                               min="0" 
                               data-rowid="${item.rowid}" 
                               style="width: 60px; text-align: center;">
                    </div>
                    <div style="text-align: center;">
                        <button class="btn btn-sm btn-danger remove-item-btn" data-rowid="${item.rowid}" style="padding: 2px 8px;">X</button>
                    </div>
                </div>
            `;
        });
        $list.html(html);
        
        $('#resumen-subtotal').text(formatQuetzal(totales.subtotal));
        $('#resumen-descuento').text(formatQuetzal(totales.descuento));
        $('#resumen-envio').text(formatQuetzal(totales.costo_envio));
        $('#resumen-total-final').text(formatQuetzal(totales.total_final));
    }
    
    function updateCarritoCount(count) {
        $('.cart-count').text(count);
    }
    
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
                    url: CART_BASE_URL + '/checkout',
                    type: 'POST',
                    data: { [csrf_token_name]: csrf_token_hash },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            swal("¡Éxito!", "Tu compra se realizó con éxito.", "success");
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
        });
    }
    
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
                    url: CART_BASE_URL + '/vaciar',
                    type: 'GET',
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