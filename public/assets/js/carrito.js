/**
 * Lógica de frontend para el carrito de compras, comunicándose con el controlador PHP.
 * Requiere jQuery.
 */

$(document).ready(function() {
    
    // --- URLs de los endpoints de la API (Ajustar según tu configuración de rutas) ---
    const API_URLS = {
        agregar: '/carrito/agregar',
        obtener: '/carrito/obtener',
        actualizar: '/carrito/actualizar',
        vaciar: '/carrito/vaciar'
    };

    const $toastContainer = $('#toast-container'); // Contenedor para mensajes (asegúrate de que existe en tu HTML)

    /**
     * Muestra un mensaje de notificación (toast).
     * @param {string} message - El mensaje a mostrar.
     * @param {string} type - 'success' o 'error'.
     */
    function showToast(message, type = 'success') {
        const toastClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const $toast = $(`<div class="p-3 text-white rounded-lg shadow-lg mb-2 ${toastClass}">${message}</div>`);
        
        if ($toastContainer.length) {
            $toastContainer.append($toast);
        } else {
            console.warn('Contenedor de toast no encontrado. Mostrando alerta en consola:', message);
            // Fallback en caso de que no exista el elemento visual
            alert(message); 
        }

        // Eliminar el toast después de 4 segundos
        setTimeout(() => {
            $toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 4000);
    }

    /**
     * Dibuja o actualiza la interfaz del carrito (items, totales).
     * @param {object} data - Datos del carrito (items y totales).
     */
    function renderizarCarrito(data) {
        const $cartItems = $('#cart-items-list'); // El <tbody> o <div> que lista los productos
        const $totalFinal = $('#cart-total-final');
        const $subtotal = $('#cart-subtotal');
        const $descuento = $('#cart-descuento');
        const $envio = $('#cart-envio');
        const $totalItemsBadge = $('#total-items-badge'); // Badge con el número total de items
        
        $cartItems.empty();
        
        if (data.items.length === 0) {
            $cartItems.html('<tr><td colspan="5" class="text-center py-4 text-gray-500">El carrito está vacío.</td></tr>');
        } else {
            data.items.forEach(item => {
                const row = `
                    <tr data-rowid="${item.rowid}">
                        <td class="px-4 py-2">${item.name} (${item.options.artista})</td>
                        <td class="px-4 py-2">$${parseFloat(item.price).toFixed(2)}</td>
                        <td class="px-4 py-2">
                            <input type="number" 
                                class="w-16 p-1 border rounded text-center update-qty" 
                                value="${item.qty}" 
                                min="0" 
                                data-rowid="${item.rowid}"
                            >
                        </td>
                        <td class="px-4 py-2 subtotal-item-value">$${parseFloat(item.subtotal).toFixed(2)}</td>
                        <td class="px-4 py-2">
                            <button class="text-red-500 hover:text-red-700 remove-item-btn" data-rowid="${item.rowid}">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                `;
                $cartItems.append(row);
            });
        }
        
        // Actualizar totales
        const totales = data.totales;
        $subtotal.text(`$${parseFloat(totales.subtotal).toFixed(2)}`);
        $descuento.text(`-$${parseFloat(totales.descuento).toFixed(2)}`);
        $envio.text(`$${parseFloat(totales.costo_envio).toFixed(2)}`);
        $totalFinal.text(`$${parseFloat(totales.total_final).toFixed(2)}`);
        
        // Actualizar contador de items global (navbar/badge)
        const totalItemsCount = data.items.reduce((sum, item) => sum + item.qty, 0);
        $totalItemsBadge.text(totalItemsCount);
        
        // Mostrar/Ocultar el botón de vaciar/checkout
        if (data.items.length > 0) {
            $('#checkout-actions').show();
        } else {
            $('#checkout-actions').hide();
        }
    }

    /**
     * Carga el estado actual del carrito desde el controlador.
     */
    function cargarCarrito() {
        $.ajax({
            url: API_URLS.obtener,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    renderizarCarrito(response);
                } else {
                    showToast(response.message || 'Error al cargar el carrito.', 'error');
                    console.error('Error al cargar carrito:', response);
                }
            },
            error: function(xhr, status, error) {
                showToast('Error de conexión con el servidor. No se pudo cargar el carrito.', 'error');
                console.error('AJAX Error:', status, error, xhr.responseText);
            }
        });
    }

    // --- 1. Agregar Producto al Carrito ---
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const idDisco = $btn.data('id');
        const qty = $btn.closest('form').find('.product-qty').val() || 1; 

        if (!idDisco) {
            showToast('Error: ID de disco no definido.', 'error');
            return;
        }

        // Deshabilitar botón mientras carga
        $btn.prop('disabled', true).text('Agregando...'); 

        $.ajax({
            url: API_URLS.agregar,
            method: 'POST',
            data: { id_disco: idDisco, qty: qty, [csrf_token_name]: csrf_token_hash },
            dataType: 'json',
            success: function(response) {
                $btn.prop('disabled', false).text('Añadir al Carrito'); // Restablecer botón
                
                if (response.status === 'success') {
                    showToast(response.message);
                    cargarCarrito(); // Recargar la vista del carrito
                } else {
                    showToast(response.message || 'Error al agregar el producto.', 'error');
                }
            },
            error: function(xhr, status, error) {
                $btn.prop('disabled', false).text('Añadir al Carrito'); // Restablecer botón
                showToast('Error de conexión (500). Verifique el log del servidor.', 'error');
                console.error('AJAX Error en agregar:', status, error, xhr.responseText);
            }
        });
    });

    // --- 2. Actualizar Cantidad/Eliminar Item ---
    $(document).on('change', '.update-qty', function() {
        const $input = $(this);
        const rowId = $input.data('rowid');
        const newQty = parseInt($input.val());

        if (isNaN(newQty) || newQty < 0) {
            $input.val(1); // Reset a 1 si el valor es inválido
            return;
        }
        
        actualizarItem(rowId, newQty);
    });

    $(document).on('click', '.remove-item-btn', function() {
        const rowId = $(this).data('rowid');
        actualizarItem(rowId, 0); // Establecer cantidad a 0 para eliminar
    });
    
    /**
     * Función genérica para actualizar la cantidad del ítem.
     */
    function actualizarItem(rowId, newQty) {
        $.ajax({
            url: API_URLS.actualizar,
            method: 'POST',
            data: { 
                rowid: rowId, 
                qty: newQty,
                [csrf_token_name]: csrf_token_hash
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showToast(response.message);
                    
                    // Si la cantidad es 0, eliminar la fila visualmente
                    if (newQty === 0) {
                        $(`#cart-items-list tr[data-rowid="${rowId}"]`).remove();
                    }
                    
                    // Actualizar el subtotal del ítem si aún existe
                    const $itemRow = $(`#cart-items-list tr[data-rowid="${rowId}"]`);
                    $itemRow.find('.subtotal-item-value').text(`$${response.new_subtotal_item}`);

                    // Actualizar todos los totales
                    const totales = response.totales;
                    $('#cart-subtotal').text(`$${parseFloat(totales.subtotal).toFixed(2)}`);
                    $('#cart-descuento').text(`-$${parseFloat(totales.descuento).toFixed(2)}`);
                    $('#cart-envio').text(`$${parseFloat(totales.costo_envio).toFixed(2)}`);
                    $('#cart-total-final').text(`$${parseFloat(totales.total_final).toFixed(2)}`);

                    // Recargar por si hay que ocultar/mostrar elementos (ej. el botón de Checkout)
                    cargarCarrito(); 

                } else {
                    showToast(response.message || 'Error al actualizar la cantidad.', 'error');
                }
            },
            error: function(xhr, status, error) {
                showToast('Error de conexión al actualizar el carrito.', 'error');
                console.error('AJAX Error en actualizar:', status, error, xhr.responseText);
            }
        });
    }

    // --- 3. Vaciar Carrito Completo ---
    $('#vaciar-carrito-btn').on('click', function() {
        if (!confirm('¿Está seguro que desea vaciar su carrito de compras?')) {
            return;
        }

        $.ajax({
            url: API_URLS.vaciar,
            method: 'POST',
            data: { [csrf_token_name]: csrf_token_hash },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showToast(response.message);
                    cargarCarrito(); // Recargar para vaciar la vista
                } else {
                    showToast(response.message || 'Error al vaciar el carrito.', 'error');
                }
            },
            error: function(xhr, status, error) {
                showToast('Error de conexión al vaciar el carrito.', 'error');
                console.error('AJAX Error en vaciar:', status, error, xhr.responseText);
            }
        });
    });

    // --- Carga Inicial al Abrir la Vista del Carrito ---
    // Si esta función está en la vista del carrito, la llama inmediatamente:
    // cargarCarrito(); 
});

// Nota de implementación:
// Debe definir las variables 'csrf_token_name' y 'csrf_token_hash'
// en su vista PHP para que el CSRF funcione. Ejemplo:
// <script>
//     const csrf_token_name = '<?= csrf_token() ?>';
//     const csrf_token_hash = '<?= csrf_hash() ?>';
// </script>