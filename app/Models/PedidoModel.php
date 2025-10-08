<?php namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table      = 'pedidos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    
    // Dejamos solo los campos que existen en la tabla `pedidos`.
    protected $allowedFields = ['id_user', 'monto_total', 'estado_pedido', 'fecha_pedido', 'fecha_entrega'];

    protected $useTimestamps    = false;
    protected $useSoftDeletes   = false;

    /**
     * Obtiene todos los pedidos realizados por un usuario específico.
     * ESTE ES EL MÉTODO QUE FALTABA
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
     * Obtiene la cabecera del pedido, detalle y las reglas de membresía asociadas al usuario.
     */
    public function getDetallePedido($idPedido)
    {
        // 1. Obtener la cabecera del pedido, datos de usuario y datos de membresía
        $pedidoData = $this->db->table('pedidos p')
                           ->select('p.*, u.id_membresia, tm.nombre, tm.descuento_porcentaje, tm.costo_envio_fijo, tm.envio_gratis_monto_minimo')
                           ->join('usuarios u', 'u.id = p.id_user')
                           ->join('tipos_membresia tm', 'tm.id = u.id_membresia') // <- JOIN CLAVE
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

        return [
            'pedido' => $pedidoData,
            'detalle' => $detalle
        ];
    }
}