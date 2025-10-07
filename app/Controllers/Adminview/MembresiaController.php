<?php 
namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\TipoMembresiaModel;
use App\Models\UsuarioModel; 

class MembresiaController extends BaseController
{
    protected $tipoMembresiaModel;
    protected $usuarioModel;
    
    public function __construct()
    {
        $this->tipoMembresiaModel = new TipoMembresiaModel();
        $this->usuarioModel = new UsuarioModel();
        helper('url');
    }

    // Muestra el listado de los 3 tipos de membresía
    public function index()
    {
        $membresias_db = $this->tipoMembresiaModel->findAll();

        $membresias_completas = [];
        
        // 🚨 CAMBIO CLAVE: Nuevas características de membresía
        $caracteristicas = [
            1 => [ // ID 1: Básica
                // Usamos <li> para estructurar la información, que luego limpiaremos en la vista
                'desc' => '
                    Descuento: 5% en toda la tienda.
                    Costo de envío: Q35.
                    Envío gratuito en compras mayores a Q250.
                    Tiempo de entrega: hasta 1 mes.'
            ],
            2 => [ // ID 2: Estándar
                'desc' => '
                    Descuento: 15% en toda la tienda.
                    Envío gratuito.
                    Tiempo de entrega: entre 15 y 20 días.'
            ],
            3 => [ // ID 3: Premium
                'desc' => '
                    Descuento: 25% en toda la tienda.
                    Envío gratuito.
                    Beneficios adicionales: Acceso prioritario a preventas y Entrega prioritaria de productos.'
            ]
        ];

        // Combinar datos
        foreach ($membresias_db as $membresia) {
            $id = $membresia['id'];
            if (isset($caracteristicas[$id])) {
                $membresias_completas[] = [
                    'id'              => $id,
                    'nombre'          => 'Membresía ' . $membresia['nombre'],
                    'precio'          => $membresia['precio'],
                    'duracion'        => $membresia['duracion_meses'],
                    'caracteristicas' => $caracteristicas[$id]['desc'] // Se pasa el texto con saltos de línea
                ];
            } else {
                 $membresias_completas[] = [
                    'id'              => $id,
                    'nombre'          => 'Membresía ' . $membresia['nombre'],
                    'precio'          => $membresia['precio'],
                    'duracion'        => $membresia['duracion_meses'],
                    'caracteristicas' => 'Sin descripción.'
                 ];
            }
        }

        $data = [
            'membresias' => $membresias_completas,
            'titulo'     => 'Gestión de Membresías'
        ];
        
        return view('admin/membresias/index', $data);
    }

    /**
     * Muestra los usuarios asignados a una membresía específica.
     */
    public function usuarios($idMembresia = null) 
    {
        if (empty($idMembresia) || !is_numeric($idMembresia)) {
            session()->setFlashdata('error', 'Membresía no válida.');
            return redirect()->to(base_url('admin/membresias'));
        }

        $membresia = $this->tipoMembresiaModel->find($idMembresia);

        if (!$membresia) {
            session()->setFlashdata('error', 'Membresía no encontrada.');
            return redirect()->to(base_url('admin/membresias'));
        }

        $usuarios = $this->usuarioModel->getUsuariosByMembresia($idMembresia);
        
        $data = [
            'membresia' => $membresia,
            'usuarios'  => $usuarios,
            'titulo'    => 'Usuarios de la Membresía: ' . $membresia['nombre']
        ];

        return view('admin/membresias/detalle_usuarios', $data);
    }
}