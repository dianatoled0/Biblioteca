<?php

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\DiscoModel;
use App\Models\CategoriaModel;
use App\Models\NotificacionModel;

class DiscoController extends BaseController
{
    protected $discoModel;
    protected $categoriaModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->discoModel = new DiscoModel();
        $this->categoriaModel = new CategoriaModel();
        $this->notificacionModel = new NotificacionModel();
        helper(['form', 'url']);
    }

    /**
     * Lista todos los discos.
     */
    public function index()
    {
        $data = [
            'discos' => $this->discoModel->findAll(),
        ];

        return view('admin/discos/index', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo disco.
     */
    public function crear()
    {
        $data = [
            'categorias' => $this->categoriaModel->findAll(),
        ];

        return view('admin/discos/form', $data);
    }

    /**
     * Guarda un nuevo disco en la base de datos.
     */
    public function guardar()
    {
        $input = $this->request->getPost();

        $data_to_validate_and_save = [
            'titulo'        => $input['titulo'] ?? null,
            'artista'       => $input['artista'] ?? null,
            'precio_venta'  => $input['precio'] ?? null,
            'stock'         => $input['stock'] ?? null,
            'id_categoria'  => $input['categoria_id'] ?? null,
        ];

        $validation = \Config\Services::validation();
        $validation->setRules(
            $this->discoModel->validationRules ?? [
                'titulo'       => 'required|min_length[3]|max_length[100]',
                'artista'      => 'required|min_length[3]|max_length[100]',
                'precio_venta' => 'required|numeric',
                'stock'        => 'required|integer',
                'id_categoria' => 'required|integer',
            ],
            $this->discoModel->validationMessages ?? []
        );

        if (! $validation->run($data_to_validate_and_save)) {
            return view('admin/discos/form', [
                'validation' => $validation,
                'disco'      => (object) $data_to_validate_and_save,
                'categorias' => $this->categoriaModel->findAll(),
            ]);
        }

        $saved = $this->discoModel->save($data_to_validate_and_save);

        if ($saved) {
            $new_id = $this->discoModel->insertID();

            // --- NOTIFICACIÓN: NUEVO DISCO INGRESADO ---
            $this->notificacionModel->save([
                'tipo_evento'   => 'disco_ingresado',
                'mensaje'       => 'Nuevo disco añadido al catálogo: ' .
                                        esc($data_to_validate_and_save['titulo']) .
                                        ' de ' . esc($data_to_validate_and_save['artista']),
                'referencia_id' => $new_id,
            ]);
            // --------------------------------------------

            session()->setFlashdata('success', 'Disco creado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al guardar el disco en la base de datos.');
            // CORRECCIÓN: Si hay error, se redirige con input, pero el flashdata ya está seteado.
            return redirect()->to(base_url('admin/discos/crear'))->withInput();
        }

        // Redirección ya estaba correcta
        return redirect()->to(base_url('admin/discos'));
    }

    /**
     * Muestra el formulario para editar un disco existente.
     */
    public function editar($id = null)
    {
        $disco = $this->discoModel->find($id);

        if (! $disco) {
            session()->setFlashdata('error', 'Disco no encontrado.');
            return redirect()->to(base_url('admin/discos'));
        }

        $data = [
            'disco'      => (object) $disco,
            'categorias' => $this->categoriaModel->findAll(),
        ];

        return view('admin/discos/form', $data);
    }

    /**
     * Actualiza los datos de un disco existente.
     */
    public function actualizar($id = null)
    {
        if (empty($id) || ! $this->discoModel->find($id)) {
            session()->setFlashdata('error', 'Error de actualización: Disco ID no válido o inexistente.');
            return redirect()->to(base_url('admin/discos'));
        }

        $input = $this->request->getPost();

        $data_to_validate_and_save = [
            'titulo'        => $input['titulo'] ?? null,
            'artista'       => $input['artista'] ?? null,
            'precio_venta'  => $input['precio'] ?? null,
            'stock'         => $input['stock'] ?? null,
            'id_categoria'  => $input['categoria_id'] ?? null,
        ];

        $validation = \Config\Services::validation();
        $validation->setRules(
            $this->discoModel->validationRules ?? [
                'titulo'       => 'required|min_length[3]|max_length[100]',
                'artista'      => 'required|min_length[3]|max_length[100]',
                'precio_venta' => 'required|numeric',
                'stock'        => 'required|integer',
                'id_categoria' => 'required|integer',
            ],
            $this->discoModel->validationMessages ?? []
        );

        if (! $validation->run($data_to_validate_and_save)) {
            $disco_original = $this->discoModel->find($id);
            $data_for_form = $disco_original
                ? array_merge($disco_original, $data_to_validate_and_save)
                : $data_to_validate_and_save;

            return view('admin/discos/form', [
                'validation' => $validation,
                'disco'      => (object) $data_for_form,
                'categorias' => $this->categoriaModel->findAll(),
            ]);
        }

        $this->discoModel->update($id, $data_to_validate_and_save);

        // Redirección ya estaba correcta
        session()->setFlashdata('success', 'Disco actualizado exitosamente.');
        return redirect()->to(base_url('admin/discos'));
    }

    /**
     * Elimina un disco de la base de datos.
     */
    public function eliminar($id = null)
    {
        if ($this->discoModel->delete($id)) {
            session()->setFlashdata('success', 'Disco eliminado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al intentar eliminar el disco.');
        }

        // Redirección ya estaba correcta
        return redirect()->to(base_url('admin/discos'));
    }
}
