<?php

namespace App\Controllers\Userview;

use App\Controllers\BaseController;
use App\Models\DiscoModel;
use App\Models\UsuarioModel;
use App\Models\TipoMembresiaModel;
use App\Models\PedidoModel;
use App\Models\DetallePedidoModel;

class CarritoController extends BaseController
{
    protected $cart;
    protected $discoModel;
    protected $usuarioModel;
    protected $membresiaModel;

    public function __construct()
    {
        // Inicializa sesiones explícitamente si no están activas
        if (session_status() === PHP_SESSION_NONE) {
            \Config\Services::session();
        }

        // Inicializa el carrito con fallback si falla
        try {
            $this->cart = \Config\Services::cart();
            if ($this->cart === null) {
                throw new \Exception('Cart service null');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error inicializando Cart: ' . $e->getMessage());
            // Fallback: Inicializa manualmente si es necesario
            $this->cart = new \CodeIgniter\Cart\Cart();
        }

        $this->discoModel = new DiscoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel();
        helper(['form', 'url']);
    }

    /**
     * Calcula el subtotal, el descuento por membresía y el costo de envío.
     * Esta función está protegida contra valores NULL y errores de base de datos.
     * @return array Array con los totales calculados.
     */
    private function calcularTotales()
    {
        $subtotal = $this->cart->total();
        $descuento = 0.00;
        $costoEnvio = 0.00;
        $idUsuario = session()->get('id_usuario');

        // Solo intentar aplicar beneficios si el usuario está logueado
        if ($idUsuario) {
            try {
                // 1. Obtener la información del usuario
                $usuario = $this->usuarioModel->select('id_membresia')->find($idUsuario);

                // 2. Verificar si el usuario y la membresía son válidos
                if ($usuario && !empty($usuario['id_membresia'])) {
                    
                    // 3. Obtener las reglas de la membresía. Castear a int por seguridad.
                    $reglas = $this->membresiaModel->getReglasMembresia((int)$usuario['id_membresia']); 

                    if ($reglas && is_array($reglas)) {
                        // Asegurar que todos los valores son flotantes (float) y usar 0.00 si son NULL o no están.
                        $porcentajeDescuento = (float)($reglas['descuento_porcentaje'] ?? 0.00);
                        $envioGratisMontoMinimo = (float)($reglas['envio_gratis_monto_minimo'] ?? 0.00);
                        $costoEnvioFijo = (float)($reglas['costo_envio_fijo'] ?? 0.00);

                        // --- Aplicar Descuento ---
                        $descuento = $subtotal * $porcentajeDescuento;

                        // --- Aplicar Reglas de Envío ---
                        if ($envioGratisMontoMinimo == 0.00 || $subtotal >= $envioGratisMontoMinimo) {
                            $costoEnvio = 0.00; // Envío gratis
                        } else {
                            $costoEnvio = $costoEnvioFijo; // Aplicar costo fijo
                        }
                    } 
                }
            } catch (\Exception $e) {
                // Captura CUALQUIER error crítico y lo registra
                log_message('error', 'Fallo CRÍTICO en CarritoController::calcularTotales: ' . $e->getMessage());
                $descuento = 0.00;
                $costoEnvio = 0.00;
            }
        }

        // Asegurar que el descuento no exceda el subtotal
        $descuento = min($descuento, $subtotal);
        $totalFinal = $subtotal - $descuento + $costoEnvio;

        return [
            'subtotal' => (float)$subtotal,
            'descuento' => (float)$descuento,
            'costo_envio' => (float)$costoEnvio,
            'total_final' => (float)$totalFinal
        ];
    }

    /**
     * Agrega un disco al carrito a través de AJAX.
     * @return \CodeIgniter\HTTP\Response
     */
    public function agregar()
    {
        try {
            // Check relajado para debug - verifica si es POST o AJAX, pero permite GET temporal
            log_message('debug', 'Método recibido en agregar: ' . $this->request->getMethod() . ', isAJAX: ' . ($this->request->isAJAX() ? 'true' : 'false'));

            // Usa getPostGet para aceptar tanto POST como GET durante debug
            $id_disco = $this->request->getPostGet('id_disco');
            $qty = (int)($this->request->getPostGet('qty') ?? 1);

            log_message('debug', 'Datos recibidos en agregar: id_disco=' . $id_disco . ', qty=' . $qty);

            if (empty($id_disco) || $qty < 1) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ID de disco inválido o cantidad menor a 1.']);
            }

            $disco = $this->discoModel->find($id_disco);

            if (!$disco) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Disco no encontrado en la base de datos.']);
            }

            $data = [
                'id'      => $disco['id'],
                'qty'     => $qty,
                'price'   => (float)$disco['precio_venta'],
                'name'    => $disco['titulo'],
                'options' => ['artista' => $disco['artista']]
            ];

            $this->cart->insert($data);

            $totales = $this->calcularTotales();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Producto agregado al carrito.',
                'total_items' => $this->cart->totalItems(),
                'total_final' => number_format($totales['total_final'], 2),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fallo al agregar producto al carrito (ENDPOINT): ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno al procesar la solicitud de agregar.']);
        }
    }

    /**
     * Actualiza la cantidad de un item en el carrito.
     * @return \CodeIgniter\HTTP\Response
     */
    public function actualizar()
    {
        try {
            $rowid = $this->request->getPost('rowid');
            $qty = $this->request->getPost('qty');

            if (!$rowid || !is_numeric($qty) || $qty < 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Datos de actualización inválidos.']);
            }
            
            $data = [
                'rowid' => $rowid,
                'qty'   => (int)$qty
            ];

            $this->cart->update($data);
            $totales = $this->calcularTotales();

            $message = ((int)$qty == 0) ? 'Producto eliminado del carrito.' : 'Cantidad actualizada.';
            $item_data = $this->cart->getItem($rowid);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message,
                'new_subtotal_item' => number_format($item_data['subtotal'] ?? 0, 2), 
                'totales' => $totales
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fallo al actualizar carrito: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno al actualizar el carrito.']);
        }
    }

    /**
     * Obtiene los contenidos y totales del carrito.
     * @return \CodeIgniter\HTTP\Response
     */
    public function obtener()
    {
        try {
            $items = $this->cart->contents();
            $totales = $this->calcularTotales();

            return $this->response->setJSON([
                'status' => 'success',
                'items' => array_values($items),
                'totales' => $totales
            ]);
        } catch (\Exception $e) {
            // Manejador de errores para el endpoint 'obtener'
            log_message('error', 'Fallo al obtener contenido del carrito: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo cargar el carrito. Intente de nuevo.']);
        }
    }

    /**
     * Vacía todo el carrito.
     * @return \CodeIgniter\HTTP\Response
     */
    public function vaciar()
    {
        try {
            if (!$this->request->isAJAX()) {
                return redirect()->back()->with('success', 'El carrito ha sido vaciado.');
            }

            $this->cart->destroy();
            $totales = $this->calcularTotales();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'El carrito ha sido vaciado.',
                'total_items' => 0,
                'total_final' => number_format($totales['total_final'], 2),
            ]);
        } catch (\Exception $e) {
             log_message('error', 'Fallo al vaciar carrito: ' . $e->getMessage());
             return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error al vaciar el carrito.']);
        }
    }

    /**
     * Elimina un item del carrito a través de AJAX.
     * @return \CodeIgniter\HTTP\Response
     */
    public function eliminar()
    {
        try {
            $rowid = $this->request->getPost('rowid');
            if (!$rowid) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Rowid inválido.']);
            }
            $this->cart->remove($rowid);
            $totales = $this->calcularTotales();
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Producto eliminado.',
                'total_items' => $this->cart->totalItems(),
                'total_final' => number_format($totales['total_final'], 2),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fallo al eliminar: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al eliminar.']);
        }
    }

    /**
     * Procesa la finalización de la compra.
     * @return \CodeIgniter\HTTP\Response
     */
    public function checkout()
    {
        try {
            if (!$this->request->isAJAX()) {
                return redirect()->back()->with('error', 'Acceso denegado.');
            }

            if ($this->cart->totalItems() == 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'El carrito está vacío.']);
            }

            $totales = $this->calcularTotales();

            // Lógica de guardar el pedido en la DB
            $idUser = session()->get('id_usuario');
            $pedidoData = [
                'id_user' => $idUser,
                'monto_total' => $totales['total_final'],
                'estado_pedido' => 'Pendiente',
                'fecha_pedido' => date('Y-m-d')
            ];
            $pedidoModel = new PedidoModel();
            $idPedido = $pedidoModel->insert($pedidoData);

            foreach ($this->cart->contents() as $item) {
                $detalleData = [
                    'id_pedido' => $idPedido,
                    'id_disco' => $item['id'],
                    'cantidad' => $item['qty'],
                    'sub_total' => $item['subtotal']
                ];
                $detalleModel = new DetallePedidoModel();
                $detalleModel->insert($detalleData);
            }

            // Vaciar el carrito después de "finalizar" la compra
            $this->cart->destroy();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Compra finalizada con éxito. ¡Gracias!',
                'resumen' => $totales
            ]);
        } catch (\Exception $e) {
             log_message('error', 'Fallo al finalizar la compra: ' . $e->getMessage());
             return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error al procesar el pago.']);
        }
    }
}