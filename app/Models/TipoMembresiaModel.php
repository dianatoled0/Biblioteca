<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para la tabla 'tipos_membresia'.
 */
class TipoMembresiaModel extends Model
{
    // Configuración principal de la tabla
    protected $table            = 'tipos_membresia';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array'; // Importante: retorna un array asociativo
    protected $useSoftDeletes   = false; 
    protected $useTimestamps    = false; 

    // Campos permitidos 
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
     * @return array
     */
    public function getAllMembresias(): array
    {
        return $this->select('id, nombre, duracion_meses')->findAll();
    }

    /**
     * Obtiene las reglas de descuento y envío necesarias para el carrito.
     * * @param int $idMembresia ID de la membresía.
     * @return array|null Solo los campos de reglas, o null si no se encuentra.
     */
    public function getReglasMembresia(int $idMembresia): ?array
    {
        // 🚨 Refinamiento clave: Solo selecciona los campos que el CarritoController necesita.
        $reglas = $this->select('descuento_porcentaje, envio_gratis_monto_minimo, costo_envio_fijo')
                       ->find($idMembresia);
        
        if ($reglas === null || $reglas === false) {
            log_message('debug', "Membresía ID: $idMembresia no encontrada para calcular reglas.");
            return null;
        }

        return $reglas;
    }
}