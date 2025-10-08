<?php

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;
use App\Models\NotificacionModel;

class CategoriaController extends BaseController
{
    protected $categoriaModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
        $this->notificacionModel = new NotificacionModel();
        helper(['form', 'url']);
    }

    /**
     * Muestra el listado de categorías.
     */
    public function index()
    {
        $data = [
            'categorias' => $this->categoriaModel->findAll(),
        ];

        return view('admin/categorias/index', $data);
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function crear()
    {
        return view('admin/categorias/form');
    }

    /**
     * Intenta guardar una nueva categoría en la DB.
     */
    public function guardar()
    {
        // Validar los datos del formulario
        if (! $this->validate($this->categoriaModel->validationRules ?? [
            'nom_categoria' => 'required|min_length[3]|max_length[50]'
        ])) {
            return view('admin/categorias/form', [
                'validation' => $this->validator,
            ]);
        }

        $nom_categoria = $this->request->getPost('nom_categoria');

        // Guardar en la base de datos
        $guardado = $this->categoriaModel->save([
            'nom_categoria' => $nom_categoria,
        ]);

        if ($guardado) {
            $new_id = $this->categoriaModel->insertID();

            // --- NOTIFICACIÓN: NUEVA CATEGORÍA AGREGADA ---
            $this->notificacionModel->save([
                'tipo_evento'   => 'nueva_categoria',
                'mensaje'       => 'Nueva categoría agregada: ' . esc($nom_categoria),
                'referencia_id' => $new_id,
            ]);
            // ------------------------------------------------

            session()->setFlashdata('success', 'Categoría creada exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al guardar la categoría en la base de datos.');
        }

        // Redirección ya estaba correcta, solo usa setFlashdata
        return redirect()->to(base_url('admin/categorias'));
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     */
    public function editar($id = null)
    {
        $categoria = $this->categoriaModel->find($id);

        if (! $categoria) {
            session()->setFlashdata('error', 'Categoría no encontrada.');
            return redirect()->to(base_url('admin/categorias'));
        }

        return view('admin/categorias/form', [
            'categoria' => $categoria,
        ]);
    }

    /**
     * Intenta actualizar una categoría existente.
     */
    public function actualizar($id = null)
    {
        // Validar los datos
        if (! $this->validate($this->categoriaModel->validationRules ?? [
            'nom_categoria' => 'required|min_length[3]|max_length[50]'
        ])) {
            return view('admin/categorias/form', [
                'validation' => $this->validator,
                'categoria'  => $this->categoriaModel->find($id),
            ]);
        }

        // Actualizar registro
        $this->categoriaModel->update($id, [
            'nom_categoria' => $this->request->getPost('nom_categoria'),
        ]);

        // CORRECCIÓN: Usar setFlashdata y redirección simple
        session()->setFlashdata('success', 'Categoría actualizada exitosamente.');
        return redirect()->to(base_url('admin/categorias'));
    }

    /**
     * Elimina una categoría.
     */
    public function eliminar($id = null)
    {
        if ($this->categoriaModel->delete($id)) {
            session()->setFlashdata('success', 'Categoría eliminada exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al intentar eliminar la categoría.');
        }

        // Redirección ya estaba correcta
        return redirect()->to(base_url('admin/categorias'));
    }
}