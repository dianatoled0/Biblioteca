<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para la tabla 'tipos_membresia'.
 */
class TipoMembresiaModel extends Model
{
    // Configuración principal de la tabla
    protected $table          = 'tipos_membresia';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false; 
    protected $useTimestamps  = false; 

    // Campos permitidos (¡Añadir las nuevas reglas!)
    protected $allowedFields = [
        'nombre',
        'precio',
        'duracion_meses',
        'descuento_porcentaje', // Nuevo
        'envio_gratis_monto_minimo', // Nuevo
        'costo_envio_fijo', // Nuevo
    ];

    /**
     * Obtiene todas las membresías, incluyendo su duración.
     * Esto es necesario para el formulario de creación/edición de usuarios.
     * @return array
     */
    public function getAllMembresias(): array
    {
        return $this->select('id, nombre, duracion_meses')->findAll();
    }

    /**
     * Obtiene los detalles completos de una membresía, incluyendo las reglas de compra.
     * @param int $idMembresia ID de la membresía.
     * @return array|null
     */
    public function getReglasMembresia(int $idMembresia): ?array
    {
        return $this->find($idMembresia);
    }
}