<?php namespace App\Controllers\Userview;

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
        $this->discoModel = new DiscoModel();
        $this->categoriaModel = new CategoriaModel();
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel(); 
    }

    public function index()
    {
        // INTENTAR nombre_completo, luego usuario, luego el valor por defecto
        $nombreUsuario = session()->get('nombre_completo') 
                         ?? session()->get('usuario') 
                         ?? 'Usuario'; 
        
        $data = [
            'latestDiscos' => $this->discoModel->getDiscos(),
            'categorias' => $this->categoriaModel->findAll(),
            'allDiscos' => $this->discoModel->getDiscos(),
            'nombreUsuario' => $nombreUsuario
        ];

        return view('user/home_view', $data);
    }

    public function membresias()
    {
        $userId = session()->get('id'); 
        
        $data['usuario'] = $this->usuarioModel
                                ->select('usuarios.*, tipos_membresia.nombre as nombre_membresia, tipos_membresia.precio, tipos_membresia.duracion_meses')
                                ->join('tipos_membresia', 'tipos_membresia.id = usuarios.id_membresia', 'left')
                                ->find($userId);
        
        $data['tipos_membresia'] = $this->membresiaModel->findAll();

        return view('user/membresias_view', $data);
    }
    
    /**
     * Maneja la llamada AJAX para filtrar discos por categoría. (SOLUCIÓN AL ERROR DE FILTRO)
     * Ruta: /usuario/ajax/discos/(:num)
     */
    public function getDiscosByCategory($id_categoria = 0)
    {
        // Si $id_categoria es 0, obtenemos todos los discos
        if ($id_categoria == 0) {
            $discos = $this->discoModel->getDiscos(); 
        } else {
            // Llamamos al método del modelo que filtra por ID
            $discos = $this->discoModel->getDiscosByCategory($id_categoria); 
        }

        // Devolvemos la respuesta en formato JSON
        return $this->response->setJSON([
            'status' => 'success', 
            'discos' => $discos
        ]);
    }
}