<?php namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table        = 'pedidos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    
    // Incluir 'fecha_entrega' en los campos permitidos.
    protected $allowedFields = ['id_user', 'monto_total', 'estado_pedido', 'fecha_pedido', 'fecha_entrega'];

    protected $useTimestamps    = false;
    protected $useSoftDeletes   = false;

    /**
     * Obtiene todos los pedidos realizados por un usuario específico.
     * @param int $userId El ID del usuario.
     * @return array
     */
    public function getPedidosByUserId($userId)
    {
        return $this->where('id_user', $userId)
                     ->orderBy('fecha_pedido', 'DESC') // Ordena por fecha más reciente
                     ->findAll();
    }
    
    /**
     * Obtiene todos los pedidos y los une con usuario y membresía.
     * Incorpora el FILTRO por ID de membresía.
     * @param int|null $membresiaId ID de la membresía para filtrar, o null para todos.
     * @return array
     */
    public function getPedidosConUsuario($membresiaId = null)
    {
        $builder = $this->db->table('pedidos p')
                            ->select('p.*, u.nombre AS nom_cliente, u.apellido AS ape_cliente, tm.nombre AS nom_membresia, tm.id AS id_membresia')
                            ->join('usuarios u', 'u.id = p.id_user')
                            ->join('tipos_membresia tm', 'tm.id = u.id_membresia')
                            ->orderBy('p.fecha_pedido', 'DESC');
        
        // Lógica de Filtro por Membresía
        if (!empty($membresiaId)) {
            $builder->where('tm.id', $membresiaId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene la cabecera del pedido, detalle y las reglas de membresía asociadas al usuario.
     * Se devuelve en un formato plano y fácil de usar para la vista del Administrador.
     */
    public function getDetallePedidoAdmin($idPedido)
    {
        // 1. Obtener la cabecera del pedido, datos de usuario y datos de membresía
        $pedidoData = $this->db->table('pedidos p')
                             ->select('p.*, u.nombre AS nom_cliente, u.apellido AS ape_cliente, tm.nombre AS nom_membresia, tm.descuento_porcentaje, tm.costo_envio_fijo, tm.envio_gratis_monto_minimo')
                             ->join('usuarios u', 'u.id = p.id_user')
                             ->join('tipos_membresia tm', 'tm.id = u.id_membresia')
                             ->where('p.id', $idPedido)
                             ->get()
                             ->getRowArray();

        if (!$pedidoData) {
            return null;
        }

        // 2. Obtener los detalles (discos) del pedido
        $detalle = $this->db->table('detalle_pedidos dp')
                            ->select('dp.cantidad, dp.sub_total, d.titulo, d.artista, d.precio_venta')
                            ->join('discos d', 'd.id = dp.id_disco')
                            ->where('dp.id_pedido', $idPedido)
                            ->get()
                            ->getResultArray();

        // Combina el pedido principal con los items en el índice 'detalle'
        $pedidoData['detalle'] = $detalle;
        
        return $pedidoData;
    }
    
    /**
     * Cambia el estado del pedido y actualiza la fecha de entrega si el estado es 'Entregado'.
     * @param int $pedidoId ID del pedido a actualizar.
     * @param string $nuevoEstado El nuevo estado (Pendiente, Enviado, Entregado).
     * @return bool
     */
    public function actualizarEstado($pedidoId, $nuevoEstado)
    {
        $data = ['estado_pedido' => $nuevoEstado];

        // Lógica de Fecha de Entrega
        if ($nuevoEstado === 'Entregado') {
            $data['fecha_entrega'] = date('Y-m-d'); // Fecha de hoy
        } else {
            // Si no está entregado, la fecha de entrega se borra (NULL)
             $data['fecha_entrega'] = NULL;
        }

        return $this->update($pedidoId, $data);
    }
}