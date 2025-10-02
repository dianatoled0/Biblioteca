<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuariosController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $data['usuarios'] = $this->usuarioModel->findAll();
        return view('admin/usuarios/index', $data);
    }

    public function crear()
    {
        if ($this->request->getMethod() === 'post') {
            $this->usuarioModel->crearUsuario([
                'nombre'   => $this->request->getPost('nombre'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'rol'      => $this->request->getPost('rol')
            ]);
            return redirect()->to('/admin/usuarios')->with('success', 'Usuario creado correctamente.');
        }
        return view('admin/usuarios/crear');
    }

    public function editar($id)
    {
        $usuario = $this->usuarioModel->find($id);

        if ($this->request->getMethod() === 'post') {
            $this->usuarioModel->actualizarUsuario($id, [
                'nombre'   => $this->request->getPost('nombre'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'rol'      => $this->request->getPost('rol')
            ]);
            return redirect()->to('/admin/usuarios')->with('success', 'Usuario actualizado correctamente.');
        }

        return view('admin/usuarios/editar', ['usuario' => $usuario]);
    }

    public function eliminar($id)
    {
        $this->usuarioModel->delete($id);
        return redirect()->to('/admin/usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
}

