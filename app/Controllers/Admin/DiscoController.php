<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DiscoModel;
use App\Models\CategoriaModel;

class DiscoController extends BaseController
{
    protected $discoModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->discoModel = new DiscoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    // 📌 Listado de discos
    public function index()
    {
        $data['discos'] = $this->discoModel
            ->select('discos.*, categorias.nom_categoria')
            ->join('categorias', 'categorias.id = discos.id_categoria')
            ->findAll();

        return view('admin/discos/index', $data);
    }

    // 📌 Mostrar formulario de creación
    public function crear()
    {
        if ($this->request->getMethod() === 'post') {
            $datos = [
                'id_categoria'  => $this->request->getPost('id_categoria'),
                'titulo'        => $this->request->getPost('titulo'),
                'artista'       => $this->request->getPost('artista'),
                'precio_venta'  => $this->request->getPost('precio_venta'),
                'stock'         => $this->request->getPost('stock'),
            ];

            if ($this->discoModel->insert($datos)) {
                return redirect()->to('admin/discos')->with('success', 'Disco creado correctamente.');
            } else {
                return redirect()->back()->with('error', 'No se pudo crear el disco.');
            }
        }

        $data['categorias'] = $this->categoriaModel->findAll();
        return view('admin/discos/crear', $data);
    }

    // 📌 Mostrar formulario de edición
    public function editar($id)
    {
        $disco = $this->discoModel->find($id);
        if (!$disco) {
            return redirect()->to('admin/discos')->with('error', 'Disco no encontrado.');
        }

        if ($this->request->getMethod() === 'post') {
            $datos = [
                'id_categoria'  => $this->request->getPost('id_categoria'),
                'titulo'        => $this->request->getPost('titulo'),
                'artista'       => $this->request->getPost('artista'),
                'precio_venta'  => $this->request->getPost('precio_venta'),
                'stock'         => $this->request->getPost('stock'),
            ];

            if ($this->discoModel->update($id, $datos)) {
                return redirect()->to('admin/discos')->with('success', 'Disco actualizado correctamente.');
            } else {
                return redirect()->back()->with('error', 'No se pudo actualizar el disco.');
            }
        }

        $data['disco'] = $disco;
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('admin/discos/editar', $data);
    }

    // 📌 Eliminar un disco
    public function eliminar($id)
    {
        if ($this->discoModel->delete($id)) {
            return redirect()->to('admin/discos')->with('success', 'Disco eliminado correctamente.');
        } else {
            return redirect()->to('admin/discos')->with('error', 'No se pudo eliminar el disco.');
        }
    }
}
