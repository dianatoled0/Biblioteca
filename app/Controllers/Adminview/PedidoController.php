<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReciboModel;

class ReciboController extends BaseController
{
    protected $reciboModel;

    public function __construct()
    {
        $this->reciboModel = new ReciboModel();
    }

    /**
     * Listado de recibos
     */
    public function index()
    {
        $data['recibos'] = $this->reciboModel->getRecibosConUsuario();

        return view('admin/recibos/index', $data);
    }

    /**
     * Detalle de un recibo con sus discos
     */
    public function detalle($id)
    {
        $recibo = $this->reciboModel->getReciboConDetalles($id);

        if (!$recibo) {
            return redirect()->to(base_url('admin/recibos'))
                ->with('error', 'El recibo no existe o fue eliminado.');
        }

        $data['recibo'] = $recibo;

        return view('admin/recibos/detalle', $data);
    }
}

