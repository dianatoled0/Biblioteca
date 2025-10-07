<?php namespace App\Models;

use CodeIgniter\Model;

class DetallePedidoModel extends Model
{
    protected $table      = 'detalle_pedidos';
    protected $primaryKey = 'id'; 
    protected $returnType = 'array';
    protected $allowedFields = ['id_pedido', 'id_disco', 'cantidad', 'sub_total'];
    protected $useTimestamps = false; 
}