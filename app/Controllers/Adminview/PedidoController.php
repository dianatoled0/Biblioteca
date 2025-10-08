<?php namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\TipoMembresiaModel; 

class PedidoController extends BaseController
{
    protected $pedidoModel;
    protected $membresiaModel; 

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
        $this->membresiaModel = new TipoMembresiaModel(); 
        helper(['url']); 
    }

    /**
     * Muestra el listado de todos los pedidos con filtro. (Ruta: /admin/pedidos)
     */
    public function index()
    {
        // Obtener el filtro de la URL
        $membresia_id = $this->request->getGet('membresia_id');

        $data = [
            'pedidos' => $this->pedidoModel->getPedidosConUsuario($membresia_id), // Usamos el nuevo método con filtro
            'membresias' => $this->membresiaModel->getAllMembresias(), // Para el dropdown del filtro
            'selected_membresia' => $membresia_id, // Para mantener la opción seleccionada
            'estados_validos' => ['Pendiente', 'Enviado', 'Entregado'], // Para la lógica de estado en la vista
        ];
        
        return view('admin/pedidos/index', $data);
    }

    /**
     * Muestra la vista de detalle para un pedido específico. (Ruta: /admin/pedidos/detalle/ID)
     */
    public function verDetalle($id = null)
    {
        if (is_null($id)) {
            return redirect()->to(base_url('admin/pedidos'));
        }
        
        // Usamos el método que trae toda la info para el admin
        $pedido = $this->pedidoModel->getDetallePedidoAdmin($id);

        if (!$pedido) {
            session()->setFlashdata('error', 'Pedido no encontrado.');
            return redirect()->to(base_url('admin/pedidos'));
        }
        
        $data = ['pedido' => $pedido];
        // Nota: La vista de detalle debe llamarse 'detalle.php', no 'form.php'
        return view('admin/pedidos/detalle', $data); 
    }

    /**
     * Acción para cambiar el estado de un pedido (Pendiente, Enviado, Entregado).
     */
    public function cambiarEstado($id)
    {
        if ($this->request->getMethod() !== 'post') {
             return redirect()->back(); // Solo POST permitido
        }

        $nuevoEstado = $this->request->getPost('estado');
        $estadosValidos = ['Pendiente', 'Enviado', 'Entregado'];

        if (!in_array($nuevoEstado, $estadosValidos)) {
            session()->setFlashdata('error', 'Estado no válido.');
            return redirect()->back();
        }

        if ($this->pedidoModel->actualizarEstado($id, $nuevoEstado)) {
            session()->setFlashdata('success', 'Estado del pedido #' . $id . ' actualizado a ' . $nuevoEstado . '.');
        } else {
            session()->setFlashdata('error', 'Error al actualizar el estado del pedido.');
        }

        return redirect()->back();
    }
}
