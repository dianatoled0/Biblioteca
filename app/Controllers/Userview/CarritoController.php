<?php

namespace App\Controllers\Userview;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\DiscoModel; 

class CarritoController extends BaseController
{
    // Es buena práctica inicializar la librería Cart en el constructor
    protected $cart;

    public function __construct()
    {
        // Inicializa la librería Cart
        $this->cart = \Config\Services::cart();
        helper(['form', 'url']);
    }

    /**
     * Agrega un disco al carrito. Recibe disco_id y qty por POST.
     */
    public function agregar()
    {
        $id_disco = $this->request->getPost('id_disco');
        $qty = $this->request->getPost('qty') ?? 1;

        $discoModel = new DiscoModel();
        $disco = $discoModel->find($id_disco);

        if (!$disco) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Disco no encontrado.']);
        }

        // Estructura del ítem para la librería Cart
        $data = array(
            'id'      => $disco['id'],
            'qty'     => $qty,
            'price'   => $disco['precio_venta'],
            'name'    => $disco['nombre'],
            'options' => array('artista' => $disco['artista']) // Puedes añadir opciones extra
        );

        $this->cart->insert($data);

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Producto agregado al carrito.',
            'total_items' => $this->cart->totalItems()
        ]);
    }

    /**
     * **MÉTODO CORREGIDO: Actualiza la cantidad de un ítem en el carrito.**
     * Recibe rowid (identificador del ítem en el carrito) y qty (nueva cantidad) por POST.
     */
    public function actualizar()
    {
        // 1. Obtener datos necesarios del POST
        $rowid = $this->request->getPost('rowid');
        $qty = $this->request->getPost('qty');

        // 2. Validar
        if (!$rowid || !is_numeric($qty) || $qty < 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Datos de actualización inválidos.']);
        }

        // 3. Preparar el array de actualización
        $data = array(
            'rowid' => $rowid,
            'qty'   => $qty
        );

        // 4. Aplicar la actualización
        if ($qty == 0) {
             // Si la cantidad es 0, la librería la elimina, pero para ser explícitos:
             $this->cart->update(['rowid' => $rowid, 'qty' => 0]);
             return $this->response->setJSON(['status' => 'success', 'message' => 'Producto eliminado del carrito.']);
        } else {
             $this->cart->update($data);
             return $this->response->setJSON(['status' => 'success', 'message' => 'Cantidad actualizada.', 'new_total' => number_format($this->cart->total(), 2)]);
        }
    }

    /**
     * Obtiene el contenido actual del carrito (para mostrar el modal).
     */
    public function obtener()
    {
        // Esto generalmente se usa para cargar los datos en el modal.
        $items = $this->cart->contents();
        $total = $this->cart->total();
        
        return $this->response->setJSON([
            'status' => 'success',
            'items' => array_values($items), // Convertir a array indexado para JS
            'total' => number_format($total, 2)
        ]);
    }

    /**
     * Vacía completamente el carrito.
     */
    public function vaciar()
    {
        $this->cart->destroy();
        return redirect()->back()->with('success', 'El carrito ha sido vaciado.');
    }

    /**
     * Procesa la compra (checkout) y guarda el pedido.
     * (Esta función requiere la implementación de modelos de Pedidos)
     */
    public function checkout()
    {
        // Lógica de checkout aquí (Guardar Pedido, Detalle Pedido, vaciar carrito, etc.)
        if ($this->cart->totalItems() == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El carrito está vacío.']);
        }
        
        // Simulación:
        $this->cart->destroy();
        return $this->response->setJSON(['status' => 'success', 'message' => 'Compra finalizada con éxito. ¡Gracias!']);
    }
    
    // Nota: La función 'eliminar' ya no es estrictamente necesaria si usas actualizar con qty=0, 
    // pero si tienes un botón de eliminar dedicado, aquí estaría:
    // public function eliminar() { ... }
}