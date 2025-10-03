<?php namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class CategoriaController extends BaseController // ¡Clase corregida!
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
        // Asegúrate de cargar el helper de formularios para el form_open() en la vista
        helper(['form', 'url']); 
    }

    /**
     * Muestra el listado de categorías.
     */
    public function index()
    {
        $data = [
            'categorias' => $this->categoriaModel->findAll() // Obtiene todas las categorías
        ];
        
        return view('admin/categorias/index', $data); // El path de la vista sigue siendo admin/categorias/index
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function crear()
    {
        // La vista 'form.php' maneja el modo de creación si no se pasa $categoria
        return view('admin/categorias/form');
    }

    /**
     * Intenta guardar una nueva categoría en la DB.
     */
    public function guardar()
    {
        // 1. Validar los datos del formulario
        if (! $this->validate($this->categoriaModel->validationRules))
        {
            // Si falla la validación, regresa al formulario con errores y datos antiguos
            return view('admin/categorias/form', [
                'validation' => $this->validator
            ]);
        }
        
        // 2. Si la validación es correcta, guarda en la DB.
        $this->categoriaModel->save([
            'nom_categoria' => $this->request->getPost('nom_categoria') // ¡Usamos nom_categoria!
        ]);

        // 3. Redirige con mensaje de éxito
        session()->setFlashdata('success', 'Categoría creada exitosamente.');
        return redirect()->to(base_url('admin/categorias'));
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     */
    public function editar($id = null)
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria) {
            session()->setFlashdata('error', 'Categoría no encontrada.');
            return redirect()->to(base_url('admin/categorias'));
        }

        return view('admin/categorias/form', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Intenta actualizar una categoría existente.
     */
    public function actualizar($id = null)
    {
        // 1. Validar los datos del formulario
        if (! $this->validate($this->categoriaModel->validationRules))
        {
            // Si falla la validación, regresa al formulario con errores y datos antiguos
            return view('admin/categorias/form', [
                'validation' => $this->validator,
                // Es importante devolver la categoría actual para que el formulario sepa que está en modo edición
                'categoria' => $this->categoriaModel->find($id) 
            ]);
        }
        
        // 2. Si la validación es correcta, actualiza en la DB.
        $this->categoriaModel->update($id, [
            'nom_categoria' => $this->request->getPost('nom_categoria') // ¡Usamos nom_categoria!
        ]);

        // 3. Redirige con mensaje de éxito
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

        return redirect()->to(base_url('admin/categorias'));
    }
}