<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleReciboModel extends Model
{
    protected $table = 'detalle_recibos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_recibo',
        'id_disco',
        'cantidad',
        'sub_total'
    ];
}
