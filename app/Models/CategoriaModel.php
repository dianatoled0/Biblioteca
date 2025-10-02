<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';   // Nombre de la tabla en la BD
    protected $primaryKey = 'id';           // Clave primaria

    protected $useAutoIncrement = true;     // Autoincrementar ID
    protected $returnType       = 'array';  // Devuelve resultados como array
    protected $useSoftDeletes   = false;    // No usamos borrado lógico

    // Campos permitidos para inserción/actualización
    protected $allowedFields = ['nom_categoria'];

    // Validaciones básicas
    protected $validationRules = [
        'nom_categoria' => 'required|min_length[3]|max_length[50]'
    ];

    protected $validationMessages = [
        'nom_categoria' => [
            'required'   => 'El nombre de la categoría es obligatorio.',
            'min_length' => 'El nombre de la categoría debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre de la categoría no puede superar los 50 caracteres.'
        ]
    ];

    protected $skipValidation = false;
}

