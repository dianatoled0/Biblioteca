<?php namespace App\Controllers\Userview; // <-- ¡Namespace Correcto!

use App\Controllers\BaseController;
use App\Models\DiscoModel;
use App\Models\CategoriaModel;
use App\Models\UsuarioModel;
use App\Models\TipoMembresiaModel;

class UsuariosController extends BaseController
{
    protected $discoModel;
    protected $categoriaModel;
    protected $usuarioModel;
    protected $membresiaModel;

    public function __construct()
    {
        // Inicialización de modelos
        $this->discoModel = new DiscoModel();
        $this->categoriaModel = new CategoriaModel();
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel(); 
    }

    /**
     * Muestra la página principal (Home/Tienda).
     * Ruta: GET /usuario
     */
    public function index()
    {
        $data = [
            'latestDiscos' => $this->discoModel->getDiscos(), // Cambiado a getDiscos() para mostrar todo
            'categorias' => $this->categoriaModel->findAll(),
            'allDiscos' => $this->discoModel->getDiscos(),
        ];

        // Usa tu layout_user.php en la carpeta layoutuser/
        return view('layoutuser/layout_user', [
            'content' => view('user/home_view', $data)
        ]);
    }

    /**
     * Muestra la página de Membresías del usuario logueado.
     * Ruta: GET /usuario/membresias
     */
    public function membresias()
    {
        $userId = session()->get('id'); 
        
        $data['usuario'] = $this->usuarioModel
                                ->select('usuarios.*, tipos_membresia.nombre as nombre_membresia, tipos_membresia.precio, tipos_membresia.duracion_meses')
                                ->join('tipos_membresia', 'tipos_membresia.id = usuarios.id_membresia', 'left')
                                ->find($userId);
        
        $data['tipos_membresia'] = $this->membresiaModel->findAll();

        // Usa tu layout_user.php en la carpeta layoutuser/
        return view('layoutuser/layout_user', [
            'content' => view('user/membresias_view', $data)
        ]);
    }

    /**
     * Endpoint AJAX: Obtiene discos filtrados por ID de categoría o todos.
     * Ruta: GET /usuario/ajax/discos/(:num)
     */
    public function getDiscosByCategory($id_categoria)
    {
        if ($id_categoria == 0) {
            $discos = $this->discoModel->getDiscos(); 
        } else {
            $discos = $this->discoModel->getDiscosByCategory($id_categoria); 
        }

        return $this->response->setJSON(['status' => 'success', 'discos' => $discos]);
    }
}