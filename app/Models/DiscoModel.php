<?php namespace App\Models;

use CodeIgniter\Model;

class DiscoModel extends Model
{
    protected $table         = 'discos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    
    protected $useTimestamps    = false;
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'titulo', 
        'artista', 
        'precio_venta',
        'stock', 
        'id_categoria'
    ];

    protected $validationRules = [
        'titulo'        => 'required|min_length[3]|max_length[255]',
        'artista'       => 'required|min_length[3]|max_length[255]',
        'precio_venta'  => 'required|numeric|greater_than[0]',
        'stock'         => 'required|integer|greater_than_equal_to[0]',
        'id_categoria'  => 'required|integer' 
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
    
    /**
     * Obtiene todos los discos, incluyendo el nombre de la categoría.
     * La columna corregida es 'categorias.nom_categoria'.
     */
    public function getDiscos()
    {
        return $this->select('discos.*, categorias.nom_categoria AS nom_categoria')
                    ->join('categorias', 'categorias.id = discos.id_categoria', 'left')
                    ->findAll();
    }

    /**
     * Obtiene los discos por ID de categoría, incluyendo el nombre.
     * La columna corregida es 'categorias.nom_categoria'.
     */
    public function getDiscosByCategory($id_categoria)
    {
        return $this->select('discos.*, categorias.nom_categoria AS nom_categoria')
                    ->join('categorias', 'categorias.id = discos.id_categoria', 'left')
                    ->where('discos.id_categoria', $id_categoria)
                    ->findAll();
    }

    /**
     * Actualiza el stock de un disco restándole la cantidad vendida.
     */
    public function updateStock($discoId, $cantidadVendida)
    {
        return $this->db->table('discos')
                        ->where('id', $discoId)
                        // Usa set() con el tercer parámetro 'false' para ejecutar la operación SQL
                        ->set('stock', 'stock - ' . (int)$cantidadVendida, false) 
                        ->update();
    }
}