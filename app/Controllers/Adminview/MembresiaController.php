<?php 
namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\TipoMembresiaModel;
use App\Models\UsuarioModel; // Necesario para la vista de detalle

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
        // NOTA: Para replicar el formato de la tabla de Discos, necesitamos construir 
        // un array que combine los datos de la DB con las características fijas.
        $membresias_db = $this->tipoMembresiaModel->findAll();

        $membresias_completas = [];
        
        // Las características fijas según tu solicitud
        $caracteristicas = [
            1 => [ // ID 1: Básica
                'icono' => '🎵',
                'desc' => '5% de descuento en discos seleccionados. Acceso anticipado a lanzamientos. Boletín mensual.'
            ],
            2 => [ // ID 2: Estándar
                'icono' => '💿',
                'desc' => '10% de descuento en discos y artículos. Envío gratuito en compras > Q250. Doble acumulación de puntos.'
            ],
            3 => [ // ID 3: Premium
                'icono' => '🔥',
                'desc' => '15–20% de descuento en toda la tienda. Entrada prioritaria a preventas. Kit de bienvenida.'
            ]
        ];

        // Combinar datos
        foreach ($membresias_db as $membresia) {
            $id = $membresia['id'];
            // Se añade el chequeo isset para evitar errores si un ID de membresía de la DB no está en $caracteristicas
            if (isset($caracteristicas[$id])) {
                $membresias_completas[] = [
                    'id'          => $id,
                    'nombre'      => $caracteristicas[$id]['icono'] . ' Membresía ' . $membresia['nombre'],
                    'precio'      => $membresia['precio'],
                    'duracion'    => $membresia['duracion_meses'],
                    'caracteristicas' => $caracteristicas[$id]['desc']
                ];
            } else {
                // Alternativa por si falta la configuración de características
                 $membresias_completas[] = [
                    'id'          => $id,
                    'nombre'      => 'Membresía ' . $membresia['nombre'],
                    'precio'      => $membresia['precio'],
                    'duracion'    => $membresia['duracion_meses'],
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

    // Muestra los usuarios asignados a una membresía específica
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

        // CORRECCIÓN FINAL: Se remueven los asteriscos innecesarios.
        // La vista es 'app/Views/admin/membresias/detalle_usuarios.php'
        return view('admin/membresias/detalle_usuarios', $data); // <-- Línea 101 corregida
    }
    
    // NOTA: Debes incluir aquí los métodos crearMembresia, editarMembresia y eliminarMembresia si existen.
}
