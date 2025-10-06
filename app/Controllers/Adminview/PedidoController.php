<?php namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\PedidoModel;

class PedidoController extends BaseController
{
    protected $pedidoModel;

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
        helper(['url']); 
    }

    /**
     * Muestra el listado de todos los pedidos. (Ruta: /admin/pedidos)
     */
    public function index()
    {
        $data = [
            'pedidos' => $this->pedidoModel->getPedidosConUsuario()
        ];
        
        // Carga la vista de listado de pedidos
        return view('admin/pedidos/index', $data);
    }

    /**
     * Muestra la vista de detalle para un pedido específico. (Ruta: /admin/pedidos/detalle/ID)
     * NOTA: Este es un placeholder. La lógica para cargar el detalle_pedidos va aquí.
     */
    public function verDetalle($id = null)
    {
        if (is_null($id) || !$this->pedidoModel->find($id)) {
            session()->setFlashdata('error', 'Pedido no encontrado.');
            return redirect()->to(base_url('admin/pedidos'));
        }
        
        // --- AQUÍ VA LA LÓGICA DE DETALLE DE PEDIDO (Implementaremos esto después) ---
        session()->setFlashdata('info', "¡Aún en desarrollo! Se mostrará el detalle del Pedido #{$id} y sus productos.");
        return redirect()->to(base_url('admin/pedidos'));

        // Ejemplo de cómo se llamaría la vista de detalle:
        // return view('admin/pedidos/detalle', $data_detalle);
    }
}
