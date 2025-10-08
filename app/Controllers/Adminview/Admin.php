<?php 

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
// Eliminamos: use App\Models\PagoModel;
// Eliminamos: use App\Models\IngresoModel;
// Eliminamos: use App\Models\ReciboModel;
use App\Models\PedidoModel; // Necesario para la sección de Órdenes
use App\Models\NotificacionModel; 

class Admin extends BaseController 
{
    // Solo dejamos los modelos que sí existen y son necesarios para el dashboard
    protected $pedidoModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
        $this->notificacionModel = new NotificacionModel(); 
    }

    /**
     * Muestra el Dashboard Principal, Notificaciones y Órdenes Recientes.
     */
    public function index()
    {
        // 1. Obtener las notificaciones para el panel de historial
        $notificaciones = $this->notificacionModel->getLatestNotifications(5); 
        
        // 2. Obtener las últimas órdenes realizadas para la tabla (ej. 4 órdenes)
        $ordenes = $this->pedidoModel->orderBy('fecha_pedido', 'DESC')->findAll(4); 

        $data = [
            'title'          => 'Dashboard', 
            'notificaciones' => $notificaciones,
            'ordenes'        => $ordenes, // Datos para la tabla de Órdenes Realizadas
            
            // Aquí se pueden añadir las estadísticas (Total de Usuarios, Ingresos, Discos en Stock) 
            // que se ven en tus capturas de pantalla, si tienes los métodos para obtenerlas.
            'totalUsuarios'  => 1250, // EJEMPLO: Reemplazar con lógica de UsuarioModel
            'ingresosMes'    => '$5,400', // EJEMPLO: Reemplazar con lógica de IngresoModel (si lo creas)
            'discosStock'    => 450, // EJEMPLO: Reemplazar con lógica de DiscoModel
        ];

        return view('admin/index', $data); 
    }

    /* * ELIMINAMOS TODOS LOS MÉTODOS DE CRUD DE PAGOS, INGRESOS Y RECIBOS.
     * Si necesitas estas funciones en el futuro, tendrás que crear los modelos y tablas.
     * Si los archivos de vista (admin/pagos, admin/ingresos, admin/recibos) aún existen, 
     * no funcionarán si intentas acceder a ellos.
     */
}