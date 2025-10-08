<?php namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table      = 'notificaciones';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; 

    protected $allowedFields = [
        'tipo_evento',
        'mensaje',
        'referencia_id',
    ];

    // Gestión de fechas (timestamps)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Lo dejamos vacío si no usas updated_at

    /**
     * Obtiene las últimas notificaciones ordenadas por fecha.
     */
    public function getLatestNotifications(int $limit = 5): array
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}