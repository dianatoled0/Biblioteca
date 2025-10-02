<?php

namespace App\Models;

use CodeIgniter\Model;

class IngresoModel extends Model
{
    protected $table            = 'ingresos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_disco',
        'cantidad',
        'fecha_ingreso'
    ];

    // Validaciones básicas
    protected $validationRules = [
        'id_disco'      => 'required|integer',
        'cantidad'      => 'required|integer|greater_than[0]',
        'fecha_ingreso' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'id_disco' => [
            'required' => 'Debe seleccionar un disco.',
            'integer'  => 'El disco debe ser un valor válido.'
        ],
        'cantidad' => [
            'required'    => 'Debe ingresar una cantidad.',
            'integer'     => 'La cantidad debe ser un número.',
            'greater_than'=> 'La cantidad debe ser mayor a 0.'
        ],
        'fecha_ingreso' => [
            'required'   => 'Debe ingresar la fecha del ingreso.',
            'valid_date' => 'La fecha no es válida.'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Obtener ingresos con información del disco relacionado
     */
    public function getIngresosConDiscos()
    {
        return $this->select('ingresos.*, discos.titulo, discos.artista')
                    ->join('discos', 'discos.id = ingresos.id_disco')
                    ->orderBy('ingresos.fecha_ingreso', 'DESC')
                    ->findAll();
    }
}

