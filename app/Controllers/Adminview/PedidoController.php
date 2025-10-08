<?php

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\TipoMembresiaModel;
use App\Models\NotificacionModel;

class PedidoController extends BaseController
{
    protected $pedidoModel;
    protected $membresiaModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
        $this->membresiaModel = new TipoMembresiaModel();
        $this->notificacionModel = new NotificacionModel();
        helper(['url']);
    }

    /**
     * Muestra todos los pedidos con opción de filtrar por membresía y estado.
     */
    public function index()
    {
        $membresia_id = $this->request->getGet('membresia_id');
        // CAMBIO CLAVE: Capturar el filtro de estado de la URL.
        $selected_estado = $this->request->getGet('estado_pedido');

        // Definir los estados válidos disponibles para filtrado y el dropdown (debe coincidir con el modelo).
        $estados_validos = ['Pendiente', 'Enviado', 'Entregado'];

        // CAMBIO CLAVE: Pasar ambos filtros al método del modelo.
        $data = [
            'pedidos' => $this->pedidoModel->getPedidosConUsuario($membresia_id, $selected_estado),
            'membresias' => $this->membresiaModel->getAllMembresias(),
            'selected_membresia' => $membresia_id,
            'estados_validos' => $estados_validos,
            // Pasar el estado seleccionado a la vista.
            'selected_estado' => $selected_estado, 
        ];

        return view('admin/pedidos/index', $data);
    }

    /**
     * Muestra el detalle de un pedido específico.
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
     * Cambia el estado de un pedido.
     */
    public function cambiarEstado($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $nuevoEstado = $this->request->getPost('estado');
        $estadosValidos = ['Pendiente', 'Enviado', 'Entregado'];

        // Validar estado
        if (!in_array($nuevoEstado, $estadosValidos)) {
            session()->setFlashdata('error', 'Estado no válido.');
            return redirect()->back();
        }

        // Verificar existencia del pedido
        $pedido_actual = $this->pedidoModel->find($id);
        if (!$pedido_actual) {
            session()->setFlashdata('error', 'Pedido no encontrado para actualizar.');
            return redirect()->back();
        }

        $estado_anterior = $pedido_actual['estado_pedido'];

        // Actualizar el estado
        $actualizado = $this->pedidoModel->actualizarEstado($id, $nuevoEstado);

        if ($actualizado) {
            // Notificación solo si el estado cambió realmente
            if (
                $estado_anterior !== $nuevoEstado &&
                in_array($nuevoEstado, ['Enviado', 'Entregado'])
            ) {
                $mensaje = "Pedido #{$id} ha sido marcado como: {$nuevoEstado}.";

                $this->notificacionModel->save([
                    'tipo_evento'   => 'pedido_actualizado',
                    'mensaje'       => $mensaje,
                    'referencia_id' => $id,
                ]);
            }

            session()->setFlashdata('success', "Estado del pedido #{$id} actualizado a {$nuevoEstado}.");
        } else {
            session()->setFlashdata('error', 'Error al actualizar el estado del pedido.');
        }

        return redirect()->back();
    }
}