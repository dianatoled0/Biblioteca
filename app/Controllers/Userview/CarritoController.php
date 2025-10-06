<?php namespace App\Controllers\Userview; // <-- ¡Namespace Correcto!

use App\Controllers\BaseController;
use App\Models\DiscoModel;
use App\Models\PedidoModel;
use App\Models\DetallePedidoModel; 

class CarritoController extends BaseController
{
    protected $discoModel;
    protected $pedidoModel;
    protected $detallePedidoModel;

    public function __construct()
    {
        $this->discoModel = new DiscoModel();
        $this->pedidoModel = new PedidoModel();
        $this->detallePedidoModel = new DetallePedidoModel(); 
        helper('array');
    }

    // El resto de los métodos (agregar, obtener, vaciar) son los mismos...

    /**
     * Endpoint AJAX: Procesa la compra final (Checkout).
     * Ruta: POST /usuario/carrito/checkout
     * * 🚨 Implementación ajustada a tus campos de DB: id_user, monto_total, estado_pedido.
     */
    public function checkout()
    {
        $session = session();
        $carrito = $session->get('carrito') ?? [];

        if (empty($carrito) || !$session->get('id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Carrito vacío o usuario no logueado.']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Verificar Stock y calcular Total (Lógica de seguridad)
            $total = 0;
            $stockOk = true;
            foreach ($carrito as $id => $item) {
                $disco = $this->discoModel->find($id);
                if (!$disco || $disco['stock'] < $item['cantidad']) {
                    $stockOk = false;
                    throw new \Exception("Stock insuficiente para: " . $item['titulo']);
                }
                $total += $item['precio_venta'] * $item['cantidad'];
            }
            
            // 2. Insertar Pedido
            $pedidoData = [
                'id_user' => $session->get('id'),      // TU CAMPO DE DB: id_user
                'monto_total' => $total,               // TU CAMPO DE DB: monto_total
                'fecha_pedido' => date('Y-m-d H:i:s'), // Campo de fecha
                'estado_pedido' => 'Pagado'            // TU CAMPO DE DB: estado_pedido
            ];

            $this->pedidoModel->insert($pedidoData);
            $idPedido = $this->pedidoModel->insertID();

            // 3. Insertar Detalle y Actualizar Stock
            foreach ($carrito as $id => $item) {
                $this->detallePedidoModel->insert([
                    'id_pedido' => $idPedido, 
                    'id_disco' => $id, 
                    'cantidad' => $item['cantidad'], 
                    'precio_unitario' => $item['precio_venta']
                ]);
                $this->discoModel->updateStock($id, $item['cantidad']); // Reduce el stock
            }

            $db->transComplete(); // Si todo va bien, commit
            $session->remove('carrito');

            return $this->response->setJSON(['status' => 'success', 'message' => '¡Compra finalizada!', 'id_pedido' => $idPedido, 'count' => 0]);

        } catch (\Exception $e) {
            $db->transRollback(); // Si algo falla, rollback
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    // Aquí irían tus métodos agregar() y obtener()
    
    /**
     * Endpoint AJAX: Agrega un disco al carrito (sesión).
     */
    public function agregar()
    {
        $session = session();
        $discoId = $this->request->getPost('id');
        $cantidad = (int)$this->request->getPost('cantidad') ?: 1;

        if (!$discoId || $cantidad <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Datos inválidos.']);
        }

        $disco = $this->discoModel->find($discoId);

        if (!$disco) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El disco no existe.']);
        }

        $carrito = $session->get('carrito') ?? [];
        
        $nuevaCantidad = ($carrito[$discoId]['cantidad'] ?? 0) + $cantidad;

        if ($disco['stock'] < $nuevaCantidad) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Stock insuficiente para ' . esc($disco['titulo']) . '. Stock disponible: ' . $disco['stock']]);
        }
        
        $carrito[$discoId] = [
            'id' => $discoId,
            'titulo' => $disco['titulo'],
            'precio_venta' => (float)$disco['precio_venta'],
            'cantidad' => $nuevaCantidad
        ];

        $session->set('carrito', $carrito);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => esc($disco['titulo']) . ' agregado al carrito.',
            'count' => array_sum(array_column($carrito, 'cantidad'))
        ]);
    }

    /**
     * Endpoint AJAX: Devuelve el contenido actual del carrito.
     */
    public function obtener()
    {
        $carrito = session()->get('carrito') ?? [];
        $total = 0;
        $count = 0;

        foreach ($carrito as $item) {
            $subtotal = $item['precio_venta'] * $item['cantidad'];
            $total += $subtotal;
            $count += $item['cantidad'];
        }

        return $this->response->setJSON([
            'carrito' => $carrito,
            'total' => number_format($total, 2),
            'count' => $count
        ]);
    }

    /**
     * Endpoint AJAX: Vacía completamente el carrito.
     */
    public function vaciar()
    {
        session()->remove('carrito');
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Carrito vaciado.',
            'count' => 0
        ]);
    }
} 