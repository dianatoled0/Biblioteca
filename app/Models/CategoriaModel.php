<?php 
namespace App\Models;

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

    /**
     * Obtiene todas las categorías junto con el conteo de discos que pertenecen a cada una.
     * La unión se ajusta al nombre de columna 'id_categoria' en la tabla 'discos'.
     */
    public function getCategoriasWithDiscsCount()
    {
        // 1. Seleccionamos los campos de la tabla 'categorias'.
        // 2. Usamos COUNT(discos.id) y le damos un alias 'total_discos'.
        // 3. Hacemos un LEFT JOIN con la tabla 'discos' usando la llave foránea.
        // 🚨 CORRECCIÓN APLICADA: Usamos 'discos.id_categoria' basado en el esquema de tu BD
        return $this->select('categorias.id, categorias.nom_categoria, COUNT(discos.id) as total_discos')
                    ->join('discos', 'discos.id_categoria = categorias.id', 'left')
                    ->groupBy('categorias.id')
                    ->orderBy('categorias.nom_categoria', 'ASC')
                    ->findAll();
    }
}

