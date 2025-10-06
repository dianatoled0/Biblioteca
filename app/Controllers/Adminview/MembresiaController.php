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
        
        // Se han quitado los emojis de los íconos (solo se mantiene la descripción)
        $caracteristicas = [
            1 => [ // ID 1: Básica
                'desc' => '5% de descuento en discos seleccionados. Acceso anticipado a lanzamientos. Boletín mensual.'
            ],
            2 => [ // ID 2: Estándar
                'desc' => '10% de descuento en discos y artículos. Envío gratuito en compras > Q250. Doble acumulación de puntos.'
            ],
            3 => [ // ID 3: Premium
                'desc' => '15–20% de descuento en toda la tienda. Entrada prioritaria a preventas. Kit de bienvenida.'
            ]
        ];

        // Combinar datos
        foreach ($membresias_db as $membresia) {
            $id = $membresia['id'];
            if (isset($caracteristicas[$id])) {
                $membresias_completas[] = [
                    'id'            => $id,
                    // Se ha quitado la concatenación del ícono
                    'nombre'        => 'Membresía ' . $membresia['nombre'],
                    'precio'        => $membresia['precio'],
                    'duracion'      => $membresia['duracion_meses'],
                    'caracteristicas' => $caracteristicas[$id]['desc']
                ];
            } else {
                 $membresias_completas[] = [
                    'id'            => $id,
                    'nombre'        => 'Membresía ' . $membresia['nombre'],
                    'precio'        => $membresia['precio'],
                    'duracion'      => $membresia['duracion_meses'],
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
    // CORRECCIÓN: Se cambió 'publicric' por 'public'
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