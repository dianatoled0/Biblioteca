<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\IngresoModel;
use App\Models\DiscoModel;

class IngresoController extends BaseController
{
    protected $ingresoModel;
    protected $discoModel;

    public function __construct()
    {
        $this->ingresoModel = new IngresoModel();
        $this->discoModel = new DiscoModel();
    }

    /**
     * Listado de ingresos
     */
    public function index()
    {
        $data['ingresos'] = $this->ingresoModel->getIngresosConDisco();

        return view('admin/ingresos/index', $data);
    }

    /**
     * Formulario para crear ingreso
     */
    public function crear()
    {
        $data['discos'] = $this->discoModel->findAll();
        return view('admin/ingresos/crear', $data);
    }

    /**
     * Guardar un nuevo ingreso
     */
    public function guardar()
    {
        $id_disco = $this->request->getPost('id_disco');
        $cantidad = $this->request->getPost('cantidad');

        if (!$id_disco || !$cantidad) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        }

        $this->ingresoModel->insert([
            'id_disco' => $id_disco,
            'cantidad' => $cantidad,
            'fecha_ingreso' => date('Y-m-d')
        ]);

        // actualizar stock del disco
        $disco = $this->discoModel->find($id_disco);
        if ($disco) {
            $nuevoStock = $disco['stock'] + (int)$cantidad;
            $this->discoModel->update($id_disco, ['stock' => $nuevoStock]);
        }

        return redirect()->to(base_url('admin/ingresos'))->with('success', 'Ingreso registrado correctamente.');
    }
}
