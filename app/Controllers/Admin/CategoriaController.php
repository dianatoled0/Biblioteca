<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class CategoriaController extends BaseController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    // Mostrar todas las categorías
    public function index()
    {
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('admin/categorias/index', $data);
    }

    // Mostrar formulario de creación
    public function crear()
    {
        return view('admin/categorias/crear');
    }

    // Guardar nueva categoría
    public function guardar()
    {
        $this->categoriaModel->save([
            'nom_categoria' => $this->request->getPost('nom_categoria'),
        ]);

        return redirect()->to('/admin/categorias')->with('mensaje', 'Categoría creada exitosamente');
    }

    // Mostrar formulario de edición
    public function editar($id)
    {
        $data['categoria'] = $this->categoriaModel->find($id);

        if (!$data['categoria']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoría no encontrada');
        }

        return view('admin/categorias/editar', $data);
    }

    // Actualizar categoría
    public function actualizar($id)
    {
        $this->categoriaModel->update($id, [
            'nom_categoria' => $this->request->getPost('nom_categoria'),
        ]);

        return redirect()->to('/admin/categorias')->with('mensaje', 'Categoría actualizada correctamente');
    }

    // Eliminar categoría
    public function eliminar($id)
    {
        $this->categoriaModel->delete($id);

        return redirect()->to('/admin/categorias')->with('mensaje', 'Categoría eliminada correctamente');
    }
}
