<?php namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nom_categoria' // Nombre de la columna en la tabla 'categorias'
    ];
    
    // Si necesitas validación simple para categorías:
    protected $validationRules = [
        'nom_categoria' => 'required|min_length[3]|max_length[50]'
    ];
}

