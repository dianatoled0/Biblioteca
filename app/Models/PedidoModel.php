<?php namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table        = 'pedidos';
    protected $primaryKey   = 'id';
    protected $returnType   = 'array';
    
    // CAMPOS DE TU BASE DE DATOS
    protected $allowedFields    = ['id_user', 'monto_total', 'estado_pedido', 'fecha_pedido'];

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
} 