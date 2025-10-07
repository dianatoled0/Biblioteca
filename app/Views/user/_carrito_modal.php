<style>
/* ... (MANTEN TUS ESTILOS CSS ORIGINALES AQUI) ... */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: none; /* Inicialmente oculto */
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.carrito-modal {
    background-color: #201D2E;
    color: #FFFFFF;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    position: relative;
}
.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #A0AEC0;
    cursor: pointer;
    line-height: 1;
}
.modal-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    border-bottom: 1px solid #2C2A3B;
    padding-bottom: 15px;
}
/* Estilos para la lista y la cantidad */
.carrito-item-grid {
    display: grid;
    grid-template-columns: 3fr 1fr 1fr 0.5fr; /* Título | Precio | Cantidad | Eliminar */
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #2C2A3B;
    align-items: center;
}
.carrito-total-resumen { /* Nuevo contenedor para el resumen */
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid #2C2A3B;
}
.carrito-total-line {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    margin-bottom: 5px;
}
.carrito-total-final {
    display: flex;
    justify-content: space-between;
    font-size: 20px;
    font-weight: 700;
    padding-top: 10px;
    border-top: 1px dashed #6D28D9;
    margin-top: 10px;
}
.modal-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}
.btn-secondary {
    background-color: #F59E0B;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
}
.btn-secondary:hover { background-color: #D97706; }
.btn-primary {
    background-color: #6D28D9;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
}
.btn-primary:hover:not(:disabled) { background-color: #4C1D95; }
.btn-primary:disabled { background-color: #4C1D95; opacity: 0.6; cursor: not-allowed; }

/* Estilo para Notificación */
.cart-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    padding: 10px 20px;
    background-color: #4CAF50; /* Verde éxito */
    color: white;
    border-radius: 5px;
    display: none; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<div id="floating-cart-icon" style="position: fixed; bottom: 30px; right: 30px; background-color: #6D28D9; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; z-index: 999;">
    <svg class="icon" viewBox="0 0 24 24" style="stroke: white; width: 24px; height: 24px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
    <span class="cart-count" style="position: absolute; top: -5px; right: -5px; background-color: red; color: white; font-size: 12px; border-radius: 50%; padding: 2px 6px; font-weight: bold;">0</span>
</div>

<div id="cartNotification" class="cart-notification"></div>

<div id="carritoModal" class="modal-overlay">
    <div class="carrito-modal">
        <span class="modal-close" id="closeModalBtn">&times;</span>
        <h3 class="modal-title">Tu Carrito de Compras</h3>

        <div id="carrito-content-header" class="carrito-item-grid" style="font-weight: bold; border-bottom: 2px solid #6D28D9; display: none;">
            <span>Producto</span>
            <span style="text-align: right;">Precio (Q)</span>
            <span style="text-align: center;">Cantidad</span>
            <span></span>
        </div>
        
        <div id="carrito-items-list">
            <p id="carrito-vacio-msg">El carrito está vacío.</p>
        </div>

        <div id="carritoTotalResumen" class="carrito-total-resumen" style="display:none;">
            <div class="carrito-total-line">
                <span>Subtotal Productos:</span>
                <span>Q <span id="resumen-subtotal">0.00</span></span>
            </div>
            <div class="carrito-total-line" style="color: #48BB78;">
                <span>Descuento por Membresía:</span>
                <span>- Q <span id="resumen-descuento">0.00</span></span>
            </div>
            <div class="carrito-total-line">
                <span>Costo de Envío:</span>
                <span>Q <span id="resumen-envio">0.00</span></span>
            </div>
            <div class="carrito-total-final">
                <span>TOTAL A PAGAR:</span>
                <span>Q <span id="resumen-total-final">0.00</span></span>
            </div>
        </div>

        <div class="modal-actions">
            <button id="vaciarCarritoBtn" class="btn-secondary">Vaciar Carrito</button>
            <button id="finalizarCompraBtn" class="btn-primary" disabled>Finalizar Compra</button>
        </div>
    </div>
</div>