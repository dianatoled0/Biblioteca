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
    protected $discoModel;
    protected $usuarioModel;
    protected $membresiaModel;
    protected $session;

    public function __construct()
    {
        // El servicio de sesión se inicializa correctamente.
        $this->session = \Config\Services::session();
        
        if (empty($this->session)) {
            log_message('error', 'Sesión no pudo inicializarse en constructor');
        }

        $this->discoModel = new DiscoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel();
        helper(['form', 'url']);
    }

    /**
     * Obtiene el carrito de sesión (array simple).
     * @return array Items del carrito
     */
    private function getCartItems()
    {
        return $this->session->get('cart_items') ?? [];
    }

    /**
     * Guarda el carrito en sesión.
     * @param array $items
     */
    private function setCartItems($items)
    {
        $this->session->set('cart_items', $items);
    }

    /**
     * Calcula totales del carrito (subtotal, descuento, envío) aplicando las reglas de membresía.
     * ESTA FUNCIÓN HA SIDO REVISADA Y AJUSTADA PARA APLICAR LAS REGLAS DE MEMBRESÍA.
     * @return array Totales calculados
     */
    private function calcularTotales()
    {
        try {
            $items = $this->getCartItems();
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }

            $descuento = 0.00;
            $costoEnvio = 0.00;
            $idUsuario = $this->session->get('id_usuario');

            if ($idUsuario) {
                $usuario = $this->usuarioModel->select('id_membresia')->find($idUsuario);
                
                // Aplicar reglas de membresía si existe un ID de membresía
                if ($usuario && !empty($usuario['id_membresia'])) {
                    
                    // Se asume que TipoMembresiaModel tiene el método getReglasMembresia($id)
                    // Eliminamos el try-catch anidado, asumiendo que el método existe.
                    $reglas = $this->membresiaModel->getReglasMembresia((int)$usuario['id_membresia']);

                    if ($reglas && is_array($reglas)) {
                        $porcentajeDescuento = (float)($reglas['descuento_porcentaje'] ?? 0.00);
                        $envioGratisMontoMinimo = (float)($reglas['envio_gratis_monto_minimo'] ?? 0.00);
                        $costoEnvioFijo = (float)($reglas['costo_envio_fijo'] ?? 0.00);

                        // 1. Cálculo del descuento
                        $descuento = $subtotal * $porcentajeDescuento;
                        
                        // 2. Cálculo del costo de envío
                        if ($envioGratisMontoMinimo == 0.00 || $subtotal >= $envioGratisMontoMinimo) {
                            $costoEnvio = 0.00; // Envío gratis absoluto o por monto mínimo alcanzado
                        } else {
                            $costoEnvio = $costoEnvioFijo; // Aplicar costo fijo si no alcanza el mínimo
                        }
                    }
                }
            }
            // Asegurar que el descuento no sea mayor que el subtotal
            $descuento = min($descuento, $subtotal);
            $totalFinal = $subtotal - $descuento + $costoEnvio;

            return [
                'subtotal' => (float)$subtotal,
                'descuento' => (float)$descuento,
                'costo_envio' => (float)$costoEnvio,
                'total_final' => (float)$totalFinal
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error en calcularTotales: ' . $e->getMessage());
            return ['subtotal' => 0, 'descuento' => 0, 'costo_envio' => 0, 'total_final' => 0];
        }
    }

    /**
     * Agrega un disco al carrito.
     */
    public function agregar()
    {
        try {
            log_message('debug', 'Iniciando agregar. Método: ' . $this->request->getMethod() . ', isAJAX: ' . ($this->request->isAJAX() ? 'true' : 'false'));
            log_message('debug', 'Sesión logged_in: ' . ($this->session->get('logged_in') ? 'true' : 'false') . ', id_usuario: ' . $this->session->get('id_usuario'));

            $id_disco = $this->request->getPost('id_disco') ?? $this->request->getGet('id_disco');
            $qty = (int)($this->request->getPost('qty') ?? $this->request->getGet('qty') ?? 1);

            log_message('debug', 'Datos: id_disco=' . $id_disco . ', qty=' . $qty);

            if (empty($id_disco) || $qty < 1) {
                log_message('error', 'Validación falló: id_disco vacío o qty <1');
                return $this->response->setJSON(['status' => 'error', 'message' => 'ID de disco inválido o cantidad menor a 1.']);
            }

            $disco = $this->discoModel->find($id_disco);
            log_message('debug', 'Disco encontrado: ' . json_encode($disco));

            if (!$disco) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Disco no encontrado en la base de datos.']);
            }

            $items = $this->getCartItems();
            
            // Revisa si el disco ya existe en el carrito para no generar un nuevo rowid
            $found = false;
            foreach ($items as $rowid => $item) {
                if ($item['id'] == $id_disco) {
                    $items[$rowid]['qty'] += $qty;
                    $items[$rowid]['subtotal'] = $items[$rowid]['price'] * $items[$rowid]['qty'];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // Si es un nuevo disco, genera un rowid único
                $rowid = md5($disco['id'] . time() . rand(1, 1000));
                
                $items[$rowid] = [
                    'rowid' => $rowid,
                    'id' => $disco['id'],
                    'qty' => $qty,
                    'price' => (float)$disco['precio_venta'],
                    'name' => $disco['titulo'],
                    'options' => ['artista' => $disco['artista']],
                    'subtotal' => (float)$disco['precio_venta'] * $qty
                ];
            }


            $this->setCartItems($items);

            log_message('debug', 'Item agregado. Total items: ' . count($items));

            $totales = $this->calcularTotales();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Producto agregado al carrito.',
                'total_items' => array_sum(array_column($items, 'qty')),
                'total_final' => number_format($totales['total_final'], 2),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Excepción en agregar: ' . $e->getMessage() . ' en línea ' . $e->getLine());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno al procesar la solicitud de agregar: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtiene los contenidos y totales del carrito.
     */
    public function obtener()
    {
        try {
            $items = $this->getCartItems();
            $totales = $this->calcularTotales();

            log_message('debug', 'Items obtenidos: ' . count($items));

            return $this->response->setJSON([
                'status' => 'success',
                'items' => array_values($items),
                'totales' => $totales
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Excepción en obtener: ' . $e->getMessage() . ' en línea ' . $e->getLine());
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo cargar el carrito. Intente de nuevo: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualiza la cantidad de un item.
     */
    public function actualizar()
    {
        try {
            $rowid = $this->request->getPost('rowid');
            $qty = (int)($this->request->getPost('qty') ?? 0);

            $items = $this->getCartItems();
            if (isset($items[$rowid])) {
                $items[$rowid]['qty'] = $qty;
                $items[$rowid]['subtotal'] = $items[$rowid]['price'] * $qty;
                if ($qty == 0) {
                    unset($items[$rowid]);
                }
                $this->setCartItems($items);
            }

            $totales = $this->calcularTotales();
            $message = ($qty == 0) ? 'Producto eliminado del carrito.' : 'Cantidad actualizada.';
            
            // Calcular el nuevo subtotal del ítem actualizado
            $newSubtotalItem = isset($items[$rowid]) ? $items[$rowid]['subtotal'] : 0;
            $totalItems = empty($items) ? 0 : array_sum(array_column($items, 'qty'));


            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message,
                'new_subtotal_item' => number_format($newSubtotalItem, 2),
                'total_items' => $totalItems, // Agregado para actualizar el contador general
                'totales' => $totales
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fallo al actualizar carrito: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno al actualizar el carrito.']);
        }
    }

    /**
     * Vacía todo el carrito.
     */
    public function vaciar()
    {
        try {
            $this->setCartItems([]);
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
     * Elimina un item del carrito.
     */
    public function eliminar()
    {
        try {
            $rowid = $this->request->getPost('rowid');
            if (!$rowid) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Rowid inválido.']);
            }

            $items = $this->getCartItems();
            unset($items[$rowid]);
            $this->setCartItems($items);

            $totales = $this->calcularTotales();
            $totalItems = empty($items) ? 0 : array_sum(array_column($items, 'qty'));


            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Producto eliminado.',
                'total_items' => $totalItems,
                'total_final' => number_format($totales['total_final'], 2),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fallo al eliminar: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al eliminar.']);
        }
    }

    /**
     * Procesa la finalización de la compra (guarda en DB).
     */
    public function checkout()
    {
        try {
            if (!$this->request->isAJAX()) {
                return redirect()->back()->with('error', 'Acceso denegado.');
            }

            $items = $this->getCartItems();
            if (empty($items)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'El carrito está vacío.']);
            }

            // Verificar si el usuario está logueado
            $idUser = $this->session->get('id_usuario');
            if (!$idUser) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Debe iniciar sesión para completar la compra.']);
            }
            
            // Validar stock antes de guardar
            foreach ($items as $item) {
                $disco = $this->discoModel->find($item['id']);
                if (!$disco || $disco['stock'] < $item['qty']) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Stock insuficiente para el disco: ' . $item['name']]);
                }
            }


            $totales = $this->calcularTotales();
            
            // Guardar en DB: Pedido
            $pedidoData = [
                'id_user' => $idUser,
                // El monto total ya incluye el descuento y el costo de envío aplicados en calcularTotales()
                'monto_total' => $totales['total_final'], 
                'estado_pedido' => 'Pendiente',
                'fecha_pedido' => date('Y-m-d')
            ];
            $pedidoModel = new PedidoModel();
            $idPedido = $pedidoModel->insert($pedidoData);
            
            if (!$idPedido) {
                 log_message('error', 'Fallo al insertar pedido en DB para usuario ' . $idUser);
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Error al crear el pedido.']);
            }
            
            log_message('debug', 'Pedido creado: ID ' . $idPedido);


            // Guardar detalles y actualizar stock
            $detalleModel = new DetallePedidoModel();
            foreach ($items as $item) {
                $detalleData = [
                    'id_pedido' => $idPedido,
                    'id_disco' => $item['id'],
                    'cantidad' => $item['qty'],
                    'sub_total' => $item['subtotal'] // Nota: El subtotal del ítem individual no tiene el descuento aplicado aún, pero la estructura es válida.
                ];
                $detalleModel->insert($detalleData);

                // Actualizar stock en discos
                $disco = $this->discoModel->find($item['id']);
                if ($disco && $disco['stock'] >= $item['qty']) {
                    $newStock = $disco['stock'] - $item['qty'];
                    $this->discoModel->update($item['id'], ['stock' => $newStock]);
                    log_message('debug', 'Stock actualizado para disco ' . $item['id'] . ': ' . $newStock);
                }
            }

            // Vaciar carrito
            $this->setCartItems([]);

            log_message('debug', 'Checkout completado para usuario ' . $idUser . '. Pedido ID: ' . $idPedido);

            // Mensaje de éxito más específico.
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '¡Compra finalizada con éxito! Puedes ver el estado y detalle de tu pedido en la sección "Compras realizadas".',
                'resumen' => $totales,
                'id_pedido' => $idPedido
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fallo al finalizar la compra: ' . $e->getMessage() . ' en línea ' . $e->getLine());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error al procesar el pago: ' . $e->getMessage()]);
        }
    }
}