<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TipoPagoModel;

class TiposPagoController extends BaseController
{
    protected $tipoPagoModel;

    public function __construct()
    {
        $this->tipoPagoModel = new TipoPagoModel();
    }

    public function index()
    {
        $data['tipos_pago'] = $this->tipoPagoModel->findAll();
        return view('admin/tipos_pago/index', $data);
    }

    public function crear()
    {
        if ($this->request->getMethod() === 'post') {
            $this->tipoPagoModel->save([
                'nombre'      => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion')
            ]);
            return redirect()->to('/admin/tipos-pago')->with('success', 'Tipo de pago creado correctamente.');
        }
        return view('admin/tipos_pago/crear');
    }

    public function editar($id)
    {
        $tipoPago = $this->tipoPagoModel->find($id);

        if ($this->request->getMethod() === 'post') {
            $this->tipoPagoModel->update($id, [
                'nombre'      => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion')
            ]);
            return redirect()->to('/admin/tipos-pago')->with('success', 'Tipo de pago actualizado correctamente.');
        }

        return view('admin/tipos_pago/editar', ['tipoPago' => $tipoPago]);
    }

    public function eliminar($id)
    {
        $this->tipoPagoModel->delete($id);
        return redirect()->to('/admin/tipos-pago')->with('success', 'Tipo de pago eliminado correctamente.');
    }
}
