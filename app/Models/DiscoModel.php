<?php namespace App\Models;

use CodeIgniter\Model;

class DiscoModel extends Model
{
    protected $table          = 'discos';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    
    // Configuración para evitar errores de fecha (timestamps)
    protected $useTimestamps  = false;
    protected $useSoftDeletes = false;
    
    // Campos permitidos (DEBEN COINCIDIR con la DB)
    protected $allowedFields  = [
        'titulo', 
        'artista', 
        'precio_venta',
        'stock', 
        'id_categoria'
    ];

    // CRÍTICO: Las reglas de validación usan los nombres de la DB 
    // Este bloque ha sido reescrito para eliminar el carácter invisible.
    protected $validationRules = [
        'titulo'        => 'required|min_length[3]|max_length[255]',
        'artista'       => 'required|min_length[3]|max_length[255]',
        'precio_venta'  => 'required|numeric|greater_than[0]',
        'stock'         => 'required|integer|greater_than_equal_to[0]',
        'id_categoria'  => 'required|integer' // ¡Línea 30 limpia y correcta!
    ];
    
    protected $validationMessages = [
        'precio_venta' => [
            'required' => 'El campo Precio (Q) es requerido.',
            'numeric'  => 'El Precio debe ser un número.',
            'greater_than' => 'El Precio debe ser mayor a cero.'
        ],
        'id_categoria' => [
            'required' => 'Debe seleccionar una Categoría.',
            'integer'  => 'Debe seleccionar una Categoría válida.'
        ]
    ];
}
