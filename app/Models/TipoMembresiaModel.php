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

    // Campos permitidos
    protected $allowedFields = [
        'nombre',
        'precio',
        'duracion_meses',
    ];

    // Puedes agregar aquí métodos específicos para membresías si los necesitas
}
