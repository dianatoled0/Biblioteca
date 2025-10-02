<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoPagoModel extends Model
{
    protected $table      = 'tipos_pago';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'descripcion'
    ];

    protected $useTimestamps = false;
}

