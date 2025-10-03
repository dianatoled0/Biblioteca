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
        // Asegúrate de que existe el archivo CategoriaModel.php
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
        
        if (! $this->validate($this->discoModel->validationRules))
        {
            return view('admin/discos/form', [
                'validation' => $this->validator,
                'categorias' => $this->categoriaModel->findAll()
            ]);
        }
        
        $saved = $this->discoModel->save([
            'titulo'       => $input['titulo'],
            'artista'      => $input['artista'],
            'precio_venta' => $input['precio'],     
            'stock'        => $input['stock'],
            'id_categoria' => $input['categoria_id'] 
        ]);

        // Lógica de depuración de errores de DB (para mostrar error si falla)
        if ($saved) {
            session()->setFlashdata('success', 'Disco creado exitosamente.');
        } else {
            // Intentar capturar el error de la DB
            $db = \Config\Database::connect();
            $dbError = $db->error();

            $errorMessage = "Error de Guardado: La operación de la base de datos falló.";
            
            if ($dbError['code'] != 0) {
                // Si la DB reportó un error (y no falló al intentar leerlo)
                $errorMessage .= " (DB Code: " . $dbError['code'] . " - Mensaje: " . $dbError['message'] . ")";
            } else {
                // Si el error fue silencioso y no pudimos leerlo, asumimos un problema de clave foránea/NOT NULL
                $errorMessage .= " (Fallo Silencioso: Revisa si estás enviando una Categoría válida o si hay un campo NOT NULL vacío.)";
            }

            session()->setFlashdata('error', $errorMessage);
            
            return view('admin/discos/form', [
                'validation' => $this->validator,
                'categorias' => $this->categoriaModel->findAll()
            ]);
        }
        

        return redirect()->to(base_url('admin/discos'));
    }

    // Los métodos editar, actualizar y eliminar permanecen igual a las versiones anteriores limpias

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

        if (! $this->validate($this->discoModel->validationRules))
        {
            return view('admin/discos/form', [
                'validation' => $this->validator,
                'disco'      => $this->discoModel->find($id),
                'categorias' => $this->categoriaModel->findAll()
            ]);
        }
        
        $this->discoModel->update($id, [
            'titulo'       => $input['titulo'],
            'artista'      => $input['artista'],
            'precio_venta' => $input['precio'],     
            'stock'        => $input['stock'],
            'id_categoria' => $input['categoria_id'] 
        ]);

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