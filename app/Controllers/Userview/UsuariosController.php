<?php namespace App\Controllers\Userview;

use App\Controllers\BaseController;
use App\Models\DiscoModel;
use App\Models\CategoriaModel;
use App\Models\UsuarioModel;
use App\Models\TipoMembresiaModel;
use App\Models\PedidoModel; // ¡IMPORTAR EL NUEVO MODELO!

class UsuariosController extends BaseController
{
    protected $discoModel;
    protected $categoriaModel;
    protected $usuarioModel;
    protected $membresiaModel;
    protected $pedidoModel; // Declaración de la propiedad

    public function __construct()
    {
        $this->discoModel = new DiscoModel();
        $this->categoriaModel = new CategoriaModel();
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel(); 
        $this->pedidoModel = new PedidoModel(); // Inicialización del modelo
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
     * NUEVO MÉTODO: Muestra la lista de todas las compras realizadas por el usuario.
     */
    public function compras()
    {
        $userId = session()->get('id'); // Obtener el ID del usuario logueado
        
        $data['pedidos'] = $this->pedidoModel->getPedidosByUserId($userId);
        $data['title'] = 'Mis Compras Realizadas';

        // Cargar la vista de la lista de compras
        return view('user/compras_realizadas_view', $data); 
    }

    /**
     * NUEVO MÉTODO: Muestra la información detallada de un pedido específico.
     * @param int $idPedido El ID del pedido a mostrar.
     */
    public function detalle_compra($idPedido = null)
    {
        if (is_null($idPedido)) {
            return redirect()->back();
        }

        $detalleData = $this->pedidoModel->getDetallePedido($idPedido);
        $userId = session()->get('id');

        // Seguridad: Verificar que el pedido exista y pertenezca al usuario logueado
        if (empty($detalleData) || $detalleData['pedido']['id_user'] != $userId) {
            return redirect()->to(base_url('usuario/compras'))->with('error', 'Pedido no encontrado o no autorizado.');
        }

        $data['pedido'] = $detalleData['pedido'];
        $data['detalle'] = $detalleData['detalle'];
        $data['title'] = 'Detalle de Compra #' . $idPedido;

        // Cargar la vista para el detalle
        return view('user/detalle_compra_view', $data); 
    }

    /**
     * Maneja la llamada AJAX para filtrar discos por categoría. (SOLUCIÓN AL ERROR DE FILTRO)
     * Ruta: /usuario/ajax/discos/(:num)
     */
    public function getDiscosByCategory($id_categoria = 0)
    {
        // ... (Tu código existente aquí)
        if ($id_categoria == 0) {
            $discos = $this->discoModel->getDiscos(); 
        } else {
            $discos = $this->discoModel->getDiscosByCategory($id_categoria); 
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'discos' => $discos
        ]);
    }
}