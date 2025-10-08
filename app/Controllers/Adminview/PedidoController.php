<?php namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\TipoMembresiaModel; 
use CodeIgniter\CLI\CLI; // Opcional, pero útil

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
            'pedidos' => $this->pedidoModel->getPedidosConUsuario($membresia_id),
            'membresias' => $this->membresiaModel->getAllMembresias(),
            'selected_membresia' => $membresia_id,
            'estados_validos' => ['Pendiente', 'Enviado', 'Entregado'],
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
        
        $pedido = $this->pedidoModel->getDetallePedidoAdmin($id);

        if (!$pedido) {
            session()->setFlashdata('error', 'Pedido no encontrado.');
            return redirect()->to(base_url('admin/pedidos'));
        }
        
        $data = ['pedido' => $pedido];
        return view('admin/pedidos/detalle', $data); 
    }

    /**
     * Acción para cambiar el estado de un pedido (Pendiente, Enviado, Entregado).
     */
    public function cambiarEstado($id)
    {
        // 1. Verificación estricta de POST
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $nuevoEstado = $this->request->getPost('estado');
        $estadosValidos = ['Pendiente', 'Enviado', 'Entregado'];

        if (!in_array($nuevoEstado, $estadosValidos)) {
            session()->setFlashdata('error', 'Estado no válido.');
            return redirect()->back();
        }

        // 2. Ejecutar la actualización
        $actualizado = $this->pedidoModel->actualizarEstado($id, $nuevoEstado);

        if ($actualizado) {
            
            session()->setFlashdata('success', 'Estado del pedido #' . $id . ' actualizado a ' . $nuevoEstado . '.');
            
        } else {
            // --- DETECCIÓN DE ERRORES CRÍTICA ---
            // Si el modelo devuelve false, significa que el update falló.
            
            // Si estás en desarrollo, detenemos la ejecución para debug.
            if (ENVIRONMENT !== 'production') {
                 // Recuperamos el error de la base de datos para mostrarlo
                 $error = $this->db->error();
                 
                 // Construimos los datos que se intentaron actualizar
                 $data_intentada = [
                    'estado_pedido' => $nuevoEstado,
                    'fecha_entrega' => ($nuevoEstado === 'Entregado' ? date('Y-m-d H:i:s') : null)
                 ];
                 
                 // Usamos dd() para mostrar el error y detener la ejecución.
                 dd("ERROR FATAL EN LA ACTUALIZACIÓN:", [
                     "Mensaje de CodeIgniter" => "La función 'update' falló o devolvió 0 filas afectadas. Revise los logs del servidor.",
                     "ID de Pedido" => $id,
                     "Datos Intentados" => $data_intentada,
                     "Error de la Base de Datos" => $error['message'] ?? "No se encontró un mensaje de error específico (Puede ser un problema de allowedFields)."
                 ]);
            }
            
            session()->setFlashdata('error', 'Error al actualizar el estado del pedido. Verifique el ID o si el campo ya tiene ese estado.');
        }

        // 3. Redirección final
        return redirect()->back();
    }
}
