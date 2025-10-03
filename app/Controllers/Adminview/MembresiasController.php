<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MembresiaModel;

class MembresiasController extends BaseController
{
    protected $membresiaModel;

    public function __construct()
    {
        $this->membresiaModel = new MembresiaModel();
    }

    public function index()
    {
        $data['membresias'] = $this->membresiaModel->findAll();
        return view('admin/membresias/index', $data);
    }

    public function crear()
    {
        if ($this->request->getMethod() === 'post') {
            $this->membresiaModel->save([
                'nombre'         => $this->request->getPost('nombre'),
                'precio'         => $this->request->getPost('precio'),
                'duracion_meses' => $this->request->getPost('duracion_meses')
            ]);
            return redirect()->to('/admin/membresias')->with('success', 'Membresía creada correctamente.');
        }
        return view('admin/membresias/crear');
    }

    public function editar($id)
    {
        $membresia = $this->membresiaModel->find($id);

        if ($this->request->getMethod() === 'post') {
            $this->membresiaModel->update($id, [
                'nombre'         => $this->request->getPost('nombre'),
                'precio'         => $this->request->getPost('precio'),
                'duracion_meses' => $this->request->getPost('duracion_meses')
            ]);
            return redirect()->to('/admin/membresias')->with('success', 'Membresía actualizada correctamente.');
        }

        return view('admin/membresias/editar', ['membresia' => $membresia]);
    }

    public function eliminar($id)
    {
        $this->membresiaModel->delete($id);
        return redirect()->to('/admin/membresias')->with('success', 'Membresía eliminada correctamente.');
    }
}
