<style>
/* Estilos para el Modal (puede ir en el <style> de layout_user.php) */
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
.carrito-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #2C2A3B;
}
.carrito-item-info {
    flex-grow: 1;
}
.carrito-item-info strong {
    font-weight: 500;
    font-size: 16px;
    color: #6D28D9;
}
.carrito-total {
    margin-top: 20px;
    font-size: 20px;
    font-weight: 700;
    text-align: right;
    padding-top: 15px;
    border-top: 2px solid #6D28D9;
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
</style>

<div id="floating-cart-icon" style="position: fixed; bottom: 30px; right: 30px; background-color: #6D28D9; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; z-index: 999;">
    <svg class="icon" viewBox="0 0 24 24" style="stroke: white; width: 24px; height: 24px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
    <span id="cart-item-count" style="position: absolute; top: -5px; right: -5px; background-color: red; color: white; font-size: 12px; border-radius: 50%; padding: 2px 6px; font-weight: bold;">0</span>
</div>

<div id="carritoModal" class="modal-overlay">
    <div class="carrito-modal">
        <span class="modal-close" id="closeModalBtn">&times;</span>
        <h3 class="modal-title">Tu Carrito de Compras</h3>

        <div id="carritoContent">
            <p>El carrito está vacío.</p>
        </div>

        <div id="carritoTotal" class="carrito-total">
            Total: Q 0.00
        </div>

        <div class="modal-actions">
            <button id="vaciarCarritoBtn" class="btn-secondary">Vaciar Carrito</button>
            <button id="checkoutBtn" class="btn-primary">Finalizar Compra</button>
        </div>
    </div>
</div>