// Asegúrate de que BASE_URL se defina en home_view.php antes de cargar este script
// Ejemplo: const BASE_URL = 'http://localhost/melofy/usuario'; 

/**
 * Muestra una alerta o mensaje de éxito/error.
 */
function showAlert(message, type = 'success') {
    // Implementación básica (puedes usar SweetAlert o un div personalizado)
    console.log(`[${type.toUpperCase()}]: ${message}`);
    alert(message);
}

/**
 * 1. Agrega un disco al carrito a través de AJAX.
 * @param {number} id - ID del disco.
 * @param {number} cantidad - Cantidad a agregar.
 */
function addToCarrito(id, cantidad) {
    $.ajax({
        url: BASE_URL + '/carrito/agregar',
        method: 'POST',
        data: { id: id, cantidad: cantidad },
        dataType: 'json',
        success: function(response) {
            showAlert(response.message, response.status);
            if (response.status === 'success') {
                updateCarritoDisplay(); // Refresca el carrito y el contador
            }
        },
        error: function() {
            showAlert('Error de conexión al agregar el disco.', 'error');
        }
    });
}

/**
 * 2. Obtiene el contenido actual del carrito de la sesión y actualiza el modal y el contador.
 */
function updateCarritoDisplay() {
    $.ajax({
        url: BASE_URL + '/carrito/obtener',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            const carrito = response.carrito;
            const count = response.count;
            
            // 1. Renderiza el contenido del modal
            if (Object.keys(carrito).length === 0) {
                html = '<p style="color: #A0AEC0;">El carrito está vacío.</p>';
                $('#checkoutBtn').prop('disabled', true);
            } else {
                $('#checkoutBtn').prop('disabled', false);
                for (const id in carrito) {
                    const item = carrito[id];
                    const subtotal = (item.precio_venta * item.cantidad).toFixed(2);
                    html += `
                        <div class="carrito-item">
                            <div class="carrito-item-info">
                                <strong>${item.titulo}</strong><br>
                                Q ${item.precio_venta} x ${item.cantidad}
                            </div>
                            <span>Q ${subtotal}</span>
                        </div>
                    `;
                }
            }
            $('#carritoContent').html(html);
            $('#carritoTotal').text(`Total: Q ${response.total}`);
            
            // 2. Actualiza el contador flotante
            $('#cart-item-count').text(count);
        },
        error: function() {
            console.error('Error al obtener el carrito.');
        }
    });
}

/**
 * 3. Vacía el carrito (llama al controlador).
 */
function vaciarCarrito() {
    if (!confirm("¿Está seguro de que desea vaciar el carrito?")) {
        return;
    }
    $.ajax({
        url: BASE_URL + '/carrito/vaciar',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            showAlert(response.message, response.status);
            updateCarritoDisplay();
        },
        error: function() {
            showAlert('Error al vaciar el carrito.', 'error');
        }
    });
}

/**
 * 4. Finaliza la compra (llama al controlador).
 */
function checkout() {
    if (!confirm("¿Confirma que desea realizar la compra?")) {
        return;
    }
    
    // Ocultar modal para evitar doble clic o interrupciones
    $('#carritoModal').css('display', 'none'); 

    $.ajax({
        url: BASE_URL + '/carrito/checkout',
        method: 'POST',
        dataType: 'json',
        beforeSend: function() {
             showAlert('Procesando su compra...', 'info');
        },
        success: function(response) {
            if (response.status === 'success') {
                showAlert(`¡Compra finalizada con éxito! Pedido #${response.id_pedido}`, 'success');
                // Recargar la página para reflejar el stock y limpiar la tienda si es necesario
                window.location.reload(); 
            } else {
                showAlert(`No se pudo finalizar la compra: ${response.message}`, 'error');
            }
        },
        error: function(xhr, status, error) {
            showAlert(`Error de servidor durante el checkout.`, 'error');
            console.error("Checkout AJAX Error:", error);
        }
    });
}


// --- Event Handlers al Cargar el DOM ---
$(document).ready(function() {
    // Abrir Modal
    $('#floating-cart-icon').on('click', function() {
        updateCarritoDisplay(); // Aseguramos que el contenido esté fresco
        $('#carritoModal').css('display', 'flex');
    });

    // Cerrar Modal
    $('#closeModalBtn').on('click', function() {
        $('#carritoModal').css('display', 'none');
    });
    // Cerrar si se hace clic fuera del modal
    $('#carritoModal').on('click', function(e) {
        if (e.target.id === 'carritoModal') {
            $(this).css('display', 'none');
        }
    });

    // Botón Vaciar Carrito
    $('#vaciarCarritoBtn').on('click', vaciarCarrito);

    // Botón Checkout
    $('#checkoutBtn').on('click', checkout);

    // Inicializa el contador al cargar la página
    updateCarritoDisplay();
});