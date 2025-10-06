<?php namespace App\Controllers\Adminview;

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
        helper(['form', 'url']); 
    }

    public function index()
    {
        $data = [
            'discos' => $this->discoModel->findAll()
        ];
        return view('admin/discos/index', $data);
    }

    public function crear()
    {
        $data = [
            'categorias' => $this->categoriaModel->findAll() 
        ];
        return view('admin/discos/form', $data);
    }
    
    public function guardar()
    {
        $input = $this->request->getPost();
        
        $data_to_validate_and_save = [
            'titulo'        => $input['titulo'] ?? null,
            'artista'       => $input['artista'] ?? null,
            // Importante: Mapeamos 'precio' del formulario a 'precio_venta' de la DB/Modelo
            'precio_venta'  => $input['precio'] ?? null, 
            'stock'         => $input['stock'] ?? null,
            'id_categoria'  => $input['categoria_id'] ?? null
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($this->discoModel->validationRules, $this->discoModel->validationMessages);
        
        if (! $validation->run($data_to_validate_and_save))
        {
            return view('admin/discos/form', [
                'validation' => $validation,
                // Siempre pasamos los datos como objeto
                'disco'      => (object)$data_to_validate_and_save, 
                'categorias' => $this->categoriaModel->findAll()
            ]);
        }
        
        $saved = $this->discoModel->save($data_to_validate_and_save);

        if ($saved) {
            session()->setFlashdata('success', 'Disco creado exitosamente.');
        } else {
            $error_message = 'ERROR CRÍTICO: El disco no se pudo guardar. La base de datos rechazó la inserción.';
            session()->setFlashdata('error', $error_message);
            return redirect()->to(base_url('admin/discos/crear'))->withInput();
        }
        
        return redirect()->to(base_url('admin/discos'));
    }

    public function editar($id = null)
    {
        $disco = $this->discoModel->find($id);

        if (!$disco) {
            session()->setFlashdata('error', 'Disco no encontrado.');
            return redirect()->to(base_url('admin/discos'));
        }
        
        $data = [
            // **CORRECCIÓN CLAVE:** Convertimos el array a objeto. 
            // Esto asegura que la vista acceda a los datos como $disco->id en lugar de $disco['id'].
            'disco' => (object)$disco, 
            'categorias' => $this->categoriaModel->findAll()
        ];

        return view('admin/discos/form', $data);
    }

    public function actualizar($id = null)
    {
        // 1. Verificamos que el ID sea válido
        if (empty($id) || !$this->discoModel->find($id)) {
             session()->setFlashdata('error', 'Error de actualización: Disco ID no válido o inexistente.');
             return redirect()->to(base_url('admin/discos'));
        }

        $input = $this->request->getPost();
        
        // Mapeo de datos para la validación de actualización
        $data_to_validate_and_save = [
            'titulo'        => $input['titulo'] ?? null,
            'artista'       => $input['artista'] ?? null,
            'precio_venta'  => $input['precio'] ?? null,
            'stock'         => $input['stock'] ?? null,
            'id_categoria'  => $input['categoria_id'] ?? null
        ];
        
        $validation = \Config\Services::validation();
        $validation->setRules($this->discoModel->validationRules, $this->discoModel->validationMessages);
        
        if (! $validation->run($data_to_validate_and_save))
        {
            // Si falla, necesitamos los datos que el usuario ingresó + el ID original
            $disco_original = $this->discoModel->find($id);
            if ($disco_original) {
                 // Fusionamos los datos POST (para set_value) con el ID original
                 $data_for_form = array_merge($disco_original, $data_to_validate_and_save);
            } else {
                 $data_for_form = $data_to_validate_and_save;
            }

            return view('admin/discos/form', [
                'validation' => $validation,
                'disco'      => (object)$data_for_form, // Lo pasamos como objeto
                'categorias' => $this->categoriaModel->findAll()
            ]);
        }
        
        // Realizamos la actualización usando el ID de la URL
        $this->discoModel->update($id, $data_to_validate_and_save);

        session()->setFlashdata('success', 'Disco actualizado exitosamente.');
        return redirect()->to(base_url('admin/discos'));
    }

    public function eliminar($id = null)
    {
        if ($this->discoModel->delete($id)) {
            session()->setFlashdata('success', 'Disco eliminado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al intentar eliminar el disco.');
        }
        return redirect()->to(base_url('admin/discos'));
    }
}