<?php namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table      = 'pedidos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    
    // CAMBIO IMPORTANTE: Agregada 'fecha_entrega'
    protected $allowedFields = ['id_user', 'monto_total', 'estado_pedido', 'fecha_pedido', 'fecha_entrega'];

    protected $useTimestamps    = false;
    protected $useSoftDeletes   = false;

    // Método para el admin (no se toca)
    public function getPedidosConUsuario()
    {
        return $this->select('pedidos.*, usuarios.nombre, usuarios.apellido')
                    ->join('usuarios', 'usuarios.id = pedidos.id_user')
                    ->orderBy('pedidos.id', 'DESC')
                    ->findAll();
    }

    // NUEVO MÉTODO: Obtiene todos los pedidos de un usuario
    public function getPedidosByUserId($userId)
    {
        return $this->where('id_user', $userId)
                    ->orderBy('fecha_pedido', 'DESC')
                    ->findAll();
    }

    // NUEVO MÉTODO: Obtiene la cabecera del pedido y su detalle (discos comprados)
    public function getDetallePedido($idPedido)
    {
        // 1. Obtener la cabecera del pedido
        $pedido = $this->find($idPedido);

        if (!$pedido) {
            return null;
        }

        // 2. Obtener los detalles (discos) del pedido
        // Asegúrate de tener un DetallePedidoModel si quieres usarlo, sino usa el Query Builder:
        $detalle = $this->db->table('detalle_pedidos dp')
                           ->select('dp.cantidad, dp.sub_total, d.titulo, d.artista, d.precio_venta')
                           ->join('discos d', 'd.id = dp.id_disco')
                           ->where('dp.id_pedido', $idPedido)
                           ->get()
                           ->getResultArray();

        return [
            'pedido' => $pedido,
            'detalle' => $detalle
        ];
    }
}