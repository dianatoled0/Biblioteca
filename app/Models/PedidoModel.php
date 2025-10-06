<?php namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table          = 'pedidos';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    
    // Campos permitidos en la tabla 'pedidos' (coinciden con tu DB)
    protected $allowedFields  = ['id_user', 'monto_total', 'estado_pedido', 'fecha_pedido'];

    protected $useTimestamps  = false;
    protected $useSoftDeletes = false;

    /**
     * Obtiene el listado de pedidos con el nombre completo del usuario que realizó el pedido.
     * La consulta une la tabla 'pedidos' con la tabla 'usuarios'.
     * @return array
     */
    public function getPedidosConUsuario()
    {
        return $this->select('pedidos.*, usuarios.nombre, usuarios.apellido')
                    ->join('usuarios', 'usuarios.id = pedidos.id_user')
                    ->orderBy('pedidos.id', 'DESC')
                    ->findAll();
    }
}
