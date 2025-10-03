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
        // Asegúrate de que CategoriaModel exista en app/Models/
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
        
        // **CORRECCIÓN CRÍTICA:** Mapeamos los nombres del formulario 
        // ('precio', 'categoria_id') a los nombres de la DB/Modelo 
        // ('precio_venta', 'id_categoria').
        $data_to_validate_and_save = [
            'titulo'        => $input['titulo'] ?? null,
            'artista'       => $input['artista'] ?? null,
            'precio_venta'  => $input['precio'] ?? null, 
            'stock'         => $input['stock'] ?? null,
            'id_categoria'  => $input['categoria_id'] ?? null
        ];

        // 1. Cargar la validación y **validar el array mapeado**
        $validation = \Config\Services::validation();
        $validation->setRules($this->discoModel->validationRules, $this->discoModel->validationMessages);
        
        if (! $validation->run($data_to_validate_and_save))
        {
            // Si la validación falla, regresamos al formulario con los errores y los datos
            return view('admin/discos/form', [
                'validation' => $validation,
                // Pasamos los datos como objeto para que set_value() funcione correctamente.
                'disco'      => (object)$data_to_validate_and_save, 
                'categorias' => $this->categoriaModel->findAll()
            ]);
        }
        
        // 2. Si es válido, intentamos guardar
        $saved = $this->discoModel->save($data_to_validate_and_save);

        if ($saved) {
            session()->setFlashdata('success', 'Disco creado exitosamente.');
        } else {
            // Manejo de error para fallos en la DB
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
            'disco' => $disco,
            'categorias' => $this->categoriaModel->findAll()
        ];

        return view('admin/discos/form', $data);
    }

    public function actualizar($id = null)
    {
        $input = $this->request->getPost();
        
        // Mapeo de datos para la validación de actualización
        $data_to_validate_and_save = [
            'titulo'        => $input['titulo'] ?? null,
            'artista'       => $input['artista'] ?? null,
            'precio_venta'  => $input['precio'] ?? null,
            'stock'         => $input['stock'] ?? null,
            'id_categoria'  => $input['categoria_id'] ?? null
        ];
        
        // Validamos el array mapeado
        $validation = \Config\Services::validation();
        $validation->setRules($this->discoModel->validationRules, $this->discoModel->validationMessages);
        
        if (! $validation->run($data_to_validate_and_save))
        {
            // Si falla, regresamos la validación
            return view('admin/discos/form', [
                'validation' => $validation,
                'disco'      => $this->discoModel->find($id),
                'categorias' => $this->categoriaModel->findAll()
            ]);
        }
        
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