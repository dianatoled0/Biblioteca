<?php

namespace App\Models;

use CodeIgniter\Model;

class MembresiaModel extends Model
{
    protected $table      = 'tipos_membresia';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'precio',
        'duracion_meses'
    ];

    protected $useTimestamps = false;
}

