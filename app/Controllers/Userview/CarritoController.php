<?php

namespace App\Controllers\Userview;

use App\Controllers\BaseController;
use App\Models\DiscoModel;
use App\Models\UsuarioModel;
use App\Models\TipoMembresiaModel;

class CarritoController extends BaseController
{
    protected $cart;
    protected $discoModel;
    protected $usuarioModel;
    protected $membresiaModel;

    public function __construct()
    {
        $this->cart = \Config\Services::cart();
        $this->discoModel = new DiscoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel();
        helper(['form', 'url']);
    }

    private function calcularTotales()
    {
        $subtotal = $this->cart->total();
        $descuento = 0.00;
        $costoEnvio = 0.00;

        $idUsuario = session()->get('id_usuario');

        if ($idUsuario) {
            try {
                $usuario = $this->usuarioModel->select('id_membresia')->find($idUsuario);

                if ($usuario && $usuario['id_membresia']) {
                    $reglas = $this->membresiaModel->getReglasMembresia($usuario['id_membresia']);

                    if ($reglas) {
                        $porcentajeDescuento = (float)($reglas['descuento_porcentaje'] ?? 0);
                        $descuento = $subtotal * $porcentajeDescuento;

                        $envioGratisMontoMinimo = (float)($reglas['envio_gratis_monto_minimo'] ?? 0);
                        $costoEnvioFijo = (float)($reglas['costo_envio_fijo'] ?? 0);

                        if (is_null($reglas['envio_gratis_monto_minimo']) || $subtotal >= $envioGratisMontoMinimo) {
                            $costoEnvio = 0.00;
                        } else {
                            $costoEnvio = $costoEnvioFijo;
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Error en calcularTotales: ' . $e->getMessage());
            }
        }

        $totalFinal = $subtotal - $descuento + $costoEnvio;

        return [
            'subtotal' => (float)$subtotal,
            'descuento' => (float)$descuento,
            'costo_envio' => (float)$costoEnvio,
            'total_final' => (float)$totalFinal
        ];
    }

    public function agregar()
    {
        if (!$this->request->isAJAX() || $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Método no permitido.']);
        }

        $id_disco = $this->request->getPost('id_disco');
        $qty = (int)($this->request->getPost('qty') ?? 1);

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
            'price'   => $disco['precio_venta'],
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
    }

    public function actualizar()
    {
        $rowid = $this->request->getPost('rowid');
        $qty = $this->request->getPost('qty');

        if (!$rowid || !is_numeric($qty) || $qty < 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Datos de actualización inválidos.']);
        }

        $data = [
            'rowid' => $rowid,
            'qty'   => $qty
        ];

        if ($qty == 0) {
            $this->cart->update(['rowid' => $rowid, 'qty' => 0]);
            $totales = $this->calcularTotales();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Producto eliminado del carrito.', 'totales' => $totales]);
        } else {
            $this->cart->update($data);
            $totales = $this->calcularTotales();
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Cantidad actualizada.',
                'new_total' => number_format($this->cart->total(), 2),
                'totales' => $totales
            ]);
        }
    }

    public function obtener()
    {
        $items = $this->cart->contents();
        $totales = $this->calcularTotales();

        return $this->response->setJSON([
            'status' => 'success',
            'items' => array_values($items),
            'totales' => $totales
        ]);
    }

    public function vaciar()
    {
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
    }

    public function checkout()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Acceso denegado.');
        }

        if ($this->cart->totalItems() == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El carrito está vacío.']);
        }

        $totales = $this->calcularTotales();

        $this->cart->destroy();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Compra finalizada con éxito. ¡Gracias!',
            'resumen' => $totales
        ]);
    }
}
