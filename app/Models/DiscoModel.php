<?php namespace App\Models;

use CodeIgniter\Model;

class DiscoModel extends Model
{
    protected $table            = 'discos';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    
    // CORRECCIÓN CRÍTICA: Desactivamos las funciones de fecha y borrado
    protected $useTimestamps    = false;
    protected $useSoftDeletes   = false;
    
    // Lista final de campos permitidos, sin 'imagen'
    protected $allowedFields    = [
        'titulo', 
        'artista', 
        'precio_venta', // Nombre de Columna DB
        'stock', 
        'id_categoria'  // Nombre de Columna DB
    ];

    // Reglas de validación: Usamos los NOMBRES DEL FORMULARIO (input names)
    protected $validationRules = [
        'titulo'        => 'required|min_length[3]|max_length[255]',
        'artista'       => 'required|min_length[3]|max_length[255]',
        'precio'        => 'required|numeric|greater_than[0]', 
        'stock'         => 'required|integer|greater_than_equal_to[0]',
        'categoria_id'  => 'required|integer'                   
    ];
    
    protected $validationMessages = [
        'precio' => [
            'required' => 'El campo Precio (Q) es requerido.',
            'numeric'  => 'El Precio debe ser un número.',
            'greater_than' => 'El Precio debe ser mayor a cero.'
        ],
        'categoria_id' => [
            'required' => 'Debe seleccionar una Categoría.',
            'integer'  => 'Debe seleccionar una Categoría válida.'
        ]
    ];
}
